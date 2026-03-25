<?php
namespace PublicApp\Listener;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use TJM\WikiSite\Event\ViewContentEvent;

class ViewContentListener{
	public RouterInterface $router;
	public function __construct(
		RouterInterface $router
	){
		$this->router = $router;
	}
	public function __invoke(ViewContentEvent $event){
		$content = $event->getContent();
		$path = $event->getPath();
		//--support github friendly URLs in content repo
		//-! should we just do this as part of wiki-site?
		$router = $this->router;
		$format = pathinfo($path, PATHINFO_EXTENSION) ?: 'html';
		$content = preg_replace_callback(':(href=["\'])/content/([^\'"]*):', function($matches) use($format, $router){
			$result = $matches[1] . $router->generate('public_home');
			//--must strip off / replace trailing '.md', needed for github URLs
			if(pathinfo($matches[2], PATHINFO_EXTENSION) === 'md'){
				$result .= substr($matches[2], 0, -3);
				if($format !== 'html'){
					if($matches[2] === '/'){
						$result .= 'index.' . $format;
					}else{
						$result .= '.' . $format;
					}
				}
			}else{
				$result .= $matches[2];
			}
			return $result;
		}, $content);
		if($format === 'md' || $format === 'txt'){
			$content = preg_replace(':(\]\()/content/:', '$1' . $this->router->generate('public_home', [], UrlGeneratorInterface::ABSOLUTE_URL), $content);
		}

		$event->setContent($content);
	}
}
