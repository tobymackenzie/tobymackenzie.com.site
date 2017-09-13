<?php
namespace PublicBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase{
	public function testSimplePageHTMLActions(){
		$testPaths = Array(
			'/'
			,'/about'
		);
		$client = static::createClient(); //--need to do this because symfony won't give us the container without a client, and passing 'HTTP_HOST' to the request stopped working
		foreach($testPaths as $path){
			$client = static::createClient([], ['HTTP_HOST'=> $client->getContainer()->getParameter('host.prefix') . $client->getContainer()->getParameter('public.host.subdomain') . $client->getContainer()->getParameter('public.host')]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
			$crawler = $client->request('GET', $path);
			$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code should be 200, indicating a success');
			$this->assertTrue(!$client->getResponse()->isRedirection(), 'Should not be redirection');
			$this->assertContains('</html>', $client->getResponse()->getContent(), 'Content should contain ending </html>, implying that a full html document was rendered.');
		}
	}
	public function testSimplePageTxtActions(){
		$testPaths = Array(
			'/index.txt'
			,'/about.txt'
		);
		$client = static::createClient(); //--need to do this because symfony won't give us the container without a client, and passing 'HTTP_HOST' to the request stopped working
		foreach($testPaths as $path){
			$client = static::createClient([], ['HTTP_HOST'=> $client->getContainer()->getParameter('host.prefix') . $client->getContainer()->getParameter('public.host.subdomain') . $client->getContainer()->getParameter('public.host')]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
			$crawler = $client->request('GET', $path);
			$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code should be 200, indicating a success');
			$this->assertTrue(!$client->getResponse()->isRedirection(), 'Should not be redirection');
			$this->assertStringStartsWith('text/plain', $client->getResponse()->headers->get('Content-Type'), 'Content should be of type \'text/plain\'.');
		}
	}
	public function testSimplePageXTMLActions(){
		$testPaths = Array(
			'/index.xhtml'
			,'/about.xhtml'
		);
		$client = static::createClient(); //--need to do this because symfony won't give us the container without a client, and passing 'HTTP_HOST' to the request stopped working
		foreach($testPaths as $path){
			$client = static::createClient([], ['HTTP_HOST'=> $client->getContainer()->getParameter('host.prefix') . $client->getContainer()->getParameter('public.host.subdomain') . $client->getContainer()->getParameter('public.host')]); //-# must be here because multiple subsequent requests seem to be able to affect each other if done with the same client
			$crawler = $client->request('GET', $path);
			$this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Status code should be 200, indicating a success');
			$this->assertTrue(!$client->getResponse()->isRedirection(), 'Should not be redirection');
			$this->assertContains('</html>', $client->getResponse()->getContent(), 'Content should contain ending </html>, implying that a full html document was rendered.');
			$this->assertStringStartsWith('application/xhtml+xml', $client->getResponse()->headers->get('Content-Type'), 'Content should be of type \'text/plain\'.');
		}
	}
}
