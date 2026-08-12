<?php
namespace PublicApp;
use TJM\Files\Files;
class TMSite{
	static protected $isBuild = false;

	//==build
	//-# settings that need to be accessed in multiple places for dev but also accessed in prod without loading dev classes
	static public function isBuilding(){
		return static::$isBuild;
	}
	static public function setIsBuild(bool $val){
		static::$isBuild = $val;
	}

	//==init
	static public function init(){
		//--config
		$projectPath = realpath(__DIR__ . '/..');
		$autoloadPath = $projectPath . '/vendor/autoload.php';

		//--make sure we have var folders
		foreach([
			'var',
			'var/cache',
			'var/logs',
		] as $dir){
			$path = $projectPath . '/' . $dir;
			if(!file_exists($path)){
				passthru('mkdir ' . $path);
			}
		}

		//--make sure we have vendors installed
		if(!file_exists($autoloadPath)){
			chdir($projectPath);
			$composerCommand = $localCompser = __DIR__ . '/composer';
			if(!file_exists($localCompser)){
				if(empty(shell_exec("which composer"))){
					throw new Exception("Composer must be installed to install project dependencies");
				}else{
					$composerCommand = 'composer';
				}
			}
			passthru($composerCommand . ' install', $return);
			if($return !== 0){
				throw new Exception("Installing project dependencies failed");
			}
		}
	}
}
