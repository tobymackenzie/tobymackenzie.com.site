<?php
namespace TJM\Data;
use Exception;

class Model{
	public function __construct($data = null){
		if($data){
			$this->set($data);
		}
	}
	public function __call($name, $args){
		$match = preg_match('/^([a-z]+)([A-Z][\w]+)$/', $name, $matches);
		if($match){
			array_unshift($args, lcfirst($matches[2]));
			return call_user_func_array([$this, $matches[1]], $args);
		}else{
			throw new Exception(get_class($this) . " has no method {$name}.");
		}
	}
	protected function add($key, $value){
		$this->{$key}[] = $value;
		return $this;
	}
	protected function get($key){
		if($this->has($key)){
			return $this->$key;
		}else{
			throw new Exception(get_class($this) . " doesn't have value at key {$key}.");
		}
	}
	protected function has($key){
		return (isset($this->$key));
	}
	protected function remove($key){
		unset($this->$key);
	}
	protected function set($keyOrData, $value = null){
		if(is_array($keyOrData)){
			foreach($keyOrData as $key=> $value){
				$this->set($key, $value);
			}
		}else{
			$this->$keyOrData = $value;
		}
		return $this;
	}
	protected function setIfNotSet($keyOrData, $value = null){
		if(is_array($keyOrData)){
			foreach($keyOrData as $key=> $value){
				$this->setIfNotSet($key, $value);
			}
		}elseif(!$this->has($keyOrData)){
			$this->set($keyOrData, $value);
		}
		return $this;
	}
}
