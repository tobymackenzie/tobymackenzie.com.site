<?php
namespace PublicBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase{
	/**
	* @dataProvider getSimplePageActions
	*/
	public function testSimplePageActions($path){
		$client = static::createClient([], ['HTTP_HOST'=> $this->getRequestHost()]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
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
			$this->assertContains('</html>', $client->getResponse()->getContent(), 'Content should contain ending </html>, implying that a full html document was rendered.');
		}
	}

	/*=====
	==data
	=====*/
	protected function getRequestHost(){
		$client = static::createClient();
		return $client->getContainer()->getParameter('host.prefix') . $client->getContainer()->getParameter('public.host.subdomain') . $client->getContainer()->getParameter('public.host');
	}
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
