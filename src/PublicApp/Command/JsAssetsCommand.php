<?php
namespace PublicApp\Command;
use PublicApp\Service\Assets;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TJM\Files\Files;

class JsAssetsCommand extends Command{
	protected $assetsService;
	protected $env;
	public function __construct(Assets $assetsService, $env){
		$this->assetsService = $assetsService;
		$this->env = $env;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('assets:js')
			->setDescription("Build js.")
			->addOption('compiler', 'c', InputOption::VALUE_REQUIRED)
		;
	}
	public function recursiveGlob($pattern, $flags = 0){
		//-@http://stackoverflow.com/a/17161106/1139122
		$files = glob($pattern, $flags);
		foreach(glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir){
			$files = array_merge($files, $this->recursiveGlob($dir . '/' . basename($pattern), $flags));
		}
		return $files;
	}
	protected function execute(InputInterface $input, OutputInterface $output){
		$src = $this->assetsService->getScriptsPath();
		$dest = $this->assetsService->getScriptsDistPath();
		if($this->env === 'dev'){
			Files::symlinkRelativelySafely($dest, $src);
			$output->writeln("symlink $dest, $src");
		}else{
			$compiler = $input->getOption('compiler');
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
							$command = "rollup {$file} --output.format iife | uglifyjs --compress --mangle --stats > {$dest}/{$baseName}";
						break;
						case 'uglify':
							$command = str_replace("\n", '', "uglifyjs
								--compress
								--mangle
								-o {$dest}/{$baseName}
								--stats
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
		}
		return 0;
	}
}
