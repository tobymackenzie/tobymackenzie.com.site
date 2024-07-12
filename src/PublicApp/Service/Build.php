<?php
namespace PublicApp\Service;
use Exception;
use SimpleXMLElement;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use TJM\Data\Model;
use TJM\Files\Files;

class Build extends Model{
	protected $assetLinks = [];
	protected $distPath = 'dist';
	protected $projectPath;
	protected $scriptsPath = 'scripts';
	protected $scriptsDistPath = 'scripts';
	protected $stylesPath = 'styles';
	protected $stylesDistPath = 'styles';
	protected $svgDefaults = [];
	protected $svgSets = [];
	protected $svgs = [];
	protected $svgsDistPath = 'svgs';
	public function __construct($values = null){
		if($values){
			$this->set($values);
		}
	}
	public function linkAssets(){
		if($this->assetLinks){
			$this->createDistDir();
			foreach($this->assetLinks as $link){
				if(is_string($link)){
					$link = ['dest'=> $link, 'src'=> $link];
				}
				if(!(isset($link['dest']) && $link['src'])){
					throw new Exception('Assets link is malformed: ' . json_encode($link));
				}
				if(substr($link['src'], 0, 1) === '/'){
					$from = $link['src'];
				}else{
					$from = $this->projectPath . '/' . $link['src'];
				}
				if(substr($link['dest'], 0, 1) === '/'){
					$at = $link['dest'];
				}else{
					$at = $this->getDistPath() . '/' . $link['dest'];
				}
				if(strpos($from, '*') !== false){
					if(!file_exists($at)){
						exec("mkdir -p {$at}");
					}
					$froms = glob($from);
				}else{
					$froms = [$from];
				}
				foreach($froms as $from){
					Files::symlinkRelativelySafely($at, $from);
				}
			}
		}
	}
	public function clearDistDir(){
		if($this->getDistPath()){
			return exec("rm -rf {$this->getDistPath()}/*");
		}
		return false;
	}
	public function createDistDir(){
		if($this->getDistPath() && !file_exists($this->getDistPath())){
			return exec("mkdir -p {$this->getDistPath()}");
		}
		return false;
	}
	public function buildSvgs(){
		if($this->svgs){
			if(!file_exists($this->getSvgsDistPath())){
				exec("mkdir -p {$this->getSvgsDistPath()}");
			}
			foreach($this->svgs as $svg){
				$attr = [];
				if(is_string($svg)){
					$dest = basename($svg);
					$src = $svg;
					$set = null;
				}else{
					$src = $svg['src'] ?? null;
					$dest = $svg['dest'] ?? basename($src);
					$set = $svg['set'] ?? null;
					if(isset($svg['attr'])){
						$attr = $svg['attr'];
					}
				}
				if($set && isset($this->svgSets[$set])){
					$set = $this->svgSets[$set];
					if(is_string($set)){
						$set = ['src'=> $set];
					}
					if(isset($set['attr'])){
						$attr = array_merge($set['attr'], $attr);
					}
					if(isset($set['src']) && substr($src, 0, 1) !== '/'){
						if(substr($set['src'], 0, 1) !== '/'){
							$set['src'] = $this->projectPath . '/' . $set['src'];
						}
						$src = $set['src'] . '/' . $src;
					}
				}
				if(isset($this->svgDefaults['attr'])){
					$attr = array_merge($this->svgDefaults['attr'], $attr);
				}
				if($src && $dest){
					if(substr($dest, 0, 1) !== '/'){
						$dest = $this->getSvgsDistPath() . '/' . $dest;
					}
					copy($src, $dest);
					if(pathinfo($dest, PATHINFO_EXTENSION) === 'svg' && $attr){
						$svg = new SimpleXMLElement(file_get_contents($dest));
						if($svg){
							foreach($attr as $key=> $value){
								if(isset($svg[$key])){
									$svg[$key] = $value;
								}else{
									$svg->addAttribute($key, $value);
								}
							}
							file_put_contents($dest, $svg->asXML());
						}
					}
				}
			}
		}
	}

	/*=====
	==css
	=====*/
	public function buildCSS($env = 'dev', OutputInterface $output = null){
		if(`which sassc`){
			$sassBin = 'sassc';
		}elseif(`which sass`){
			$sassBin = 'sass';
		}else{
			throw new Exception("No sass command found on shell path.");
		}
		if($env === 'prod'){
			$sassBin .= ' --style compressed';
		}else{
			$sassBin .= " --line-numbers";
		}
		if(`which postcss`){
			$postCSSBin = "postcss --config . --no-map";
		}else{
			$postCSSBin = null;
		}
		$basePath = __DIR__ . '/..';
		chdir($basePath);
		$processes = [];
		$dest = $this->getStylesDistPath();
		if(!file_exists($dest)){
			exec("mkdir -p {$dest}");
		}
		foreach(glob("{$this->getStylesPath()}/builds/*.scss") as $file){
			$nameBase = basename($file, '.scss');
			if($nameBase[0] === '_'){
				continue;
			}
			$run = "{$sassBin} {$file}";
			if($postCSSBin){
				$run .= ' | ' . $postCSSBin;
			}
			$fileDest = "{$dest}/{$nameBase}.css";
			$run .= " > {$fileDest}";
			if($output && $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE){
				$run .= " && echo '{$nameBase}: full size:' `cat {$fileDest} | wc -c` 'gzip size:' `gzip -c {$fileDest} | wc -c`";
			}
			$process = Process::fromShellCommandline($run, $basePath);
			$process->start();
			$processes[] = $process;
		}
		foreach($processes as $process){
			$process->wait();
			if(!$process->isSuccessful()){
				if($output){
					$output->writeln('<error>CSS build failure</error>');
				}else{
					throw new Exception("CSS build failure");
				}
			}
			if($output) $output->write($process->getErrorOutput() . $process->getOutput());
		}
	}

	/*=====
	==js
	=====*/
	public function buildJS($compiler = 'rollup', $env = 'dev', OutputInterface $output = null){
		$src = $this->getScriptsPath();
		$dest = $this->getScriptsDistPath();
		if($env === 'dev'){
			Files::symlinkRelativelySafely($dest, $src);
			if($output) $output->writeln("symlink $dest, $src");
		}else{
			if(!file_exists($dest)){
				exec("mkdir -p {$dest}");
			}
			if($compiler === 'uglify'){
				$files = $this->recursiveGlob($src . '/*.js');
			}else{
				$files = glob($src . '/*.js');
			}
			foreach($files as $file){
				$baseName = str_replace($src . '/', '', $file);
				if($baseName !== 'proxy-worker.js' || $compiler === 'webpack'){ //-!! uglify can't seem to process es6
					switch($compiler){
						//-# rollup builds about 500 bytes smaller than webpack currently, so it's default.
						case 'rollup':
						default:
							$command = "rollup {$file} --output.format iife | uglifyjs --compress --mangle > {$dest}/{$baseName}";
						break;
						case 'uglify':
							$command = str_replace("\n", '', "uglifyjs
								--compress
								--mangle
								-o {$dest}/{$baseName}
								-- {$file}"
							);
						break;
						case 'webpack':
							$command = "webpack {$file} --output {$dest}/{$baseName} --mode production";
						break;
					}
					passthru($command);
				}
			}

			//--loaded lib
			Files::symlinkRelativelySafely("{$dest}/prismjs", "{$src}/lib/prismjs");
			if($output) $output->writeln("{$dest}/prismjs" . ' ' .  "{$src}/lib/primsjs");
		}
	}
	/*=====
	==paths
	=====*/
	public function getDistPath(){
		if(substr($this->distPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->distPath;
		}else{
			return $this->distPath;
		}
	}
	public function getSvgsDistPath(){
		if(substr($this->svgsDistPath, 0, 1) !== '/'){
			return $this->getDistPath() . '/' . $this->svgsDistPath;
		}else{
			return $this->svgsDistPath;
		}
	}
	public function getScriptsPath(){
		if(substr($this->scriptsPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->scriptsPath;
		}else{
			return $this->scriptsPath;
		}
	}
	public function getScriptsDistPath(){
		if(substr($this->scriptsDistPath, 0, 1) !== '/'){
			return $this->getDistPath() . '/' . $this->scriptsDistPath;
		}else{
			return $this->scriptsDistPath;
		}
	}
	public function getStylesPath(){
		if(substr($this->stylesPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->stylesPath;
		}else{
			return $this->stylesPath;
		}
	}
	public function getStylesDistPath(){
		if(substr($this->stylesDistPath, 0, 1) !== '/'){
			return $this->getDistPath() . '/' . $this->stylesDistPath;
		}else{
			return $this->stylesDistPath;
		}
	}

	/*=====
	==helpers
	=====*/
	protected function recursiveGlob($pattern, $flags = 0){
		//-@http://stackoverflow.com/a/17161106/1139122
		$files = glob($pattern, $flags);
		foreach(glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir){
			$files = array_merge($files, $this->recursiveGlob($dir . '/' . basename($pattern), $flags));
		}
		return $files;
	}
}
