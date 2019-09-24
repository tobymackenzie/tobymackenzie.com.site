<?php
namespace TJM\Views;
use Symfony\Component\HttpFoundation\Request;

class Views{
	protected $host;
	protected $router;
	protected $wraps;
	public function __construct($router, array $wraps, string $host){
		$this->host = $host;
		$this->router = $router;
		$this->wraps = $wraps;
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
			if(!isset($data['canonical']) && ($format === 'html' || $format === 'xhtml')){
				if(!$isHttps || !$isCanonicalHost || $format !== 'html'){
					$currentRoute = $request->get('_route');
					if($currentRoute === 'public_home_formatted'){
						$currentRoute = 'public_home';
					}elseif($currentRoute === 'public_page_formatted'){
						$currentRoute = 'public_page';
					}
					$routeParams = $request->get('_route_params');
					unset($routeParams['_format']);
					$data['canonical'] = 'https://' . $this->host . $this->router->generate($currentRoute, $routeParams);
				}
			}
			if(!$isCanonicalHost && filter_var($request->getHost(), FILTER_VALIDATE_IP)){
				$data['site']['forceHost'] = $request->getScheme() . '://' . $this->host;
			}
			if(!$isHttps && !isset($data['page']['secureUrl'])){
				$data['page']['secureUrl'] = 'https://' . $request->getHost() . $request->server->get('REQUEST_URI');
			}
		}
		if(!isset($data["page"]["wrap"])){
			$data["page"]["wrap"] = 'public_full';
		}
		if(!isset($data["page"]["skeleton"])){
			$data["page"]["skeleton"] = $this->wraps[$data["page"]["wrap"]] ?? $this->wraps["public_full"];
		}
		//-!! format should come from parameters if set
		$data['page']['skeleton'] = str_replace('{format}', $format , $data['page']['skeleton']);
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
