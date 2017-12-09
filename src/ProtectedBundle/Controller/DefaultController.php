<?php
namespace ProtectedBundle\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller{
	public function testAction($path = null){
		return new Response($path);
	}
	/*
	Action: notFoundAction
	Point user to public page.
	*/
	public function notFoundAction(Request $request, $url = null){
		$pathInfo = $request->getPathInfo();
		if($pathInfo{0} === '/'){
			$pathInfo = substr($pathInfo, 1);
		}
		$url = $this->generateUrl(
			'public_base'
			,['url'=> $pathInfo]
			,UrlGeneratorInterface::ABSOLUTE_URL
		);
		$qs = $request->server->get('QUERY_STRING');
		if($qs){
			$url .= '?' . $qs;
		}
		if(strpos($url, '/app.php') !== false){
			$url = str_replace('/app.php', '/', $url);
		}elseif(strpos($url, '/app_dev.php') !== false){
			$url = str_replace('/app_dev.php', '', $url);
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
