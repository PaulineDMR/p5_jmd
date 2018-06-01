<?php 

require_once ('../models/class/PostManager.php');


class Footer {

	public function lastTwoPosts() {
		$postManager = new PostManager();
		$resp = $postManager->getLastTwoPosts();

		$loader = new Twig_Loader_Filesystem('views');
		$twig = new Twig_Environment($loader);
		$twig->render('footer.twig', ['posts' => $resp]);

		return $twig;
	}
	
}
