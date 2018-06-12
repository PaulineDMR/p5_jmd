<?php 

namespace jmd\controllers;

class Controller {

	protected $paintingManager;
	protected $postManager;
	
	//protected $adminManager;

	public function __construct() {
		$this->postManager = new \jmd\models\PostManager();
		$this->paintingManager = new \jmd\models\PaintingManager();
		//$this->adminManager = new AdminManager();
	}
}

?>