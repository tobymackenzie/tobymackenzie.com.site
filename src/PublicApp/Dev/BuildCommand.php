<?php
namespace PublicApp\Dev;
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
		'clear',
		'css',
		'js',
		'static',
		'svg',
		'webroot',
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
			->addOption('tasks', 't', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'Build tasks to run.')
			->addOption('force', 'f', InputOption::VALUE_NONE, 'Force task to ignore checks for if rebuild needed.')
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		$command = explode(':', $input->getArgument('command'));
		$tasks = $input->getOption('tasks') ?? [];
		$force = $input->getOption('force');
		if(count($command) === 1){
			if(empty($tasks)){
				$tasks = ['assets', 'css', 'js', 'static', 'webroot', 'svg'];
			}
		}else{
			array_unshift($tasks, $command[1]);
		}
		foreach($tasks as $task){
			switch($task){
				case 'assets':
					$this->buildService->linkAssets($input->getOption('dist'));
				break;
				case 'clear':
					$this->buildService->clearBuildDir($input->getOption('dist'));
				break;
				case 'css':
					$this->buildService->buildCSS($input->getOption('dist'), $force, $output);
				break;
				case 'js':
					$this->buildService->buildJS(null, $input->getOption('dist'), $output);
				break;
				case 'static':
					$this->buildService->buildStaticPages($input->getOption('dist'), $force);
				break;
				case 'svg':
					$this->buildService->buildSvgs($input->getOption('dist'));
				break;
				case 'webroot':
					$this->buildService->installWebRootFiles($input->getOption('dist'));
				break;
			}
		}
		return 0;
	}
}
