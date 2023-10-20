<?php
namespace PublicApp\Listener;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use TJM\Views\Views;
use TJM\WikiSite\Event\ViewDataEvent;

class ViewDataListener{
	static protected array $formats = [
		[
			'name'=> 'html',
			'type'=> 'text/html',
		],
		[
			'name'=> 'xhtml',
			'type'=> 'application/xhtml+xml',
		],
		[
			'name'=> 'txt',
			'type'=> 'text/plain',
		],
	];
	protected RequestStack $requestStack;
	protected Views $viewService;
	public function __construct(
		RequestStack $requestStack,
		RouterInterface $router,
		Views $viewService
	){
		$this->requestStack = $requestStack;
		$this->router = $router;
		$this->viewService = $viewService;
	}
	public function __invoke(ViewDataEvent $event){
		$data = $event->getData();
		$request = $data['request'] ?? $this->requestStack->getCurrentRequest();
		if(empty($data['doc'])){
			$data['doc'] = [];
		}
		$format = $data['format'] ?? $request->getRequestFormat();
		$isHtmlish = $format === 'html' || $format === 'xhtml';
		if($isHtmlish && !empty(preg_match(':<h1.*>(.*)</h1>:i', $data['content'], $matches))){
			$data['doc']['title'] = $matches[1];
		}
		if(empty($data['doc']['title'])){
			$data['doc']['title'] = $data['name'];
		}
		$isHome = !empty($data['pagePath']) && preg_match(':^/?index(\.[\w]+)?:', $data['pagePath']);
		if(empty($data['doc']['name'])){
			if($isHome){
				$data['doc']['name'] = 'home';
			}else{
				$data['doc']['name'] = strtolower(str_replace(' ', '-', $data['doc']['title']));
			}
		}
		if($isHome){
			$data['name'] = $data['doc']['title'] = 'Home';
			$data['content'] = preg_replace(':<h1.*/h1>:i', '<h1>Home</h1>', $data['content']);
		}
		if(empty($data['formats'])){
			$data['formats'] = [];
			$routeName = $request->get('_route');
			$htmlRouteName = preg_replace('/_formatted$/', '', $routeName);
			if($htmlRouteName === $routeName){
				$formattedRouteName = $routeName . '_formatted';
			}else{
				$formattedRouteName = $routeName;
			}
			foreach(static::$formats as $aFormat){
				if($aFormat['name'] !== $format){
					$routeParams = [];
					if($data['pagePath'] !== 'index'){
						$routeParams['id'] = $data['pagePath'];
					}
					if($aFormat['name'] !== 'html'){
						$routeParams['_format'] = $aFormat['name'];
					}
					$aFormat['path'] =  $this->router->generate(
						($aFormat['name'] === 'html' ? $htmlRouteName : $formattedRouteName)
						,$routeParams
					);
					$data['formats'][] = $aFormat;
				}
			}
		}

		$data = $this->viewService->getDocData($request, $data);
		$event->setData($data);
	}
}
