<?php 

namespace jmd\controllers;


/**
 * 
 */
class AdminCommentsController {
	private $pageNumber;
	private $commentsPerPage = 15;

	public function __construct() {
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}

	public function commentList() {
		$firstIndex = ($this->pageNumber - 1) * $this->commentsPerPage;

		$commentManager = new \jmd\models\managers\CommentManager();
		$pagesCount = $commentManager->countPages($this->commentsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}


		$resp = $commentManager->getComments($firstIndex, $this->commentsPerPage);

		return $resp;
	}

	
	public function renderAdminComments() {

		$comments = $this->commentList();

		$commentManager = new \jmd\models\managers\CommentManager();
		$numberOfPages = $commentManager->countPages($this->commentsPerPage);

		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentAdminComments.twig', [
			"comments" => $comments,
			"pageNumber" => $this->pageNumber,
			"numberOfPages" => $numberOfPages]);
	}


	public function commentDeletion($id) {
		$commentManager = new \jmd\models\managers\CommentManager();
		$resp = $commentManager->deleteComment($id);

		header("location:index.php?action=adminComments");
	}

	
	public function commentValidation($id) {
		$commentManager = new \jmd\models\managers\CommentManager();
		$resp = $commentManager->updateValidated($id);

		header("location:index.php?action=adminComments");
	}
	
}
