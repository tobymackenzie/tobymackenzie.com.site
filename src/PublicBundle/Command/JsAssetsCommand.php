<?php
namespace PublicBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JsAssetsCommand extends ContainerAwareCommand{
	protected function configure(){
		$this
			->setName('public:assets:js')
			->setDescription("Build js.")
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
		$src = __DIR__ . '/../Resources/scripts';
		$dest = __DIR__ . '/../Resources/public/scripts/prod';
		$files = $this->recursiveGlob($src . '/*.js');
		foreach($files as $file){
			$baseName = str_replace($src . '/', '', $file);
			if($baseName !== 'proxy-worker.js'){ //-!! uglify can't seem to process es6
				$command = str_replace("\n", '', "uglifyjs
					--compress
					--mangle
					-o {$dest}/{$baseName}
					--stats
					-- {$file}"
				);
				$output->writeln("Uglifying {$baseName}");
				passthru($command);
			}
		}
	}
}
