<?php
namespace PublicApp\Controller;
use DateTime;
use ParsedownExtra;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use TJM\HtmlToMarkdown\HtmlConverter;
use TJM\Pages\Pages;
use TJM\WikiSite\WikiSite;

class DefaultController extends Controller{
	public function pageAction(
		$_format = null
		,HtmlConverter $htmlToMarkdown
		,$id
		,ParsedownExtra $markdownToHtml
		,Pages $pagesService
		,Request $request
		,WikiSite $wikiSite
	){
		//-!! temporarily pass through to new action with fall back to old logic until we have redirects implemented properly
		try{
			return $wikiSite->viewAction($_format && $_format !== 'html' ? $id . '.' . $_format : $id);
		}catch(\Exception $e){ }

		//==old logic for redirects
		//-! still have anything else here that is needed?

		//--make sure format is supported
		if(!in_array($_format, static::SUPPORTED_FORMATS)){
			throw $this->createNotFoundException("Format {$_format} not currently supported");
		}
		$lowerCaseId = strtolower($id);
		$page = $pagesService->getResponse($lowerCaseId, $_format);

		if(!$page){
			throw $this->createNotFoundException("No data found for id '{$id}'");
		}

		//--redirect if page type is redirect
		if($page->getType() === 'redirect'){
			return $this->redirect($page->getContent());
		}

		//--make sure our id is lowercase
		if($lowerCaseId !== $id){
			return $this->redirect($this->router->generate($request->get('_route'), ['id'=> $lowerCaseId, '_format'=> $_format]));
		}
		//--strip 'html' format, since that is the default
		if($_format === 'html'){
			$routeName = preg_replace('/_formatted$/', '', $request->get('_route'));
			$routeParams = $request->get('_route_params');
			unset($routeParams['_format']);
			return $this->redirect($this->router->generate($routeName, $routeParams));
		}
		//--if format isn't in url, it is 'html'
		if(!isset($_format)){
			$_format = 'html';
		}

		$fileNamePieces = explode('.', $page->getFileName(), 1);
		$fileExt = $fileNamePieces[2] ?? 'txt';
		switch($fileExt){
			case 'html':
				switch($request->getRequestFormat()){
					case 'md':
					case 'txt':
						$page->setContent($htmlToMarkdown->convert($page->getContent()));
					break;
				}
			break;
			case 'md':
			case 'txt':
				switch($request->getRequestFormat()){
					case 'html':
					case 'xhtml':
						$page->setContent($markdownToHtml->text($page->getContent()));
						//--fix line breaks
						//-# not sure why we have to do this, as they are correct in the content and it doesn't look like parsedown should mangle them
						$page->setContent(str_ireplace('<br>', '<br />', $page->getContent()));
					break;
					case 'txt':
						$page->setContent($htmlToMarkdown->convertInMarkdown($page->getContent()));
					break;
				}
			break;
		}
		$data = [
			'content'=> $page->getContent()
			,'request'=> $request
		];

		//--set title for pages with names besides home page
		$name = $page->hasTitle() ? $page->getTitle() : null;
		if(!$name){
			$name = ucwords(
				str_replace('/', ': ',
					str_replace('-', ' ', $id)
				)
			);
		}
		$data['doc'] = [
			'name'=> preg_replace('/[^\w\-]/', '', str_replace(' ', '-', strtolower($name)))
		];
		//--special treatment for home page
		if($id === 'index'){
			$data['site'] = Array(
				'title'=> "<toby"
			);
			if($request->getRequestFormat() === 'xhtml'){
				$data['site']['title'] .= '/';
			}
			$data['site']['title'] .= "> Mackenzie's site";
		}else{
			$data['doc']['title'] = $name;
		}

		$data['heading'] = $name;

		//--set up formats list
		$possibleFormats = [
			[
				'name'=> 'html'
				,'type'=> 'text/html'
			]
			,[
				'name'=> 'xhtml'
				,'type'=> 'application/xhtml+xml'
			]
			,[
				'name'=> 'txt'
				,'type'=> 'text/plain'
			]
		];
		$data['formats'] = [];
		$routeName = $request->get('_route');
		$htmlRouteName = preg_replace('/_formatted$/', '', $routeName);
		if($htmlRouteName === $routeName){
			$formattedRouteName = $routeName . '_formatted';
		}else{
			$formattedRouteName = $routeName;
		}
		foreach($possibleFormats as $formatData){
			if($request->getRequestFormat() !== $formatData['name']){
				$isHtml = ($formatData['name'] === 'html');
				$routeParams = ['id'=> $id];
				if(!$isHtml){
					$routeParams['_format'] = $formatData['name'];
				}
				$formatData['path'] = $this->router->generate(
					($isHtml ? $htmlRouteName : $formattedRouteName)
					,$routeParams
				);
				$data['formats'][] = $formatData;
			}
		}
		$response = $this->renderPage('@Public/default/simplePage.' . $request->getRequestFormat() . '.twig', $data);

		//--set response headers
		$response->setPublic();
		$response->setMaxAge(600);
		$response->setSharedMaxAge(600);
		// $response->headers->set('X-Reverse-Proxy-TTL', 3600000);

		return $response;
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
			$match = $this->router->match(str_replace('/app_dev.php', '', $url));
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
