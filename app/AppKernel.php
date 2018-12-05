<?php
use TJM\Bundle\StandardEditionBundle\Component\AppKernel as Base;

class AppKernel extends Base{
	public function registerBundles(){
		$bundles = array(
			new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
			new Symfony\Bundle\SecurityBundle\SecurityBundle(),
			new Symfony\Bundle\TwigBundle\TwigBundle(),
			new Symfony\Bundle\MonologBundle\MonologBundle(),
			new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
		);

		if (in_array($this->getEnvironment(), array('dev', 'test'))) {
			$bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
			$bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
			$bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
			$bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
		// }else{
		// 	$bundles[] = new FOS\HttpCacheBundle\FOSHttpCacheBundle();
		}

		$bundles[] = new TJM\Bundle\StandardEditionBundle\TJMStandardEditionBundle();
		return array_merge($bundles, Array(
			new TJM\Bundle\BaseBundle\TJMBaseBundle()
			,new PublicBundle\PublicBundle()
			,new ProtectedBundle\ProtectedBundle()
		));
	}
}
