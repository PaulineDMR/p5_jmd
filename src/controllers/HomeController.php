<?php 

namespace jmd\controllers;

class HomeController extends Controller {

	/*public function __construct() {
		$this->carousselImg();
	}*/

	public function carousselImg() {

		$paintingManager = new \jmd\models\PaintingManager();
		$resp = $paintingManager->getLastTenPaintingImg();

		return $resp;

		
	}

	



	public function displayHome() {

		$resp = $this->carousselImg();

		$loader = new \Twig_Loader_Filesystem('src/views');
		$twig = new \Twig_Environment($loader);
		$twig->addExtension(new \Twig_Extensions_Extension_Text());

		echo $twig->render('homeContent.twig', ['paintings' => $resp]);
		//echo $twig->render('footerContent.twig', ['posts' => $posts]);
		//
		

	}

} 