<?php 

namespace jmd\controllers;

class HomeController extends Controller {

	/*public function __construct() {
		$this->carousselImg();
	}*/

	public function carousselImg() {

		$resp = $this->paintingManager->getRecentPaintings($max = 10);

		return $resp;	
	}

	public function displayHome() {

		$resp = $this->paintingManager->getRecentPaintings($max = 10);

		$twig = \jmd\models\Twig::initTwig();

		echo $twig->render('homeContent.twig', ['paintings' => $resp]);		
	}
} 