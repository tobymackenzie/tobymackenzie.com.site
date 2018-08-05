<?php
namespace PublicBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use TJM\Bundle\BaseBundle\Controller\Controller as Base;

class Controller extends Base{
	protected function getGlobalRenderData(array $parameters = Array()){
		$request = $this->get('request_stack')->getMasterRequest();
		return $this->get('TJM\Views\Views')->getDocData($request, $parameters);
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
