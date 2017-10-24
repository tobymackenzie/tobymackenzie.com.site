<?php
namespace PublicBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use TJM\Bundle\BaseBundle\Controller\Controller as Base;

class Controller extends Base{
	protected function getGlobalRenderData(array $parameters = Array()){
		if(!isset($parameters["doc"])){
			$parameters["doc"] = [];
		}
		if(!isset($parameters["doc"]['name'])){
			$parameters["doc"]['name'] = null;
		}
		if(!array_key_exists("page", $parameters)){
			$parameters["page"] = Array();
		}
		if(!array_key_exists("wrap", $parameters["page"])){
			$parameters["page"]['wrap'] = 'public_full';
		}
		if(!array_key_exists("site", $parameters)){
			$parameters["site"] = Array();
		}
		if(!array_key_exists("isResponsive", $parameters["site"])){
			$parameters["site"]['isResponsive'] = true;
		}
		$request = $this->get('request_stack')->getMasterRequest();
		if(isset($parameters['format'])){
			$format = $parameters['format'];
		}elseif($request){
			$format = $request->getRequestFormat();
		}
		if(!array_key_exists("title", $parameters["site"])){
			$parameters["site"]['title'] = '<toby';
			if($format === 'xhtml'){
				$parameters["site"]['title'] .= '/';
			}
			$parameters["site"]['title'] .= '>';
		}
		if($request){
			if(!isset($parameters['canonical']) && ($format === 'html' || $format === 'xhtml')){
				if($request->getScheme() !== 'https' || $request->getHost() !== 'www.tobymackenzie.com' || $format !== 'html'){
					$currentRoute = $request->get('_route');
					if($currentRoute === 'public_home_formatted'){
						$currentRoute = 'public_home';
					}
					$routeParams = $request->get('_route_params');
					unset($routeParams['_format']);
					$parameters['canonical'] = 'https://www.tobymackenzie.com' . $this->get('router')->generate($currentRoute, $routeParams);
				}
			}
			if($request->getScheme() !== 'https' && !isset($parameters['page']['secureUrl'])){
				$parameters['page']['secureUrl'] = 'https://' . $request->getHost() . $request->server->get('REQUEST_URI');
			}
		}
		return parent::getGlobalRenderData($parameters);
	}
	public function renderPage($view, array $parameters = array(), Response $response = null){
		$parameters = $this->getGlobalRenderData($parameters);
		$response = parent::renderPage($view, $parameters, $response);
		return $response;
	}
	public function renderPageView($view, array $parameters = array()){
		$parameters = $this->getGlobalRenderData($parameters);
		return parent::renderView($view, $parameters);
	}
}
