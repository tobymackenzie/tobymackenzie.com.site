<?php
namespace ProtectedBundle\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller{
	public function testAction($path = null){
		return new Response(htmlspecialchars($path));
	}
	/*
	Action: notFoundAction
	Point user to public page.
	*/
	public function notFoundAction(Request $request, $url = null){
		$url = $this->generateUrl(
			'public_base'
			,['url'=> ltrim($request->getPathInfo(), '/')]
			,UrlGeneratorInterface::ABSOLUTE_URL
		);
		$requestUri = $request->getRequestUri();
		if(strpos($requestUri, '?') !== false){
			$url .= '?' . explode('?', $requestUri, 2)[1];
		}
		preg_match('![\w]+://([\w-\.]+)/?!', $url, $domain);
		$router = $this->get('router');
		$routerContext = $router->getContext();
		$requestHost = $routerContext->getHost();
		$routerContext->setHost($domain[1]);
		try{
			$match = $router->match(rtrim($request->getPathInfo(), '/'));
			if($match['_route'] === 'public_base'){
				$match = null;
			}
		}catch(ResourceNotFoundException $e){}
		//-# just in case
		$routerContext->setHost($requestHost);
		if(!$match){
			throw $this->createNotFoundException();
		}

		return $this->renderPage('ProtectedBundle:default:notFound.' . $request->getRequestFormat() . '.twig', [
			'site'=> ['title'=> '<toby:)>']
			,'url'=> $url
		]);
	}
	//-@ http://symfony.com/doc/current/cookbook/routing/redirect_trailing_slash.html
	public function removeTrailingSlashAction(Request $request){
		$pathInfo = $request->getPathInfo();
		$requestUri = $request->getRequestUri();
		$url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
		return $this->redirect($url, ($this->get('kernel')->getEnvironment() === 'dev') ? 302 : 301);
	}
}
