<?php
namespace TJM\Pages;

class Pages{
	protected $dataBasePath;
	public function __construct($dataBasePath){
		$this->dataBasePath = $dataBasePath;
	}
	protected function getDataPath(){
		return $this->dataBasePath;
	}
	public function getPagePath($id){
		if($id{0} !== '/'){
			$id = '/' . $id;
		}
		if($id === '/'){
			$id = '/index';
		}
		return $this->dataBasePath . $id;
	}
	public function getPageDataPath($id){
		return $this->getPagePath($id) . '/data.json';
	}
}
