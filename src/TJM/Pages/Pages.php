<?php
namespace TJM\Pages;
use TJM\FileStore\FileStore;
use TJM\Pages\Entity\Page;

class Pages{
	protected $dataBasePath;
	protected $fileStore;
	protected $aliases;
	public function __construct($dataBasePath, FileStore $fileStore){
		$this->dataBasePath = $dataBasePath;
		$this->fileStore = $fileStore;
	}

	//--data
	//---aliases
	public function getAlias($id){
		if(!$this->hasAlias($id)){
			return null;
		}
		return new Page([
			'id'=> $id
			,'content'=> $this->aliases[$id]
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
	public function hasResponse($id){
		return $this->hasPage($id) || $this->hasAlias($id);
	}
	public function getResponse($id){
		return $this->getPage($id) ?? $this->getAlias($id);
	}

	//---pages
	public function getPage($id){
		if(!$this->hasPage($id)){
			return null;
		}
		$page = new Page(json_decode(file_get_contents($this->getPageDataPath($id)), true));
		$page->setId($id);
		if(!$page->hasContent()){
			switch($page->getType()){
				case 'file':
					if(!$page->hasFileName()){
						$page->setFileName($id . '.txt');
					}
					$page->setContent($this->fileStore->getFileContents($id, $page->getFileName()));
				break;
				case 'redirect':
					$page->setContent('/');
				break;
			}
		}
		return $page;
	}
	public function hasPage($id){
		return file_exists($this->getPageDataPath($id));
	}

	//--paths
	protected function getPageDataPath($id){
		if($id{0} !== '/'){
			$id = '/' . $id;
		}
		if($id === '/'){
			$id = '/index';
		}
		return $this->dataBasePath . $id . '.json';
	}
}
