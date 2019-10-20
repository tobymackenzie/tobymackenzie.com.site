<?php
namespace PublicApp\Command;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CssAssetsCommand extends Command{
	protected $env;
	public function __construct($env){
		$this->env = $env;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('assets:css')
			->setDescription("Build CSS.")
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output){
		if(`which sassc`){
			$sassBin = 'sassc';
		}elseif(`which sass`){
			$sassBin = 'sass';
		}else{
			throw new Exception("No sass command found on shell path.");
		}
		if($this->env === 'prod'){
			$sassBin .= ' --style compressed';
		}else{
			$sassBin .= " --line-numbers";
		}
		if(`which postcss`){
			$postCSSBin = "postcss --config . --no-map";
		}else{
			$postCSSBin = null;
		}
		$resourcesPath = __DIR__ . '/../Resources';
		chdir($resourcesPath);
		$processes = [];
		foreach(glob('./styles/builds/*.scss') as $file){
			$nameBase = basename($file, '.scss');
			if($nameBase[0] === '_'){
				continue;
			}
			$run = "{$sassBin} {$file}";
			if($postCSSBin){
				$run .= ' | ' . $postCSSBin;
			}
			$run .= " > ./public/styles/{$this->env}/{$nameBase}.css";
			$process = new Process($run, $resourcesPath);
			$process->start();
			$processes[] = $process;
		}
		foreach($processes as $process){
			$process->wait();
			if(!$process->isSuccessful()){
				$output->writeln('<error>Build failure</error>');
			}
			$output->write($process->getErrorOutput() . $process->getOutput());
		}
	}
}
