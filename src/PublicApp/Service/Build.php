<?php
namespace PublicApp\Service;
use DateTime;
use Exception;
use SimpleXMLElement;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\RouterInterface;
use TJM\Data\Model;
use TJM\Files\Files;
use TJM\StaticWebTasks\Task as StaticWebTask;
use TJM\WebCrawler\Response;
use TJM\WikiSite\WikiSite;

class Build extends Model{
	static protected $isBuild = false;

	protected $assetLinks = [];
	protected string $buildPath = 'dist';
	protected string $canonicalHost;
	protected $assetsRoot = '/_assets';
	protected $projectPath;
	protected ?RouterInterface $router = null;
	protected $scriptsPath = 'scripts';
	protected $scriptsDest = 'scripts';
	protected array $staticFormats = ['md', 'txt', 'xhtml'];
	protected $stylesPath = 'styles';
	protected $stylesDest = 'styles';
	protected $svgDefaults = [];
	protected $svgSets = [];
	protected $svgs = [];
	protected $svgsDest = 'svgs';
	protected ?WikiSite $wikiSite = null;

	public function __construct($values = null){
		if($values){
			$this->set($values);
		}
	}
	static public function isBuilding(){
		return static::$isBuild;
	}
	public function linkAssets($dist = 'public'){
		if($this->assetLinks){
			$this->createAssetsDir($dist);
			if($dist === 'dev'){
				foreach($this->assetLinks as $link){
					Files::symlinkRelativelySafely($this->getAssetsDest($dist) . '/' . $link['dest'], $this->getAssetsDest('public') . '/' . $link['dest']);
				}
			}else{
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
						$at = $this->getAssetsDest() . '/' . $link['dest'];
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
	}
	public function createAssetsDir($dist = 'public'){
		if($this->getAssetsDest($dist) && !file_exists($this->getAssetsDest($dist))){
			return exec("mkdir -p {$this->getAssetsDest($dist)}");
		}
		return false;
	}
	public function clearBuildDir($dist = 'public'){
		$dest = $this->getDistPath($dist);
		if($dest && is_dir($dest)){
			return passthru("find -P {$dest} -mindepth 1 -delete");
		}
		return false;
	}
	public function buildSvgs($dist = 'public'){
		if($this->svgs){
			$distPath = $this->getSvgDistPath($dist);
			if($dist === 'dev'){
				Files::symlinkRelativelySafely($distPath, $this->getSvgDistPath('public'));
			}else{
				if(!file_exists($distPath)){
					exec("mkdir -p {$distPath}");
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
							$dest = $distPath . '/' . $dest;
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
	}
	public function installWebRootFiles($dist = 'public'){
		foreach(glob(__DIR__ . '/../www/{.??*,.[!.],*}', GLOB_BRACE) as $from){
			$to = $this->getDistPath($dist) . '/' . basename($from);
			Files::symlinkRelativelySafely($to, $from, $this->projectPath);
		}
	}

	/*=====
	==css
	=====*/
	public function buildCSS($dist = 'public', $force = false, OutputInterface $output = null){
		if(`which sassc`){
			$sassBin = 'sassc';
		}elseif(`which sass`){
			$sassBin = 'sass';
		}else{
			throw new Exception("No sass command found on shell path.");
		}
		if($dist === 'public'){
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
		$dest = $this->getStylesDistPath($dist);
		if(!file_exists($dest)){
			exec("mkdir -p {$dest}");
		}
		foreach(glob("{$this->getStylesPath()}/builds/*.scss") as $file){
			$nameBase = basename($file, '.scss');
			if($nameBase[0] === '_'){
				continue;
			}
			$fileDest = "{$dest}/{$nameBase}.css";

			//--check if need built, skip if not
			//-# need to check whole src styles dir, no easy way to check only files that would be in this build
			if(!$force && !$this->doesFileNeedBuilt($fileDest, $this->getStylesPath())){
				if($output && $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE){
					$output->writeln("Skipping '{$nameBase}' build source, doesn't need built");
				}
				continue;
			}

			$run = "{$sassBin} {$file}";
			if($postCSSBin){
				$run .= ' | ' . $postCSSBin;
			}
			$run .= " > {$fileDest}";
			if($output && $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE){
				$run .= " && echo '{$nameBase}: full size:' `cat {$fileDest} | wc -c` 'gzip size:' `gzip -c {$fileDest} | wc -c`";
			}
			$process = Process::fromShellCommandline($run, $basePath);
			$process->start();
			$processes[] = $process;
		}
		if(empty($processes)){
			if($output && $output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE){
				$output->writeln("No styles need built");
			}
			return false;
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
	public function buildJS($compiler = 'rollup', $dist = 'dev', OutputInterface $output = null){
		$src = $this->getScriptsPath();
		$dest = $this->getScriptsDistPath($dist);
		if($dist === 'dev'){
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
	==pages
	=====*/
	public function buildStaticPages($dist = 'public', $force = false, OutputInterface $output = null){
		//--no static for dev site
		if($dist === 'dev'){
			return false;
		}
		static::$isBuild = true;
		$this->router->getContext()->setHost($this->canonicalHost);

		//--build path array
		$exclude = [
			'/.htaccess',
			'/_/*',
			'/_assets/*',
			'/_assets-dev/*',
			'/_maintenance.html',
			'/examples',
			'/favicon.ico',
			'/icon-*',
			'/index*.php',
			'/sites',
		];
		//---get wiki page paths
		$paths = $this->wikiSite->getPagePaths();
		//---add multi-format other paths
		$paths[] = $this->router->generate('public_robots');
		$paths[] = $this->router->generate('public_site_nav');
		foreach($paths as $key=> $path){
			//--disable blog for now
			if(substr($path, 0, 5) === '/blog'){
				unset($paths[$key]);
				continue;
			}

			if(!pathinfo($path, PATHINFO_EXTENSION)){
				if($path === '/'){
					$path = '/index';
				}
				foreach($this->staticFormats as $format){
					$paths[] = $path . '.' . $format;
				}
			}
		}

		//--remove files not modified since last build
		//-! must force if template changes are made
		if(!$force){
			$wiki = $this->wikiSite->getWiki();
			$self = $this;
			$needsBuilt = function($path) use($dist, $self, $wiki){
				//-! misses non-page, symfony paths
				//-! this getting path logic is indirect, recreating logic that WikiSite has to do.  WikiSite or Wiki should provide method to get the path directly
				$srcPath = $path;
				if(substr($srcPath, 0, 1) === '/'){
					$srcPath = substr($srcPath, 1);
				}
				if(empty($srcPath)){
					$srcPath = 'index';
				}
				$extension = pathinfo($srcPath, PATHINFO_EXTENSION);
				$srcPath = $extension ? substr($srcPath, 0, -1 * (strlen($extension) + 1)) : $srcPath;
				$srcPath = $wiki->getPageFilePath($srcPath);
				if(!file_exists($srcPath)){
					return true;
				}
				$destPath = $path;
				if($destPath === '/'){
					$destPath = '/index.html';
				}elseif(!$extension){
					$destPath .= '.html';
				}
				$destPath = $self->getDistPath($dist) . $destPath;
				return $self->doesFileNeedBuilt($destPath, $srcPath);
			};
			foreach($paths as $key=> $path){
				if(!$needsBuilt($path)){
					unset($paths[$key]);

					//--must exclude dest from rsync so that it doesn't get removed by task for not being part of build
					if(pathinfo($path, PATHINFO_EXTENSION)){
						$exclude[] = $path;
					}else{
						$exclude[] = $path . '.html';
					}
				}
			}
		}

		//---add single format other paths
		$paths[] = $this->router->generate('public_app_manifest');
		$paths[] = $this->router->generate('public_bing_verification');
		$paths[] = $this->router->generate('public_google_verification');
		// $paths[] = $this->router->generate('public_proxy_service_worker');

		//--build
		//-! for certain targets eg github pages, we will need to generate redirect files for aliases
		$host = $this->canonicalHost;
		$task = new StaticWebTask(
			[
				// 'client'=> 'php ' . __DIR__ . '/../bin/console web:request',
				'client'=> function($path) use($host){
					global $app;
					//--need clean output
					if($app->getEnvironment() !== 'prod'){
						$app->setEnvironment('prod');
						$app->setKernel($app->createKernel());
					}
					$syResponse = $app->getResponse(Request::create($path, 'GET', [], [], [], [
						'HTTP_HOST'=> $host,
						'HTTPS'=> 'on',
					]));
					$headers = [];
					foreach($syResponse->headers as $key=> $value){
						foreach($value as $subValue){
							$headers[] = $key . ': ' . $subValue;
						}
					}
					$response = new Response($syResponse->getContent(), $syResponse->getStatusCode(), $headers);
					return $response;
				},
				'follow'=> false,
			],
			$this->getDistPath($dist),
			[
				//-! should come up with list from building these elsewhere?
				'exclude'=> $exclude,
				'paths'=> $paths,
			]
		);
		$task->do();
		//-! should task return paths?
		static::$isBuild = false;
		return $paths;
	}

	/*=====
	==paths
	=====*/
	public function getAssetsDest($dist = 'public'){
		return $this->getDistPath($dist) . $this->assetsRoot;
	}
	public function getDistPath($dist = 'public'){
		if($dist === 'prod'){
			$dist = 'public';
		}
		return $this->buildPath . '/' . $dist;
	}
	public function getSvgDistPath($dist = 'public'){
		return $this->getAssetsDest($dist) . '/' . $this->svgsDest;
	}
	public function getScriptsPath(){
		if(substr($this->scriptsPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->scriptsPath;
		}else{
			return $this->scriptsPath;
		}
	}
	public function getScriptsDistPath($dist = 'public'){
		return $this->getAssetsDest($dist) . '/' . $this->scriptsDest;
	}
	public function getStylesPath(){
		if(substr($this->stylesPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->stylesPath;
		}else{
			return $this->stylesPath;
		}
	}
	public function getStylesDistPath($dist = 'public'){
		return $this->getAssetsDest($dist) . '/' . $this->stylesDest;
	}

	/*=====
	==helpers
	=====*/
	protected function doesFileNeedBuilt($dest, $src){
		if(!file_exists($dest)){
			return true;
		}
		if(!file_exists($src)){
			return false;
		}
		if(is_dir($dest)){
			$var = exec('find ' . $dest . ' -type f -name "*.scss" -printf "%T@ %p\n" | sort -n | tail -1');
			$var = explode(' ', $var)[0];
			$destMod = new DateTime('@' . $var);
		}else{
			$destMod = new DateTime('@' . filemtime($dest));
		}
		if(is_dir($src)){
			$var = exec('find ' . $src . ' -type f -name "*.scss" -printf "%T@ %p\n" | sort -n | tail -1');
			$var = explode(' ', $var)[0];
			$srcMod = new DateTime('@' . $var);
		}else{
			$srcMod = new DateTime('@' . filemtime($src));
		}
		return $destMod < $srcMod;
	}
	protected function recursiveGlob($pattern, $flags = 0){
		//-@http://stackoverflow.com/a/17161106/1139122
		$files = glob($pattern, $flags);
		foreach(glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir){
			$files = array_merge($files, $this->recursiveGlob($dir . '/' . basename($pattern), $flags));
		}
		return $files;
	}
}
