<?php
/*
settings that need to be accessed in multiple places for dev but also accessed in prod without loading dev classes
 */
namespace PublicApp;
class TMSite{
	static protected $isBuild = false;
	static public function isBuilding(){
		return static::$isBuild;
	}
	static public function setIsBuild(bool $val){
		static::$isBuild = true;
	}
}
