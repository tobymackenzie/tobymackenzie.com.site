<?php
namespace PublicBundle\Controller;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
					,'src'=> 'icon-64.gif'
					,'type'=> 'image/gif'
				]
				//--min size for installable mobile apps
				,[
					'sizes'=> '144x144'
					,'src'=> 'icon-144.png'
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
	public function exceptionAction(Request $request, FlattenException $exception){
		$code = $exception->getStatusCode();
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
			$data['search'] = $request->getPathInfo();
			foreach([
				'/\.[\w]+$/'=> ''
				,'/[<>]+/'=> ''
				,'/[^a-zA-Z0-9]+/'=> ' '
			] as $regEx=> $replace){
				$data['search'] = preg_replace($regEx, $replace, $data['search']);
			}
			$data['search'] = trim($data['search']);
		}
		$format = $request->getRequestFormat();
		if(!in_array($format, ['html', 'xhtml'])){
			$format = 'html';
			$data['format'] = $format;
		}
		$response = $this->renderPage('@Public/meta/error.' . $format . '.twig', $data);
		$response->setStatusCode($code);
		return $response;
	}
	public function humansAction(Request $request, $_format = 'html'){
		$data = [
			'sections'=> [
				'Human / Webmaster'=> [
					'Name'=> 'Toby Mackenzie'
					,'Location'=> 'Cleveland, Ohio'
				]
				,'Site'=> [
					'URL'=> 'https://www.tobymackenzie.com'
					,'Language'=> 'English'
					,'Host'=> 'Dreamhost Dreamcompute VPS'
					,'Server'=> [
						'OS'=> 'Ubuntu'
						,'HTTP'=> 'Apache 2'
						,'Data'=> 'SQLite, File system'
						,'Code'=> 'PHP'
						,'Frameworks'=> 'Symfony 3, Parsedown Extra'
					]
					,'Development'=> [
						'Environment'=> 'Mac OS X.11 + MacPorts'
						,'Code'=> 'Atom / Sublime Text'
						,'Images'=> 'Adobe Photoshop'
						,'Deployment'=> 'Ansible with rsync'
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
			//--force empty skeleton so we get bare markdown content
			$data['page'] = [
				'skeleton'=> 'PublicBundle:skeletons:empty.md.twig'
			];
			//--render markdown template
			$content = $this->renderPageView('PublicBundle:meta:humans.md.twig', $data);
			//--make sublabels into <strong> since nested <dl> doesn't seem to work
			$content = preg_replace('/\t([\w\s-\.\(\)\[\]]+):/', "\t**$1**:", $content);
			//--parse markdown
			$content = $this->get('markdownToHtml')->text($content);
			//--pass new data to simplePage
			$data = [
				'content'=> $content
				,'doc'=> ['title'=> 'Humans']
				,'request'=> $request
			];
			$response = $this->renderPage('PublicBundle:default:simplePage.' . $request->getRequestFormat() . '.twig', $data);
		}else{
			$response = $this->renderPage('PublicBundle:meta:humans.' . $request->getRequestFormat() . '.twig', $data);
		}
		$response->setPublic();
		$response->setMaxAge(86400);
		$response->headers->set('X-Reverse-Proxy-TTL', 3600000);
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
		//--force https to ensure crawlers are made aware of https version
		if($_format === 'txt' && $request->getScheme() !== 'https'){
		return $this->redirect(preg_replace('/^http:/', 'https:', $this->get('router')->generate('public_robots', Array('_format'=> $_format), UrlGeneratorInterface::ABSOLUTE_URL)), ($this->get('kernel')->getEnvironment() === 'dev' ? 302 : 301));
		}else
		//--only allow for canonical
		if($request->getScheme() === 'https' && preg_match("/^{$this->container->getParameter('host.prefix')}{$this->container->getParameter('public.host.subdomain')}{$this->container->getParameter('public.host')}$/", $request->getHttpHost())){
			$data['agents'] = [
				'*'=> []
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
		$response = $this->renderPage('PublicBundle:meta:robots.' . $request->getRequestFormat() . '.twig', $data);
		$response->setPublic();
		$response->setMaxAge(86400);
		$response->headers->set('X-Reverse-Proxy-TTL', 3600000);
		return $response;
	}
}
