<?php
namespace PublicApp\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class IncludeFileExtension extends AbstractExtension{
	protected string $func;
	protected string $path;
	public function __construct(string $func, string $path){
		$this->func = $func;
		$this->path = $path;
	}
	public function getFunctions(){
		return [
			new TwigFunction($this->func, [$this, 'include']),
		];
	}
	public function include(string $path){
		//--quick prevention of including files outside of path
		$path = str_replace('../', '', $path);
		return file_get_contents($this->path . '/' . $path);
	}
}

