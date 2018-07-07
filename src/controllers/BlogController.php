<?php 

namespace jmd\controllers;

use jmd\models\managers\PostManager;
use jmd\models\managers\PaintingManager;
use jmd\models\managers\PostImgManager;
use jmd\models\managers\CommentManager;
use jmd\models\managers\CategoryManager;
use jmd\models\managers\ImgManager;
use jmd\helpers\FrenchDate;

class BlogController
{

	private $postsPerPage = 6;
	private $commentsPerPage = 10;
	private $pageNumber;

	private $msg = array();
	private $err;

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
	 * [Get a list of x post for one page (pagination)]
	 * @return [array] [array of published post or post for one category]
	 */
	public function postsList()
	{
		$postManager = new PostManager();
		$pagesCount = $postManager->countPages($this->postsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}

		$firstIndex = ($this->pageNumber - 1) * $this->postsPerPage;

		$resp;

		if (isset($_GET["category"])) {
			$categoryManager = new CategoryManager();
			$categoryList = $categoryManager->getCategoryList();

			foreach ($categoryList as $value) {
				
				if ($_GET["category"] == strtolower($value->getName())) {
					$resp = $postManager->getPostsPerCat($firstIndex, $this->postsPerPage, $_GET["category"]);
				}
			}
		} else {
			$resp = $postManager->getPublishedPosts($firstIndex, $this->postsPerPage);
		}	

		foreach ($resp as $value) {
			$date = $value->getPublication();
			$newDate = new FrenchDate($date);
			$frenchDate = $newDate->getFrenchDate();
			$value->setPublication($frenchDate);
		}

		return $resp;
	}

	/**
	 * [Get a list of comments of one post]
	 * @param  [int] $postId [id of the post]
	 * @return [array]         [comments list]
	 */
	public function postCommentsList($postId)
	{
		$commentManager = new CommentManager();
		$pagesCount = $commentManager->countPages($this->commentsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}

		$firstIndex = ($this->pageNumber - 1) * $this->commentsPerPage;

		$comments = $commentManager->getPostComments($firstIndex, $this->commentsPerPage, $postId);

		foreach ($comments as $value) {
			$date = $value->getCreation();
			$newDate = new FrenchDate($date);
			$frenchDate = $newDate->getFrenchDate();
			$value->setCreation($frenchDate);
		}

		return $comments;		
	}

	/**
	 * [Display the main page og the blog]
	 */
	public function renderHomeBlog()
	{
		$categoryManager = new CategoryManager();
		$categories = $categoryManager->getCountPostByCat();

		$posts = $this->postsList();

		$postManager = new PostManager(); 
		$recentPosts = $postManager->getRecentPosts(5);
		foreach ($recentPosts as $value) {
			$date = $value->getPublication();
			$newDate = new FrenchDate($date);
			$frenchDate = $newDate->getFrenchDate();
			$value->setPublication($frenchDate);
		}

		$paintingManager = new PaintingManager();
		$paintings = $paintingManager->getRecentPaintings($max = 6);
		
		$postImgManager = new PostImgManager();
		$postImgs = $postImgManager->getPostImg();

		$postsImgsUrl = array();
		foreach ($posts as $value) {
			$imgsUrl = array();
			foreach ($postImgs as $valeur) {
			 	if ($value->getId() == $valeur->getPost_id()) {
			 		$url = $valeur->getUrl();
			 		$imgsUrl[] = $url;
			 	}
			}
			$postImgsUrl = $imgsUrl;
			$postsImgsUrl[$value->getId()] = $postImgsUrl;
		}

		$pageNumber = $this->pageNumber;
		$pagesCount = $postManager->countPages($this->postsPerPage);

		$twig = Twig::initTwig("src/views/");

		echo $twig->render('blogContent.twig', [
			"postsImgs" => $postsImgsUrl,
			"posts" => $posts,
			"recentPosts" => $recentPosts,
			"paintings" => $paintings,
			"categories" => $categories,
			"numberOfPages" => $pagesCount,
			"pageNumber" => $pageNumber]);
	}

	/**
	 * [Display the page to see only one post]
	 * @param  [int] $postId [id of the post]
	 */
	public function renderOnePost($postId)
	{
		$categoryManager = new CategoryManager();
		$categories = $categoryManager->getCountPostByCat();

		$postManager = new PostManager();
		$post = $postManager->getOnePost($postId);
		$recentPosts = $postManager->getRecentPosts(5);

		$paintingManager = new PaintingManager();
		$paintings = $paintingManager->getRecentPaintings($max = 6);
		
		$imgManager = new ImgManager();
		$postImgs = $imgManager->getPostImgs($postId);

		$commentManager = new CommentManager();
		$pagesCount = $commentManager->countPostCommentsPages($this->commentsPerPage, $postId);

		$comments = $this->postCommentsList($postId);

		$pageNumber = $this->pageNumber;

		if (isset($_SESSION["comment-msg"])) {
			$msg = $_SESSION["comment-msg"];
			unset($_SESSION["comment-msg"]);
		} else {
			$msg = null;
		}

		$twig = Twig::initTwig("src/views/");

		echo $twig->render('blogPostContent.twig', [
			"imgs" => $postImgs,
			"post" => $post,
			"recentPosts" => $recentPosts,
			"paintings" => $paintings,
			"categories" => $categories,
			"comments" => $comments,
			"numberOfPages" => $pagesCount,
			"pageNumber" => $pageNumber,
			"msg" => $msg]);
	}

	/**
	 * [php validation of the comment form and adding in the DB if correct]
	 * @param  [int] $postId  [id of the post concern by the comment]
	 * @param  [string] $name    [First name of the personn who send the comment]
	 * @param  [string] $content [text message of the comment]
	 */
	public function newComment($postId, $name, $content)
	{
		if (array_key_exists('name', $_POST) && !empty($name)) {
            $commentName = substr($name, 0, 100);
        } else {
            $this->msg[] = "Pas de prénom ! Vous devez saisir un prénom.";
            $this->err = true;
        }

        if (array_key_exists('comment', $_POST) && !empty($content)) {
            $comment = substr($content, 0, 16384);
        } else {
            $this->msg[] = 'Pas de commentaire ! Vous devez saisir un commentaire';
            $this->err = true;
        }

        if ($this->err) {
        	$_SESSION["comment-msg"] = implode(" - ", $this->msg);
        } else {
        	$this->msg[] = 'Merci pour votre commentaire !';
        	$_SESSION["comment-msg"] = implode(" - ", $this->msg);

        	$commentManager = new CommentManager();
			$commentManager->addComment($name, $content, $postId);	
        }

        header("location:index.php?action=blog&postId=$postId");
	}

	/**
	 * [Change the status of the comment to reposrted]
	 * @param  [int] $commentId [id of the comment]
	 * @param  [int] $postId    [id of the post linked]
	 * @return [bool]            [DB answer : success]
	 */
	public function reportComment($commentId, $postId)
	{
		$commentManager =  new CommentManager();
		$resp = $commentManager->updateReported($commentId);

		header("location:index.php?action=blog&postId=$postId");
	}

}

