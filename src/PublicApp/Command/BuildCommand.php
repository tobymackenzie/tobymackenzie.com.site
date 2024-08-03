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
	const TASKS = [
		'assets',
		'css',
		'js',
		'static',
		'webroot',
		'svg',
	];
	public function __construct(Build $buildService){
		$this->buildService = $buildService;
		parent::__construct();
	}
	protected function configure(){
		$aliases = [];
		foreach(static::TASKS as $task){
			$aliases[] = 'build:' . $task;
		}
		$this
			->setName('build')
			->setAliases($aliases)
			->setDescription("Run site build task(s).  Run all with `build` command, one with `build:*` command.")
			->addOption('dist', 'd', InputOption::VALUE_REQUIRED, 'Which dist folder to build to.  May also change some characteristics of how build is done', 'public')
			//-! arg: dest (folder to build into)
			//-! opt: host type (eg apache, github pages, cloudflare pages)
			//-! opt: tasks, array of build steps to run, eg clear (not default), assets, js, css, static pages
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		$command = explode(':', $input->getArgument('command'));
		if(count($command) === 1){
			$tasks = ['assets', 'css', 'js', 'static', 'svg'];
		}else{
			$tasks = [$command[1]];
		}
		foreach($tasks as $task){
			switch($task){
				case 'assets':
					$this->buildService->linkAssets($input->getOption('dist'));
				break;
				case 'css':
					$this->buildService->buildCSS($input->getOption('dist'), $output);
				break;
				case 'js':
					$this->buildService->buildJS(null, $input->getOption('dist'), $output);
				break;
				case 'static':
					$this->buildService->buildStaticPages($input->getOption('dist'));
				break;
				case 'svg':
					$this->buildService->buildSvgs($input->getOption('dist'));
				break;
				case 'webroot':
					$this->buildService->installWebRootFiles($input->getOption('dist'));
				break;
			}
		}
		//-! should eventually support different host targets
		return 0;
	}
}
