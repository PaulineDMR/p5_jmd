<?php 

require ('models/class/PaintingManager.php'); // A modifier : Autoloader


class HomeController {

	/*public function __construct() {
		$this->carousselImg();
	}*/

	public function carousselImg() {

		$paintingManager = new PaintingManager();
		$resp = $paintingManager->getLastTenPaintingImg();

		//return $resp;
		$loader = new Twig_Loader_Filesystem('views');
		$twig = new Twig_Environment($loader);
		echo $twig->render('home.twig', ['paintings' => $resp]);

	}

} 