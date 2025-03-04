<?php
namespace TJM\Pages;
use Exception;
use TJM\Pages\Entity\Page;

class Pages{
	protected $dataBasePath;
	protected $aliases;
	public function __construct($dataBasePath){
		$this->dataBasePath = $dataBasePath;
	}

	//--data
	//---aliases
	public function getAlias($id, $_format = null){
		if(!$this->hasAlias($id)){
			return null;
		}
		$content = $this->aliases[$id];
		if(strpos($content, '/') === false){
			$content = '/' . $content;
		}
		if($_format && $_format !== 'html'){
			$content .= ".{$_format}";
		}
		return new Page([
			'id'=> $id
			,'content'=> $content
			,'type'=> 'redirect'
		]);
	}
	public function hasAlias($id){
		if(!isset($this->aliases)){
			$this->loadAliases();
		}
		return isset($this->aliases[$id]);
	}
	protected function loadAliases(){
		$path = $this->dataBasePath . '/aliases.json';
		if(file_exists($path)){
			$this->aliases = json_decode(file_get_contents($path), true);
		}else{
			$this->aliases = [];
		}
	}
	public function getResponse($id, $_format = null){
		return $this->getAlias($id, $_format);
	}
}
