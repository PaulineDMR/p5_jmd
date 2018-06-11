<?php 

require_once ('Controller.php'); // A modifier : Autoloader


class HomeController extends Controller {

	/*public function __construct() {
		$this->carousselImg();
	}*/

	public function carousselImg() {

		$paintingManager = new PaintingManager();
		$resp = $paintingManager->getLastTenPaintingImg();

		return $resp;

		
	}

	



	public function displayHome() {

		$resp = $this->carousselImg();

		$loader = new Twig_Loader_Filesystem('views');
		$twig = new Twig_Environment($loader);
		$twig->addExtension(new Twig_Extensions_Extension_Text());

		return $twig->render('homeContent.twig', ['paintings' => $resp]);
		//echo $twig->render('footerContent.twig', ['posts' => $posts]);
		//
		

	}

} 