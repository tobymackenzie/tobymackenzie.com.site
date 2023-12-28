<?php
namespace PublicApp\Controller;
use ParsedownExtra;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MetaController extends Controller{
	public function appManifestAction(Request $request){
		//-@ https://developer.mozilla.org/en-US/docs/Web/Manifest
		//-@ http://html5doctor.com/web-manifest-specification/
		$data = [
			'background_color'=> '#4e784e'
			,'display'=> 'browser'
			,'icons'=> [
				[
					'sizes'=> '64x64'
					,'src'=> '/icon-64.gif'
					,'type'=> 'image/gif'
				]
				//--min size for installable mobile apps
				,[
					'sizes'=> '144x144'
					,'src'=> '/icon-144.png'
					,'type'=> 'image/png'
				]
			]
			,'lang'=> 'en-US'
			,'name'=> 'Toby Mackenzie\'s site'
			,'scope'=> '/'
			,'short_name'=> '<toby>'
			,'start_url'=> '/'
			,'theme_color'=> '#4e784e'
		];
		$response = new JsonResponse($data);
		$response->setPublic();
		$response->setMaxAge(86400);
		return $response;
	}
	//-@ https://symfony.com/doc/current/controller/error_pages.html#custom-exception-controller
	public function exceptionAction(Request $request, FlattenException $exception){
		$code = $exception->getStatusCode();
		if($code === 404){
			//--if the path contains uppercase letters, check if we have this route in all lowercase
			//-! for public_page*, case redirect must be handled in its action, to make sure we have item to redirect to
			$pathInfo = $request->getPathInfo();
			if(preg_match('/[A-Z]/', $pathInfo)){
				try{
					$match = $this->router->match(strtolower($pathInfo));
					if(!in_array($match['_route'], [
						'public_page'
						,'public_page_formatted'
						,'public_base'
						,'protected_not_found'
					])){ //-# need protected fallback here because this controller handles all app errors
						return $this->redirect(strtolower($request->getRequestUri()));
					}
				}catch(ResourceNotFoundException $e){
					//--go on
				}
			}
		}
		$statusText =  (isset(Response::$statusTexts[$code]) ? Response::$statusTexts[$code] : '');
		$data = [
			'doc'=> [
				'name'=> 'error'
				,'title'=> 'Error: ' . $statusText
			]
			,'status_code'=> $code
			,'status_text'=> $statusText
		];
		if($code == 404){
			//--build search from path
			$search = urldecode($request->getPathInfo());
			foreach([
				//---strip file extension, if any
				'/^\/?\./'=> ''
				,'/(\.[\w]+)$/'=> ''
				//---strip non-useful or dangerous characters
				,'/[^a-zA-Z0-9]+/'=> ' '
			] as $regEx=> $replace){
				$search = preg_replace($regEx, $replace, $search);
			}
			$data['search'] = trim($search);
		}
		$format = $request->getRequestFormat();
		if(!in_array($format, ['html', 'xhtml'])){
			$format = 'html';
			$request->setRequestFormat($format);
			$data['format'] = $format;
		}
		$response = $this->renderPage('@Public/meta/error.' . $format . '.twig', $data);
		$response->setStatusCode($code);
		return $response;
	}
	public function humansAction(
		ParsedownExtra $markdownToHtml
		,Request $request
		,$_format = 'html'
	){
		$data = [
			'sections'=> [
				'Human / Webmaster'=> [
					'Name'=> 'Toby Mackenzie'
					,'Location'=> 'Akron, Ohio'
				]
				,'Site'=> [
					'URL'=> 'https://www.tobymackenzie.com'
					,'Language'=> 'English'
					,'Host'=> 'Dreamhost Dreamcompute VPS'
					,'Server'=> [
						'OS'=> 'Ubuntu'
						,'HTTP'=> 'Apache 2.4'
						,'Data'=> 'SQLite, File system, MySQL'
						,'Code'=> 'PHP'
						,'Frameworks / Software'=> 'Symfony 5, Parsedown Extra, WordPress'
						,'Provisioning'=> 'Ansible'
					]
					,'Development'=> [
						'Environment'=> 'Mac OS X + homebrew, Vagrant + VirtualBox'
						,'Code'=> 'Atom / vim'
						,'Images'=> 'Adobe Photoshop'
						,'Build'=> 'SASS, Rollup.js, Uglify'
						,'Deployment'=> 'rsync via PHP script'
					]
					,'Code'=> [
						'Server'=> 'https://github.com/tobymackenzie/tobymackenzie.srv',
						'Site'=> 'https://github.com/tobymackenzie/tobymackenzie.site',
					]
				]
			]

			//==separators
			,'postSeparator'=> ($request->getRequestFormat() === 'txt'
				? ''
				: "\n"
			)
			,'separator'=> ($request->getRequestFormat() === 'txt'
				? ': '
				: "\n: "
			)
			//-# this would be 'subSeparator' if nested <dl> worked
			// ,'subSeparator'=> ($request->getRequestFormat() === 'txt'
			// 	? ': '
			// 	: "\n\t: "
			// )
			,'subSeparator'=> ': '
		];
		if(in_array($request->getRequestFormat(), ['html', 'xhtml'])){
			//--force empty shell so we get bare markdown content
			$data['page'] = [
				'shell'=> '@Public/shells/empty.md.twig'
			];
			//--render markdown template
			$content = $this->renderPageView('@Public/meta/humans.md.twig', $data);
			//--make sublabels into <strong> since nested <dl> doesn't seem to work
			$content = preg_replace('/\t([\w\s\-\.\(\)\[\]]+):/', "\t**$1**:", $content);
			//--parse markdown
			$content = $markdownToHtml->text($content);
			//--pass new data to simplePage
			$data = [
				'content'=> $content
				,'doc'=> ['title'=> 'Humans']
				,'request'=> $request
			];
			$response = $this->renderPage('@Public/default/simplePage.' . $request->getRequestFormat() . '.twig', $data);
		}else{
			$response = $this->renderPage('@Public/meta/humans.' . $request->getRequestFormat() . '.twig', $data);
		}
		$response->setPublic();
		$response->setMaxAge(86400);
		// $response->headers->set('X-Reverse-Proxy-TTL', 3600000);
		return $response;
	}
	public function proxyServiceWorkerAction(){
		$response = new Response(file_get_contents(__DIR__ . '/../Resources/scripts/proxy-worker.js'));
		$response->headers->set('Content-Type', 'application/javascript');
		$response->setMaxAge(86400);
		return $response;
	}

	//-@ www.robotstxt.org/
	//-@ www.google.com/support/webmasters/bin/answer.py?hl=en&answer=156449
	public function robotsAction(Request $request, $_format = 'html'){
		$data = [];
		//--only allow for canonical
		if(preg_match("/^{$this->getParameter('public.host')}$/", $request->getHttpHost())){
			$data['agents'] = [
				'*'=> [
					'Crawl-delay'=> 10
				]
			];
			$data['other'] = [
				'Sitemap'=> 'https://www.tobymackenzie.com/blog/sitemap.xml'
			];
		//--disallow for all others
		}else{
			$data['agents'] = [
				'*'=> [
					'Disallow'=> '/'
				]
			];
		}
		if($_format === 'html' || $_format === 'xhtml'){
			$data['doc'] = ['title'=> 'Robots'];
		}
		$response = $this->renderPage('@Public/meta/robots.' . $request->getRequestFormat() . '.twig', $data);
		$response->setPublic();
		$response->setMaxAge(86400);
		// $response->headers->set('X-Reverse-Proxy-TTL', 3600000);
		return $response;
	}
	public function siteNavAction(
		Request $request
		, $_format = null
	){
		if(!in_array($_format, DefaultController::SUPPORTED_FORMATS)){
			throw $this->createNotFoundException("Format {$_format} not currently supported");
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
			$_format = $templateFormat = 'html';
		}elseif($_format === 'xhtml'){
			$templateFormat = 'html';
		}elseif($_format === 'md'){
			$templateFormat = 'txt';
		}else{
			$templateFormat = $_format;
		}
		$routeFormat = ($_format === 'html' ? null : $_format);
		$routeReferenceType = ($templateFormat === 'txt' ? UrlGeneratorInterface::ABSOLUTE_URL : UrlGeneratorInterface::ABSOLUTE_PATH);

		$data = [
			'items'=> [
				[
					'items'=> [
						[
							'label'=> 'WWW'
							,'url'=> '/blog/category/www/'
						]
						,[
							'label'=> 'Personal'
							,'url'=> '/blog/category/toby/'
						]
						,[
							'label'=> 'Computers & Tech'
							,'url'=> '/blog/category/computer/'
						]
						,[
							'label'=> 'Thoughts & Ideas'
							,'url'=> '/blog/category/ideas/'
						]
						,[
							'label'=> 'Et Cetera'
							,'url'=> '/blog/category/et-cetera/'
						]
						,[
							'label'=> 'Search blog'
							,'name'=> 's'
							,'template'=> 'search'
							,'url'=> '/blog/'
						]
					]
					,'label'=> 'Blog'
					,'type'=> 'blog'
					,'url'=> '/blog/'
				]
				,[
					'label'=> 'About'
					,'type'=> 'about'
					,'url'=> $this->router->generate($routeFormat ? 'public_page_formatted' : 'public_page', ['_format'=> $routeFormat, 'id'=> 'about'], $routeReferenceType)
				]
				,[
					'label'=> 'Home'
					,'type'=> 'home'
					,'url'=> $this->router->generate($routeFormat ? 'public_home_formatted' : 'public_home', ['_format'=> $routeFormat], $routeReferenceType)
				]
			]
		];
		$response = $this->renderPage('@Public/meta/site-nav.' . $templateFormat . '.twig', $data);
		$response->setPublic();
		$response->setMaxAge(21600); //-# 6 hours
		return $response;
	}
	public function showExceptionAction($code){
		throw new HttpException($code);
	}

	/*=====
	==seo
	=====*/
	public function bingVerificationAction(){
		$response = $this->render('@Public/meta/bing-verification.xml.twig', Array('code'=> $this->getParameter('bing-verification')));
		$response->headers->set('Content-Type', 'application/xml'); //-! symfony's supposed to be determining this based on the template, but isn't, so we must be explicit
		$response->setMaxAge(86400); //-# 24 hours
		return $response;
	}
	public function googleVerificationAction(){
		$response = $this->render('@Public/meta/google-verification.html.twig', Array('code'=> $this->getParameter('google-verification')));
		$response->setMaxAge(86400); //-# 24 hours
		return $response;
	}
}
