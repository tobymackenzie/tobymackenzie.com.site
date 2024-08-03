<?php
namespace PublicApp\Command;
use PublicApp\Service\Build;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class JsAssetsCommand extends Command{
	protected $buildService;
	public function __construct(Build $buildService){
		$this->buildService = $buildService;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('assets:js')
			->setDescription("Build js.")
			->addOption('compiler', 'c', InputOption::VALUE_REQUIRED)
			->addOption('dist', 'd', InputOption::VALUE_REQUIRED, 'Which dist folder to build to.  May also change some characteristics of how build is done', 'public')
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		$this->buildService->buildJS($input->getOption('compiler'), $input->getOption('dist'), $output);
		return 0;
	}
}
