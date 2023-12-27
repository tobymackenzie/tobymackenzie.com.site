<?php
namespace PublicApp\Controller;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use TJM\SySite\Controller\Controller as Base;
use TJM\Views\Views;

class Controller extends Base{
	protected string $env;
	protected ?RequestStack $requestStack;
	protected RouterInterface $router;
	protected Views $views;
	public function __construct(
		RequestStack $requestStack,
		RouterInterface $router,
		Views $views,
		string $env = 'prod'
	){
		$this->env = $env;
		$this->requestStack = $requestStack;
		$this->router = $router;
		$this->views = $views;
	}
	protected function getGlobalRenderData(array $parameters = Array()){
		$request = $this->requestStack->getMainRequest();
		return $this->views->getDocData($request, $parameters);
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
