<?php
namespace PublicApp\Command;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PublicApp\Service\Assets;

class AssetsCommand extends Command{
	protected $assetsService;
	protected $env;
	public function __construct(Assets $assetsService, $env){
		$this->assetsService = $assetsService;
		$this->env = $env;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('assets')
			->setDescription("Install assets.")
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output){
		$this->assetsService->linkAssets();
		$this->assetsService->buildIcons();
		return 0;
	}
}
