<?php
namespace TJM\Pages;
use TJM\FileStore\FileStore;
use TJM\Pages\Entity\Page;

class Pages{
	protected $dataBasePath;
	protected $fileStore;
	public function __construct($dataBasePath, FileStore $fileStore){
		$this->dataBasePath = $dataBasePath;
		$this->fileStore = $fileStore;
	}

	//--data
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
