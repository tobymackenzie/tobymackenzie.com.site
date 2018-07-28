<?php
namespace TJM\FileStore;
use SplFileObject;

class FileStore{
	protected $basePath;
	public function __construct($basePath){
		$this->basePath = $basePath;
	}

	//--files
	public function getFile($id, $file, $mode = 'r'){
		return new SplFileObject($this->getItemFilePath($id, $file), $mode);
	}
	public function getFileContents($id, $file){
		$file = $this->getFile($id, $file);
		if($file->isFile() && $file->isReadable()){
			return file_get_contents($file->getPathname());
		}else{
			return null;
		}
	}

	//--paths
	protected function getItemPath($id){
		if($id{0} !== '/'){
			$id = '/' . $id;
		}
		if($id === '/'){
			$id = '/index';
		}
		return $this->basePath . $id;
	}
	protected function getItemFilePath($id, $file){
		return $this->getItemPath($id) . '/' . $file;
	}
}
