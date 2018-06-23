<?php 

namespace jmd\controllers;


/**
 * 
 */
class AdminController extends Controller {

	private $pageNumber;
	private $postsPerPage = 20;
	private $paintingsPerPage = 8;


	public function __construct() {
		parent::__construct();
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}
	
	public function renderMainAdmin() {
		
		$posts = $this->postManager->getRecentPosts($max = 1);

		$comments = $this->commentManager->getRecentComments($max = 1);

		$twig = \jmd\models\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentBackHome.twig', [
			"posts" => $posts,
			"comments" => $comments]);
	}

	


}