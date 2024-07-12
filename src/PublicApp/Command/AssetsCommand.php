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
	protected $env;
	public function __construct(Build $buildService, $env){
		$this->buildService = $buildService;
		$this->env = $env;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('assets')
			->setDescription("Install assets.")
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		$this->buildService->linkAssets();
		$this->buildService->buildSvgs();
		return 0;
	}
}
