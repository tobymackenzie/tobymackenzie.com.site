<?php
namespace PublicBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MetaControllerTest extends WebTestCase{
	/**
	* @dataProvider getUppercaseNonRedirects
	*/
	public function testUppercaseNonRedirects($path){
		$client = static::createClient([], ['HTTP_HOST'=> $this->getRequestHost()]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
		$client->request('GET', $path);
		$this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Status code should be 404 for request ' . $path);
		$this->assertTrue(!$client->getResponse()->isRedirection(), 'Request ' . $path . ' should not be redirection');
	}
	/**
	* @dataProvider getUppercaseRedirects
	*/
	public function testUppercaseRedirects($path){
		$client = static::createClient([], ['HTTP_HOST'=> $this->getRequestHost()]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
		$client->request('GET', $path);
		$this->assertEquals(302, $client->getResponse()->getStatusCode(), 'Status code should be 302 for request ' . $path);
		$this->assertTrue($client->getResponse()->isRedirection(), 'Request ' . $path . ' should be redirection');
	}

	/*=====
	==data
	=====*/
	protected function getRequestHost(){
		$client = static::createClient();
		return $client->getContainer()->getParameter('public.host');
	}
	public function getUppercaseNonRedirects(){
		return [
			['/Abutt']
			,['/wEb-DaVe']
			,['/INDEXxx.txt']
		];
	}
	public function getUppercaseRedirects(){
		return [
			['/About']
			,['/aBout']
			,['/wEb-DeV']
			,['/INDEX.txt']
		];
	}
}
