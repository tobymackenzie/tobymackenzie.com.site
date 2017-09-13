<?php
namespace TJM\TMCom\Listener;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

//-@ https://securityheaders.io/?q=http%3A%2F%2Ftobymackenzie.com

class HeadersListener{
	protected $env;
	public function __construct($env){
		$this->env = $env;
	}
	//-@ http://stackoverflow.com/a/21720357/1139122
	//-@ http://php-and-symfony.matthiasnoback.nl/2011/10/symfony2-create-a-response-filter-and-set-extra-response-headers/
	public function onKernelResponse(FilterResponseEvent $event){
		$request = $event->getRequest();
		$headers = $event->getResponse()->headers;
		if($this->env === 'dev'){
			$headers->set('X-Symfony-Route', $request->get('_route'));
		}

		// if($request->getScheme() !== 'https' && ($request->getHost() === 'tobymackenzie.com' || $request->getHost() === 'www.tobymackenzie.com')){
		// 	//--tell browser it should use https if possible
		// 	if(!$headers->has('Upgrade-Insecure-Requests')){
		// 		$headers->set('Upgrade-Insecure-Requests', '1');
		// 	}
		// }

		//--tell browser to keep using https for all requests to the domain
		if($request->getScheme() === 'https'){
			if(!$headers->has('Strict-Transport-Security')){
				$headers->set('Strict-Transport-Security', 'max-age=2592000');
			}
		}

		if($request->getRequestFormat() === 'html' || $request->getRequestFormat() === 'xhtml'){
			//--tell browser where safe scripts can be loaded from
			//-# consider a nonce or hash
			//-@ https://scotthelme.co.uk/content-security-policy-an-introduction/
			$cspHeader = ($this->env === 'dev') ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
			if(!$headers->has($cspHeader)){
				$headers->set($cspHeader, "default-src 'unsafe-inline' {$request->server->get('HTTP_HOST')};block-all-mixed-content");
			}

			//--block iframes from showing site, except from our domain.  helps prevent clickjacking
			//-@ https://scotthelme.co.uk/hardening-your-http-response-headers/#x-frame-options
			if(!$headers->has('X-Frame-Options')){
				$headers->set('X-Frame-Options', 'sameorigin');
			}

			//--force enable xss protection and block rendering of page if attack detected.  IE and Chrome.  may not be necessary, since the default behavior should be satisfactory
			//-@ https://scotthelme.co.uk/hardening-your-http-response-headers/#x-xss-protection
			if(!$headers->has('X-Xss-Protection')){
				$headers->set('X-Xss-Protection', '1; mode=block');
			}
		}
	}
}
