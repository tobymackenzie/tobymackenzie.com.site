<?php
namespace ProtectedBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase{
	public function testNotFoundAction(){
		$redirectingPaths = Array(
			'/'
			,'/foo/bar'
			,'/foo?bar=biz'
			,'/foo?bar[]=biz&bar[]=baz'
		);
		$client = static::createClient(); //--need to do this because symfony won't give us the container without a client, and passing 'HTTP_HOST' to the request stopped working
		foreach($redirectingPaths as $path){
			$client = static::createClient([], ['HTTP_HOST'=> $client->getContainer()->getParameter('protected.host')]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
			$crawler = $client->request('GET', $path);
			$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code should be 200 success');
			$this->assertContains('</html>', $client->getResponse()->getContent(), 'Content should contain ending </html>, implying that a full html document was rendered.');
			// $desiredUrl = 'http://www.tobymackenzie.com' . $path;
			// $this->assertContains($desiredUrl, $client->getResponse()->getContent(), 'Content should contain ending the desired URL (), implying that a full html document was rendered.');
			// $this->assertEquals(301, $client->getResponse()->getStatusCode(), 'Status code should be 301, indicating a permanent redirect');
			// $this->assertTrue($client->getResponse()->isRedirect($desiredUrl), "'{$path}' should be redirected to '{$desiredUrl}', was instead '{$client->getResponse()->headers->get('Location')}'");
		}
	}
	public function testTestAction(){
		$testPaths = Array(
			'test'=> ''
			,'test/bar'=> 'bar'
			,'test/foo'=> 'foo'
		);
		$client = static::createClient(); //--need to do this because symfony won't give us the container without a client, and passing 'HTTP_HOST' to the request stopped working
		foreach($testPaths as $path=> $expectedResult){
			$client = static::createClient([], ['HTTP_HOST'=> $client->getContainer()->getParameter('protected.host')]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
			$crawler = $client->request('GET', $path);
			$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code should be 200, indicating a success');
			$this->assertTrue(!$client->getResponse()->isRedirection(), 'Should not be redirection');
			$this->assertEquals($client->getResponse()->getContent(), $expectedResult, "Route '{$path}' should yield content '{$expectedResult}");
		}
	}
}
