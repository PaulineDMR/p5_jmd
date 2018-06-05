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

	public function footerLastPosts () {
		$postManager = new PostManager();
		$posts = $postManager->getLastTwoPosts();

		return $posts;


	}

	public function displayHome() {

		$loader = new Twig_Loader_Filesystem('views');
		$twig = new Twig_Environment($loader);
		$twig->addExtension(new Twig_Extensions_Extension_Text());

		$resp = $this->carousselImg();
		
		$posts = $this->footerLastPosts();
		
		echo $twig->render('home.twig', ['paintings' => $resp]);

		echo $twig->render('footerContent.twig', ['posts' => $posts]);
	

	}

} 