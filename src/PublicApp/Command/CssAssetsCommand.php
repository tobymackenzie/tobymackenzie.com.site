<?php
namespace PublicApp\Command;
use Exception;
use PublicApp\Service\Assets;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class CssAssetsCommand extends Command{
	protected $assetsService;
	protected $env;
	public function __construct(Assets $assetsService, $env){
		$this->assetsService = $assetsService;
		$this->env = $env;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('assets:css')
			->setDescription("Build CSS.")
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
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
		$basePath = __DIR__ . '/..';
		chdir($basePath);
		$processes = [];
		$dest = $this->assetsService->getStylesDistPath();
		if(!file_exists($dest)){
			exec("mkdir -p {$dest}");
		}
		foreach(glob("{$this->assetsService->getStylesPath()}/builds/*.scss") as $file){
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
			if($output->getVerbosity() >= OutputInterface::VERBOSITY_VERBOSE){
				$run .= " && echo '{$nameBase}: full size:' `cat {$fileDest} | wc -c` 'gzip size:' `gzip -c {$fileDest} | wc -c`";
			}
			$process = Process::fromShellCommandline($run, $basePath);
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
		return 0;
	}
}
