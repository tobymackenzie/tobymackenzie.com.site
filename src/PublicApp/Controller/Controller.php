<?php
namespace PublicApp\Controller;
use PublicApp\Listener\ViewDataListener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use TJM\SySite\Controller\Controller as Base;

class Controller extends Base{
	protected string $env;
	protected ?RequestStack $requestStack;
	protected RouterInterface $router;
	protected ViewDataListener $views;
	public function __construct(
		RequestStack $requestStack,
		RouterInterface $router,
		ViewDataListener $views,
		string $env = 'prod'
	){
		$this->env = $env;
		$this->requestStack = $requestStack;
		$this->router = $router;
		$this->views = $views;
	}
	protected function getGlobalRenderData(array $parameters = []){
		$request = $this->requestStack->getMainRequest();
		return $this->views->getDocData($request, $parameters);
	}
	public function renderPage($view, array $parameters = [], Response $response = null){
		$response = parent::renderPage($view, $parameters, $response);
		return $response;
	}
	public function renderPageView($view, array $parameters = []){
		$parameters = $this->getGlobalRenderData($parameters);
		return parent::renderView($view, $parameters);
	}
}
