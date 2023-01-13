<?php
namespace ProtectedApp\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase{
	/**
	* @dataProvider getNotFoundActions
	*/
	public function testNotFoundAction($path){
		$client = static::createClient();
		$client->request('GET', $path);
		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code should be 200 success');
		$this->assertStringContainsStringIgnoringCase('</html>', $client->getResponse()->getContent(), 'Content should contain ending </html>, implying that a full html document was rendered.');
		$this->assertStringContainsStringIgnoringCase('probably want to visit', $client->getResponse()->getContent());
		// $desiredUrl = 'http://www.tobymackenzie.com' . $path;
		// $this->assertStringContainsStringIgnoringCase($desiredUrl, $client->getResponse()->getContent(), 'Content should contain ending the desired URL (), implying that a full html document was rendered.');
		// $this->assertEquals(301, $client->getResponse()->getStatusCode(), 'Status code should be 301, indicating a permanent redirect');
		// $this->assertTrue($client->getResponse()->isRedirect($desiredUrl), "'{$path}' should be redirected to '{$desiredUrl}', was instead '{$client->getResponse()->headers->get('Location')}'");
	}
	/**
	* @dataProvider getTestActions
	*/
	public function testTestAction($path, $expectedResult){
		$client = static::createClient();
		$client->request('GET', $path);
		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code should be 200, indicating a success');
		$this->assertTrue(!$client->getResponse()->isRedirection(), 'Should not be redirection');
		$this->assertEquals($client->getResponse()->getContent(), $expectedResult, "Route '{$path}' should yield content '{$expectedResult}");
	}

	/*=====
	==data
	=====*/
	public function getNotFoundActions(){
		return [
			['/']
			,['/about']
			,['/about.txt']
		];
	}
	public function getTestActions(){
		return [
			['test', '']
			,['test/bar', 'bar']
			,['test/foo', 'foo']
			,['test/<b>foo</b>', '&lt;b&gt;foo&lt;/b&gt;']
		];
	}
}
