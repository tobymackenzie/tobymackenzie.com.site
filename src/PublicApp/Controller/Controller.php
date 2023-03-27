<?php
namespace PublicApp\Controller;
use Symfony\Component\HttpFoundation\Response;
use TJM\BaseBundle\Controller\Controller as Base;
use TJM\Views\Views;

class Controller extends Base{
	protected function getGlobalRenderData(array $parameters = Array()){
		$request = $this->get('request_stack')->getMainRequest();
		return $this->get(Views::class)->getDocData($request, $parameters);
	}
	public function renderPage($view, array $parameters = array(), Response $response = null){
		$response = parent::renderPage($view, $parameters, $response);
		return $response;
	}
	public function renderPageView($view, array $parameters = array()){
		$parameters = $this->getGlobalRenderData($parameters);
		return parent::renderView($view, $parameters);
	}
}
