<?php
namespace TJM\Pages;
use Exception;
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
	public function hasResponse($id){
		return $this->hasPage($id) || $this->hasAlias($id);
	}
	public function getResponse($id, $_format = null){
		return $this->getPage($id) ?? $this->getAlias($id, $_format);
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
					try{
						$page->setContent($this->fileStore->getFileContents($id, $page->getFileName()));
					}catch(Exception $e){
						return null;
					}
				break;
				case 'redirect':
					$page->setContent('/');
				break;
			}
		}
		return $page;
	}
	public function hasPage($id){
		return substr($id, 0, 1) !== '_' && file_exists($this->getPageDataPath($id));
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
