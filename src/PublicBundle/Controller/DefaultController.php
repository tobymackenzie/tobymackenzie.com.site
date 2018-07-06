<?php
namespace PublicBundle\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\RouterInterface;
use TJM\Pages\Pages;

class DefaultController extends Controller{
	public function pageAction(
		Request $request
		,$id
		,Pages $pagesService
		,RouterInterface $router
		,$_format = null
	){
		//--make sure format is supported
		if(!in_array($_format, static::SUPPORTED_FORMATS)){
			throw $this->createNotFoundException("Format {$_format} not currently supported");
		}
		$lowerCaseId = strtolower($id);
		$basePath = $pagesService->getPagePath($lowerCaseId);
		$fileData = $pagesService->getPageDataPath($lowerCaseId);
		if(!file_exists($fileData)){
			throw $this->createNotFoundException("No data found for id '{$id}'");
		}
		//--make sure our id is lowercase
		if($lowerCaseId !== $id){
			return $this->redirect($lowerCaseId);
		}
		//--strip 'html' format, since that is the default
		if($_format === 'html'){
			$routeName = preg_replace('/_formatted$/', '', $request->get('_route'));
			$routeParams = $request->get('_route_params');
			unset($routeParams['_format']);
			return $this->redirect($router->generate($routeName, $routeParams));
		}
		//--if format isn't in url, it is 'html'
		if(!isset($_format)){
			$_format = 'html';
		}
		$fileData = json_decode(file_get_contents($fileData), true);
		$content = file_get_contents($basePath . '/' . ($fileData['fileName'] ?? $id . '.txt'));
		$fileNamePieces = explode('.', $fileData['fileName'], 1);
		$fileExt = $fileNamePieces[2] ?? 'txt';
		switch($fileExt){
			case 'html':
				switch($request->getRequestFormat()){
					case 'md':
					case 'txt':
						$content = $this->get('htmlToMarkdown')->convert($content);
					break;
				}
			break;
			case 'md':
			case 'txt':
				switch($request->getRequestFormat()){
					case 'html':
					case 'xhtml':
						$content = $this->get('markdownToHtml')->text($content);
						//--fix line breaks
						//-# not sure why we have to do this, as they are correct in the content and it doesn't look like parsedown should mangle them
						$content = str_ireplace('<br>', '<br />', $content);
					break;
					case 'txt':
						$content = $this->get('htmlToMarkdown')->convertInMarkdown($content);
					break;
				}
			break;
		}
		$data = [
			'content'=> $content
			,'request'=> $request
		];

		//--set title for pages with names besides home page
		$name = $fileData['title'] ?? null;
		if($name){
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
				$data['doc']['title'] = ucfirst($name);
			}

			$data['heading'] = ucfirst($name);
		}

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
				$formatData['path'] = $router->generate(
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
		,RouterInterface $router
	){
		$pathInfo = $request->getPathInfo();
		$requestUri = $request->getRequestUri();
		$url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
		try{
			$match = $router->match(str_replace('/app_dev.php', '', $url));
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
			if(!file_exists($pagesService->getPageDataPath($match['id']))){
				throw $this->createNotFoundException("No data found for id '{$match['id']}'");

			}
		}
		return $this->redirect($url, ($this->get('kernel')->getEnvironment() === 'dev') ? 302 : 301);
	}

	/*=====
	==page helpers
	=====*/
	const SUPPORTED_FORMATS = [null, 'html', 'md', 'txt', 'xhtml'];
}
