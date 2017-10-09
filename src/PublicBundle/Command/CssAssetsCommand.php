<?php
namespace PublicBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CssAssetsCommand extends ContainerAwareCommand{
	protected function configure(){
		$this
			->setName('public:assets:css')
			->setDescription("Build CSS.")
			->addOption('watch', 'w', InputOption::VALUE_NONE)
		;
	}
	protected function execute(InputInterface $input, OutputInterface $output){
		chdir(__DIR__ . '/../Resources');
		if($input->getOption('watch')){
			passthru('grunt watch:css');
		}else{
			passthru('grunt build:css:' . $this->getContainer()->get('kernel')->getEnvironment());
		}
	}
}
