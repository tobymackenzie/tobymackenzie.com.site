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
	const SUPPORTED_FORMATS = [null, 'html', 'md', 'txt', 'xhtml'];
	public function pageAction(
		$id
		,Pages $pagesService
		,Request $request
		,WikiSite $wikiSite
		,$_format = null
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
}
