<?php
namespace PublicApp\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase{
	/**
	* @dataProvider getSimplePageActions
	*/
	public function testSimplePageActions($path){
		$client = static::createClient();
		$client->request('GET', $path);
		$extension = pathinfo($path, PATHINFO_EXTENSION);
		if(!$extension){
			$extension = 'html';
		}
		switch($extension){
			case 'html':
				$contentType = 'text/html';
			break;
			case 'txt':
				$contentType = 'text/plain';
			break;
			case 'xhtml':
				$contentType = 'application/xhtml+xml';
			break;
		}
		$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code for ' . $path . ' should be 200, indicating a success');
		$this->assertTrue(!$client->getResponse()->isRedirection(), 'Should not be redirection');
		$this->assertStringStartsWith($contentType, $client->getResponse()->headers->get('Content-Type'), 'Content should be of type \'' . $contentType . '\'.');
		if($extension === 'html' || $extension === 'xhtml'){
			$this->assertStringContainsStringIgnoringCase('</html>', $client->getResponse()->getContent(), 'Content should contain ending </html>, implying that a full html document was rendered.');
		}
	}
	public function testTrailingSlash404(){
		$path = '/foo/';
		$client = static::createClient();
		$client->request('GET', $path);
		$this->assertTrue(!$client->getResponse()->isRedirection(), 'Non-existant path ' . $path . ' with trailing slash should not be a redirect.');
		$this->assertEquals(404, $client->getResponse()->getStatusCode(), 'Status code for ' . $path . ' should be 404, indicating not found.');
	}

	/*=====
	==data
	=====*/
	// protected function getRequestHost($client){
	// 	return $client->getContainer()->getParameter('public.host');
	// }
	public function getSimplePageActions(){
		return [
			['/']
			,['/about']
			,['/index.txt']
			,['/about.txt']
			,['/index.xhtml']
			,['/about.xhtml']
		];
	}
}
