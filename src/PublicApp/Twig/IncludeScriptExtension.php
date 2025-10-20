<?php
namespace PublicApp\Twig;

class IncludeScriptExtension extends IncludeFileExtension{
	protected string $func = 'include_script';
	public function __construct(string $path){
		$this->path = $path;
	}
}

