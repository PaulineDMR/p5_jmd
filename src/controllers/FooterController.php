<?php 

require_once ('Controller.php'); // A modifier : Autoloader


class FooterController extends Controller {

	/*public function __construct() {
		$this->_______();
	}*/

	public function RecentPosts() {
		$postManager = new PostManager();
		$posts = $postManager->getRecentPosts($max = 2);

		return $posts;


	}

	public function footerRender() {
		$this->RecentPosts();

		require '../views.footerContent.php';

	}

} 