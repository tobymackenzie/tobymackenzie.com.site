<?php
namespace PublicBundle\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class DefaultController extends Controller{
	public function simpleFilePageAction(Request $request, $id){
		$basePath = __DIR__ . '/../../../app/data/data-store/files/' . $id;
		if(!file_exists($basePath . '/data.json')){
			throw $this->createNotFoundException();
		}
		$fileData = json_decode(file_get_contents($basePath . '/data.json'), true);
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
				'name'=> strtolower($name)
			];
			//--special treatment for home page
			if($id === 4){
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
			'html'
			,'xhtml'
			,'txt'
		];
		$data['formats'] = [];
		$routeName = $request->get('_route');
		if($routeName === 'public_home'){
			$routeName = 'public_home_formatted';
		}
		foreach($possibleFormats as $format){
			if($request->getRequestFormat() !== $format){
				$data['formats'][$format] = $this->get('router')->generate($routeName, ['_format'=> $format]);
			}
		}

		$response = $this->renderPage('PublicBundle:default:simplePage.' . $request->getRequestFormat() . '.twig', $data);

		//--set response headers
		$response->setPublic();
		$response->setMaxAge(600);
		$response->setSharedMaxAge(600);
		$response->headers->set('X-Reverse-Proxy-TTL', 3600000);

		return $response;
	}
	//-@ http://symfony.com/doc/current/cookbook/routing/redirect_trailing_slash.html
	public function removeTrailingSlashAction(Request $request){
		$pathInfo = $request->getPathInfo();
		$requestUri = $request->getRequestUri();
		$url = str_replace($pathInfo, rtrim($pathInfo, ' /'), $requestUri);
		try{
			$match = $this->get('router')->match(str_replace('/app_dev.php', '', $url));
		}catch(ResourceNotFoundException $e){
			$match = null;
		}
		if(!$match || $match['_route'] === 'public_base'){
			throw $this->createNotFoundException();
		}else{
			return $this->redirect($url, ($this->get('kernel')->getEnvironment() === 'dev') ? 302 : 301);
		}
	}
}
