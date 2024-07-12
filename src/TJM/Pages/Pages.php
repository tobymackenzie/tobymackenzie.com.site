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
		$isPage = $this->hasPage($content);
		if($isPage){
			$content = "/{$content}";
		}
		if($_format && $_format !== 'html' && $isPage){
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
		$path = $this->getPageDataPath('_aliases');
		if(file_exists($path)){
			$this->aliases = json_decode(file_get_contents($path), true);
		}else{
			$this->aliases = [];
		}
	}

	//---responses
	public function hasPage($id){
		return substr($id, 0, 1) !== '_' && file_exists($this->getPageDataPath($id));
	}
	public function hasResponse($id){
		return $this->hasPage($id) || $this->hasAlias($id);
	}
	public function getResponse($id, $_format = null){
		return $this->getAlias($id, $_format);
	}

	//--paths
	protected function getPageDataPath($id){
		if(!$id || substr($id, 0, 1) !== '/'){
			$id = '/' . $id;
		}
		if($id === '/'){
			$id = '/index';
		}
		return $this->dataBasePath . $id . '.json';
	}
}
