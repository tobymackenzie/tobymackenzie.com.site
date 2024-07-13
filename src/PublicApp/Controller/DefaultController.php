<?php
namespace PublicApp\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use TJM\Pages\Pages;
use TJM\WikiSite\WikiSite;

class DefaultController extends Controller{
	public function pageAction(
		$_format = null
		,$id
		,Pages $pagesService
		,Request $request
		,WikiSite $wikiSite
	){
		//-!! temporarily pass through to new action with fall back to old logic until we have redirects implemented properly
		try{
			return $wikiSite->viewAction($_format && $_format !== 'html' ? $id . '.' . $_format : $id);
		}catch(\Exception $e){ }
		//==old logic for redirects
		$page = $pagesService->getResponse(strtolower($id), $_format);
		if($page && $page->getType() === 'redirect'){
			return $this->redirect($page->getContent());
		}
		throw $this->createNotFoundException();
	}
	//-@ http://symfony.com/doc/current/cookbook/routing/redirect_trailing_slash.html
	public function removeTrailingSlashAction(
		Request $request
		,Pages $pagesService
	){
		$pathInfo = $request->getPathInfo();
		$requestUri = $request->getRequestUri();
		$url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
		try{
			$match = $this->router->match(str_replace('/index-dev.php', '', $url));
		}catch(ResourceNotFoundException $e){
			$match = null;
		}
		//--make sure we have a real matching route to redirect to
		if(!$match || $match['_route'] === 'public_base'){
			throw $this->createNotFoundException();
		}elseif($match['_route'] === 'public_page' || $match['_route'] === 'public_page_formatted'){
			if(isset($match['_format']) && !in_array($match['_format'], static::SUPPORTED_FORMATS)){
				throw $this->createNotFoundException("Format {$match['_format']} not currently supported");
			}
			if(!($pagesService->hasResponse($match['id']))){
				throw $this->createNotFoundException("No data found for id '{$match['id']}'");

			}
		}
		return $this->redirect($url, ($this->env === 'dev') ? 302 : 301);
	}

	/*=====
	==page helpers
	=====*/
	const SUPPORTED_FORMATS = [null, 'html', 'md', 'txt', 'xhtml'];
}
