<?php
namespace PublicApp\Twig;

class IncludeDataExtension extends IncludeFileExtension{
	protected string $func = 'include_data';
	public function __construct(string $path){
		$this->path = $path;
	}
}

