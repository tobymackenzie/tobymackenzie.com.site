<?php
namespace PublicApp\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class WebDiffCommand extends Command{
	protected function configure(){
		$this
			->setName('web:diff')
			->setDescription("Compare output response for two different requests.")
			->addArgument('pathA', InputArgument::REQUIRED, 'URL path to request')
			->addArgument('pathB', InputArgument::REQUIRED, 'Other URL path to request')
			->addOption('opts', 'o', InputOption::VALUE_REQUIRED, 'Options to pass to `git diff` command.')
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		global $app;
		$date = date('Ymd.his');
		$outA = __DIR__ . '/' . $date . 'A';
		$outB = __DIR__ . '/' . $date . 'B';
		//--need clean output
		$app->setEnvironment('prod');
		$app->setKernel($app->createKernel());
		$response = $app->getResponse($input->getArgument('pathA'));
		file_put_contents($outA, (string) $response);
		$response = $app->getResponse($input->getArgument('pathB'));
		file_put_contents($outB, (string) $response);
		//-! maybe use symfony process instead of passthru() to get testable output and possibly smarter color handling, etc
		if(`which git`){
			passthru("git diff " . $input->getOption('opts') . " --no-index --color=always -w {$outA} {$outB}");
		}else{
			passthru("diff " . $input->getOption('opts') . " {$outA} {$outB}");
		}
		unlink($outA);
		unlink($outB);
		return 0;
	}
}
