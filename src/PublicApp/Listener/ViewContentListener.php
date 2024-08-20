<?php
namespace PublicApp\Listener;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use TJM\Views\Views;
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
		$content = preg_replace(':(href=["\'])/content/:', '$1' . $this->router->generate('public_home'), $content);
		$format = pathinfo($path, PATHINFO_EXTENSION) ?? 'html';
		if($format === 'md' || $format === 'txt'){
			$content = preg_replace(':(\]\()/content/:', '$1' . $this->router->generate('public_home', [], UrlGeneratorInterface::ABSOLUTE_URL), $content);
		}

		$event->setContent($content);
	}
}
