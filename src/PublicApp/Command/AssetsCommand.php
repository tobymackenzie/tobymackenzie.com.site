<?php
namespace PublicApp\Command;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PublicApp\Service\Build;

class AssetsCommand extends Command{
	protected $buildService;
	public function __construct(Build $buildService){
		$this->buildService = $buildService;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('assets')
			->setDescription("Install assets.")
			->addOption('dist', 'd', InputOption::VALUE_REQUIRED, 'Which dist folder to build to.  May also change some characteristics of how build is done', 'public')
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		$this->buildService->linkAssets($input->getOption('dist'));
		$this->buildService->buildSvgs($input->getOption('dist'));
		return 0;
	}
}
