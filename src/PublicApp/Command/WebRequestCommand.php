<?php
namespace PublicApp\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;

class WebRequestCommand extends Command{
	protected function configure(){
		$this
			->setName('web:request')
			->setDescription("Output response for a given request.")
			->addArgument('path', InputArgument::REQUIRED, 'URL path to request')
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		global $app;
		//--need clean output
		$app->setEnvironment('prod');
		$app->setKernel($app->createKernel());
		$response = $app->getResponse($input->getArgument('path'));
		$output->writeln((string) $response);
		return 0;
	}
}
