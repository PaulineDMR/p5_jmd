<?php 

namespace jmd\controllers;

class Controller {

	protected $paintingManager;
	protected $postManager;
	protected $postImgManager;
	protected $categoryManager;
	protected $adminManager;
	
	//protected $adminManager;

	public function __construct() {
		$this->postManager = new \jmd\models\managers\PostManager();
		$this->paintingManager = new \jmd\models\managers\PaintingManager();
		$this->postImgManager = new \jmd\models\managers\PostImgManager();
		$this->categoryManager = new \jmd\models\managers\CategoryManager();
		$this->adminManager = new \jmd\models\managers\AdminManager();

	}
}

?>