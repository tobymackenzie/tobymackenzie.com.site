<?php
namespace TJM\Pages;
use TJM\Pages\Entity\Page;

class Pages{
	protected $dataBasePath;
	public function __construct($dataBasePath){
		$this->dataBasePath = $dataBasePath;
	}

	//--data
	public function getPage($id){
		$basePath = $this->getPagePath($id);
		$fileData = $this->getPageDataPath($id);
		if(!file_exists($fileData)){
			return null;
		}
		$page = new Page(json_decode(file_get_contents($fileData), true));
		$page->setId($id);
		if(!$page->hasContent()){
			if(!$page->hasFileName()){
				$page->setFileName($id . '.txt');
			}
			$page->setContent(file_get_contents($basePath . '/' . $page->getFileName()));
		}
		return $page;
	}

	//--paths
	protected function getDataPath(){
		return $this->dataBasePath;
	}
	protected function getPagePath($id){
		if($id{0} !== '/'){
			$id = '/' . $id;
		}
		if($id === '/'){
			$id = '/index';
		}
		return $this->dataBasePath . $id;
	}
	protected function getPageDataPath($id){
		return $this->getPagePath($id) . '/data.json';
	}
}
