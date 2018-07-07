<?php 

namespace jmd\controllers;

use jmd\models\managers\PaintingManager;

class HomeController
{

	/**
	 * [Display the home page of the web site]
	 */
	public function displayHome() {
		$paintingManager = new PaintingManager();
		$resp = $paintingManager->getRecentPaintings($max = 10);

		$twig = Twig::initTwig("src/views/");

		echo $twig->render('homeContent.twig', ['paintings' => $resp]);		
	}
} 