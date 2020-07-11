<?php
namespace PublicApp\Service;
use Exception;
use SimpleXMLElement;
use TJM\Data\Model;
use TJM\Files\Files;

class Assets extends Model{
	protected $assetLinks = [];
	protected $distPath = 'dist';
	protected $iconDefaults = [];
	protected $iconSets = [];
	protected $icons = [];
	protected $iconsDistPath = 'icons';
	protected $projectPath;
	protected $scriptsPath = 'scripts';
	protected $scriptsDistPath = 'scripts';
	protected $stylesPath = 'styles';
	protected $stylesDistPath = 'styles';
	public function __construct($values = null){
		if($values){
			$this->set($values);
		}
	}
	public function linkAssets(){
		if($this->assetLinks){
			$this->createDistDir();
			foreach($this->assetLinks as $link){
				if(is_string($link)){
					$link = ['dest'=> $link, 'src'=> $link];
				}
				if(!(isset($link['dest']) && $link['src'])){
					throw new Exception('Assets link is malformed: ' . json_encode($link));
				}
				if(substr($link['src'], 0, 1) === '/'){
					$from = $link['src'];
				}else{
					$from = $this->projectPath . '/' . $link['src'];
				}
				if(substr($link['dest'], 0, 1) === '/'){
					$at = $link['dest'];
				}else{
					$at = $this->getDistPath() . '/' . $link['dest'];
				}
				if(strpos($from, '*') !== false){
					if(!file_exists($at)){
						exec("mkdir -p {$at}");
					}
					$froms = glob($from);
				}else{
					$froms = [$from];
				}
				foreach($froms as $from){
					Files::symlinkRelativelySafely($at, $from);
				}
			}
		}
	}
	public function clearDistDir(){
		if($this->getDistPath()){
			return exec("rm -rf {$this->getDistPath()}/*");
		}
		return false;
	}
	public function createDistDir(){
		if($this->getDistPath() && !file_exists($this->getDistPath())){
			return exec("mkdir -p {$this->getDistPath()}");
		}
		return false;
	}
	public function buildIcons(){
		if($this->icons){
			if(!file_exists($this->getIconsDistPath())){
				exec("mkdir -p {$this->getIconsDistPath()}");
			}
			foreach($this->icons as $icon){
				$attr = [];
				if(is_string($icon)){
					$dest = basename($icon);
					$src = $icon;
					$set = null;
				}else{
					$src = $icon['src'] ?? null;
					$dest = $icon['dest'] ?? basename($src);
					$set = $icon['set'] ?? null;
					if(isset($icon['attr'])){
						$attr = $icon['attr'];
					}
				}
				if($set && isset($this->iconSets[$set])){
					$set = $this->iconSets[$set];
					if(is_string($set)){
						$set = ['src'=> $set];
					}
					if(isset($set['attr'])){
						$attr = array_merge($set['attr'], $attr);
					}
					if(isset($set['src']) && substr($src, 0, 1) !== '/'){
						if(substr($set['src'], 0, 1) !== '/'){
							$set['src'] = $this->projectPath . '/' . $set['src'];
						}
						$src = $set['src'] . '/' . $src;
					}
				}
				if(isset($this->iconDefaults['attr'])){
					$attr = array_merge($this->iconDefaults['attr'], $attr);
				}
				if($src && $dest){
					if(substr($dest, 0, 1) !== '/'){
						$dest = $this->getIconsDistPath() . '/' . $dest;
					}
					copy($src, $dest);
					if(pathinfo($dest, PATHINFO_EXTENSION) === 'svg' && $attr){
						$svg = new SimpleXMLElement(file_get_contents($dest));
						if($svg){
							foreach($attr as $key=> $value){
								$svg->addAttribute($key, $value);
							}
							file_put_contents($dest, $svg->asXML());
						}
					}
				}
			}
		}
	}

	/*=====
	==paths
	=====*/
	public function getDistPath(){
		if(substr($this->distPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->distPath;
		}else{
			return $this->distPath;
		}
	}
	public function getIconsDistPath(){
		if(substr($this->iconsDistPath, 0, 1) !== '/'){
			return $this->distPath . '/' . $this->iconsDistPath;
		}else{
			return $this->iconsDistPath;
		}
	}
	public function getScriptsPath(){
		if(substr($this->scriptsPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->scriptsPath;
		}else{
			return $this->scriptsPath;
		}
	}
	public function getScriptsDistPath(){
		if(substr($this->scriptsDistPath, 0, 1) !== '/'){
			return $this->getDistPath() . '/' . $this->scriptsDistPath;
		}else{
			return $this->scriptsDistPath;
		}
	}
	public function getStylesPath(){
		if(substr($this->stylesPath, 0, 1) !== '/'){
			return $this->projectPath . '/' . $this->stylesPath;
		}else{
			return $this->stylesPath;
		}
	}
	public function getStylesDistPath(){
		if(substr($this->stylesDistPath, 0, 1) !== '/'){
			return $this->getDistPath() . '/' . $this->stylesDistPath;
		}else{
			return $this->stylesDistPath;
		}
	}
}
