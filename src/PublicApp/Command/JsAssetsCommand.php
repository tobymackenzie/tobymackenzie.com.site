<?php
namespace PublicApp\Command;
use PublicApp\Service\Build;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class JsAssetsCommand extends Command{
	protected $buildService;
	protected $env;
	public function __construct(Build $buildService, $env){
		$this->buildService = $buildService;
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
	protected function execute(InputInterface $input, OutputInterface $output): int{
		$this->buildService->buildJS($input->getOption('compiler'), $this->env, $output);
		return 0;
	}
}
