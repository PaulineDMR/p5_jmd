<?php 

namespace jmd\controllers;

class HomeController{

	public function displayHome() {
		$paintingManager = new \jmd\models\managers\PaintingManager();
		$resp = $paintingManager->getRecentPaintings($max = 10);

		$twig = \jmd\models\Twig::initTwig("src/views/");

		echo $twig->render('homeContent.twig', ['paintings' => $resp]);		
	}
} 