<?php
namespace PublicApp\Controller;
use Symfony\Component\HttpFoundation\Request;

class DemoController extends Controller{
	public function demoAction(Request $request){
		return $this->renderPage('@Public/demo/demo.' . $request->getRequestFormat() . '.twig');
	}
}
