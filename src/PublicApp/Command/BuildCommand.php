<?php
namespace PublicApp\Command;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PublicApp\Service\Build;

class BuildCommand extends Command{
	protected $buildService;
	public function __construct(Build $buildService){
		$this->buildService = $buildService;
		parent::__construct();
	}
	protected function configure(){
		$this
			->setName('build')
			->setDescription("Build site.")
			->addOption('dist', 'd', InputOption::VALUE_REQUIRED, 'Which dist folder to build to.  May also change some characteristics of how build is done', 'public')
			//-! arg: dest (folder to build into)
			//-! opt: host type (eg apache, github pages, cloudflare pages)
			//-! opt: tasks, array of build steps to run, eg clear (not default), assets, js, css, static pages
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		$this->buildService->buildStaticPages($input->getOption('dist'));
		//-! should eventually take any dist related stuff from `bin/app-install`
		//-! should eventually support doing all build steps
		//-! should eventually support different host targets
		// $this->buildService->linkAssets();
		// $this->buildService->buildSvgs();
		return 0;
	}
}
