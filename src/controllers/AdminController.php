<?php 

namespace jmd\controllers;

use jmd\models\managers\PostManager;
use jmd\models\managers\ImgManager;
use jmd\models\managers\CommentManager;

/**
 * 
 */
class AdminController {

	private $pageNumber;
	private $postsPerPage = 20;
	private $paintingsPerPage = 8;

	/**
	 * [Set the number of the current page]
	 */
	public function __construct()
	{
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}
	
	/**
	 * [Display the main view of the administrator space]
	 */
	public function renderMainAdmin()
	{
		$postManager = new PostManager();
		$posts = $postManager->getRecentPosts($max = 1);
		
		$postId;
		foreach ($posts as $value) {
			$postId = $value->getId();
		}

		$postImgManager = new ImgManager();
		$postImg = $postImgManager->getPostImg($postId);

		$commentManager = new CommentManager();
		$comments = $commentManager->getRecentComments($max = 1);

		$twig = Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentBackHome.twig', [
			"posts" => $posts,
			"comments" => $comments,
			"img" => $postImg]);
	}

}