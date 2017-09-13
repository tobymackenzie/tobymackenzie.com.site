<?php
namespace ProtectedBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use TJM\Bundle\BaseBundle\Controller\Controller as Base;

class Controller extends Base{
	public function renderPage($view, array $parameters = array(), Response $response = null){
		if(!array_key_exists("page", $parameters)){
			$parameters["page"] = Array();
		}
		if(!array_key_exists("wrap", $parameters["page"])){
			$parameters["page"]['wrap'] = 'protected_full';
		}
		if(!array_key_exists("site", $parameters)){
			$parameters["site"] = Array();
		}
		if(!array_key_exists("isResponsive", $parameters["site"])){
			$parameters["site"]['isResponsive'] = true;
		}
		$response = parent::renderPage($view, $parameters, $response);
		return $response;
	}
}
