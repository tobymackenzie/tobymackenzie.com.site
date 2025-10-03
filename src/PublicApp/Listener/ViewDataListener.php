<?php
namespace PublicApp\Listener;
use PublicApp\Service\Build;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
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
		array $wraps,
		string $host, 
		string $canonicalHost
	){
		$this->canonicalHost = $canonicalHost;
		$this->host = $host;
		$this->requestStack = $requestStack;
		$this->router = $router;
		$this->wraps = $wraps;
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

		$data = $this->getDocData($request, $data);
		$event->setData($data);
	}
	public function getDocData(Request $request = null, array $data = null){
		if(!$data){
			$data = Array();
		}
		if(!isset($data["doc"])){
			$data["doc"] = [];
		}
		if(!isset($data["doc"]['name'])){
			$data["doc"]['name'] = null;
		}
		if(!isset($data["page"])){
			$data["page"] = Array();
		}
		if(!isset($data["site"])){
			$data["site"] = Array();
		}
		if(!isset($data["site"]["isResponsive"])){
			$data["site"]['isResponsive'] = true;
		}
		if(!isset($data["site"]["host"])){
			$data["site"]['host'] = $this->host;
		}
		if(isset($data['format'])){
			$format = $data['format'];
		}elseif($request){
			$format = $request->getRequestFormat();
		}
		if(!isset($data["site"]["title"])){
			$data["site"]['title'] = '<toby';
			if($format === 'xhtml'){
				$data["site"]['title'] .= '/';
			}
			$data["site"]['title'] .= '>';
		}
		if($request && $data["doc"]['name'] !== 'error'){
			$isCanonicalHost = $request->getHost() === $this->host;
			$isHttps = $request->getScheme() === 'https';
			$forceCanonical = Build::isBuilding();
			if(!isset($data['canonical']) && ($format === 'html' || $format === 'xhtml') || $forceCanonical){
				if(!$isHttps || !$isCanonicalHost || $format !== 'html' || $forceCanonical){
					$currentRoute = $request->get('_route');
					if($currentRoute === 'public_home_formatted'){
						$currentRoute = 'public_home';
					}elseif($currentRoute === 'public_page_formatted'){
						$currentRoute = 'public_page';
					}
					$routeParams = $request->get('_route_params');
					unset($routeParams['_format']);
					if($currentRoute === 'public_home'){
						unset($routeParams['id']);
					}
					$data['canonical'] = 'https://' . ($forceCanonical ? $this->canonicalHost : $this->host) . $this->router->generate($currentRoute, $routeParams);
				}
			}
			if(!$isHttps && !isset($data['page']['secureUrl']) && $isCanonicalHost){
				$data['page']['secureUrl'] = 'https://' . $request->getHost() . $request->server->get('REQUEST_URI');
			}
		}
		if(!isset($data["page"]["wrap"])){
			$data["page"]["wrap"] = 'public_full';
		}
		if(!isset($data["page"]["shell"])){
			$data["page"]["shell"] = $this->wraps[$data["page"]["wrap"]] ?? $this->wraps["public_full"];
		}
		//-!! format should come from parameters if set
		$data['page']['shell'] = str_replace('{format}', $format , $data['page']['shell']);
		if(!isset($data['doc'])){
			$data['doc'] = Array();
		}
		if(!isset($data['doc']['attr'])){
			$data['doc']['attr'] = '';
		}elseif($data['doc']['attr']){
			$data['doc']['attr'] .= ' ';
		}
		if(!isset($data['doc']['language'])){
			$data['doc']['language'] = 'en';
		}
		if(strpos($data['doc']['attr'], 'lang=') === false){
			$data['doc']['attr'] .= 'lang="' . $data['doc']['language'] . '"';
		}
		if($format === 'xhtml'){
			if(strpos($data['doc']['attr'], 'xmlns=') === false){
				$data['doc']['attr'] .= ' xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . $data['doc']['language'] . '"';
			}
		}
		return $data;
	}
}
