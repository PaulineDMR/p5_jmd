<?php 

namespace jmd\controllers;


/**
 * 
 */
class AdminController {

	private $pageNumber;
	private $postsPerPage = 20;
	private $paintingsPerPage = 8;


	public function __construct() {
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}
	
	public function renderMainAdmin() {
		$postManager = new \jmd\models\managers\PostManager();
		$posts = $postManager->getRecentPosts($max = 1);
		
		$postId;
		foreach ($posts as $value) {
			$postId = $value->getId();
		}

		$postImgManager = new \jmd\models\managers\ImgManager();
		$postImg = $postImgManager->getPostImg($postId);

		$commentManager = new \jmd\models\managers\CommentManager();
		$comments = $commentManager->getRecentComments($max = 1);

		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentBackHome.twig', [
			"posts" => $posts,
			"comments" => $comments,
			"img" => $postImg]);
	}

	


}