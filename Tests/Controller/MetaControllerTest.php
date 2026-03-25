<?php
namespace PublicApp\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MetaControllerTest extends WebTestCase{
	/**
	* @dataProvider getUppercaseNonRedirects
	*/
	public function testUppercaseNonRedirects($path){
		$client = static::createClient();
		$client->request('GET', $path);
		$this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Status code should be 404 for request ' . $path);
		$this->assertTrue(!$client->getResponse()->isRedirection(), 'Request ' . $path . ' should not be redirection');
	}
	/**
	* @dataProvider getUppercaseRedirects
	*/
	public function testUppercaseRedirects($path){
		$client = static::createClient();
		$client->request('GET', $path);
		$this->assertEquals(302, $client->getResponse()->getStatusCode(), 'Status code should be 302 for request ' . $path);
		$this->assertTrue($client->getResponse()->isRedirection(), 'Request ' . $path . ' should be redirection');
	}

	/*=====
	==data
	=====*/
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
