<?php
namespace PublicApp\Command;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends Command{
	protected function configure(){
		$this
			->setName('test')
			->setDescription("Run PHPUnit tests.")
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output): int{
		passthru('cd ' . __DIR__ . '/.. && phpunit --colors=always');
		return 0;
	}
}
