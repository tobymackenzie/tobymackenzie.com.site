<?php
namespace TJM\Pages\Entity;
use TJM\Data\Model;

class Page extends Model{
	protected $content;
	protected $id;
	protected $fileName;
	protected $title;
	protected $type = 'file';
}
