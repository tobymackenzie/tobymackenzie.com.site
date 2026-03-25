<?php
namespace PublicApp\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;

class WebRequestCommand extends Command{
	protected function configure(){
		$this
			->setName('web:request')
			->setDescription("Output response for a given request.")
			->addArgument('path', InputArgument::REQUIRED, 'URL path to request')
			->addOption('out', 'o', InputOption::VALUE_REQUIRED, 'what to output, one of: headers, body')
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		global $app;
		//--need clean output
		$app->setEnvironment('prod');
		$app->setKernel($app->createKernel());
		$response = $app->getResponse($input->getArgument('path'));
		$out = $input->getOption('out');
		switch(strtolower($out)){
			case 'h':
			case 'head':
			case 'header':
			case 'headers':
				//-# first line is approximate, based on code from Symfony Response
				$output->writeln(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), Response::$statusTexts[$response->getStatusCode()] ?? 'Unknown status, real output will be different'));
				$output->writeln((string) $response->headers);
			break;
			case 'b':
			case 'body':
			case 'content':
				$output->writeln($response->getContent());
			break;
			default:
				$output->writeln((string) $response);
			break;
		}
		return 0;
	}
}
