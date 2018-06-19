<?php 

namespace jmd\controllers;

class Controller {

	protected $paintingManager;
	protected $postManager;
	protected $postImgManager;
	protected $categoryManager;
	
	//protected $adminManager;

	public function __construct() {
		$this->postManager = new \jmd\models\PostManager();
		$this->paintingManager = new \jmd\models\PaintingManager();
		$this->postImgManager = new \jmd\models\PostImgManager();
		$this->categoryManager = new \jmd\models\CategoryManager();

	}
}

?>