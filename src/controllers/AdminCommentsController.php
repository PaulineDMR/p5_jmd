<?php 

namespace jmd\controllers;

use jmd\models\managers\CommentManager;
use jmd\helpers\FrenchDate;

/**
 * 
 */
class AdminCommentsController {

	private $pageNumber;
	private $commentsPerPage = 15;

	/**
	 * [Set the value of $pageNumber]
	 */
	public function __construct() {
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}

	/**
	 * [get the list of x comments per page]
	 * @return [array] [comments list]
	 */
	public function commentList() {
		$commentManager = new CommentManager();
		$pagesCount = $commentManager->countPages($this->commentsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}
		$firstIndex = ($this->pageNumber - 1) * $this->commentsPerPage;

		$resp = $commentManager->getComments($firstIndex, $this->commentsPerPage);

		return $resp;
	}

	/**
	 * [Display the view for administrate comments]
	 */
	public function renderAdminComments() {

		$comments = $this->commentList();

		$commentManager = new CommentManager();
		$numberOfPages = $commentManager->countPages($this->commentsPerPage);

		foreach ($comments as $value) {
			$date = $value->getCreation();
			$newDate = new FrenchDate($date);
			$frenchDate = $newDate->getFrenchDate();
			$value->setCreation($frenchDate);
		}

		$twig = Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentAdminComments.twig', [
			"comments" => $comments,
			"pageNumber" => $this->pageNumber,
			"numberOfPages" => $numberOfPages]);
	}

	/**
	 * [Delete a comment]
	 * @param  [int] $id [id of the comment to delete]
	 */
	public function commentDeletion($id) {
		$commentManager = new CommentManager();
		$resp = $commentManager->deleteComment($id);

		$page = $this->pageNumber;

		header("location:index.php?action=adminComments&page=$page");
	}

	/**
	 * [User validation of a comment]
	 * @param  [int] $id [id of the comment to validate]
	 * @return [bool]     [response from db : success or fail]
	 */
	public function commentValidation($id) {
		$commentManager = new CommentManager();
		$resp = $commentManager->updateValidated($id);

		header("location:index.php?action=adminComments");
	}
	
}
