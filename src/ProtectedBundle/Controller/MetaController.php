<?php
namespace ProtectedBundle\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MetaController extends Controller{
	public function robotsAction(Request $request, $_format = 'html'){
		$data = [];
		$data['agents'] = [
			'*'=> [
				'Disallow'=> '/'
			]
		];
		if($_format === 'html' || $_format === 'xhtml'){
			$data['doc'] = ['title'=> 'Robots'];
		}
		$response = $this->renderPage('@Public/meta/robots.' . $request->getRequestFormat() . '.twig', $data);
		$response->setPublic();
		$response->setMaxAge(86400);
		// $response->headers->set('X-Reverse-Proxy-TTL', 3600000);
		return $response;
	}
}
