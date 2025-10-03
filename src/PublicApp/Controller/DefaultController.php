<?php
namespace PublicApp\Controller;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use TJM\WikiSite\WikiSite;

class DefaultController extends Controller{
	const SUPPORTED_FORMATS = [null, 'html', 'md', 'txt', 'xhtml'];
	public function pageAction(
		$id
		,Request $request
		,WikiSite $wikiSite
		,$_format = null
	){
		//-!! temporarily pass through to new action with fall back to old logic until we have redirects implemented properly
		try{
			return $wikiSite->viewAction($_format && $_format !== 'html' ? $id . '.' . $_format : $id);
		}catch(\Exception $e){
			$aliases = json_decode(file_get_contents(__DIR__ . '/../../../data/aliases.json'), true);
			$alias = $aliases[$id] ?? null;
			if($alias === null){
				throw $this->createNotFoundException();
			}
			if(strpos($alias, '/') === false){
				$alias = '/' . $alias;
			}
			if($_format && $_format !== 'html'){
				$alias .= ".{$_format}";
			}
			return $this->redirect($alias);
		}
	}
}
