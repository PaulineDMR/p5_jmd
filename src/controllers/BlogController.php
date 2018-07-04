<?php 

namespace jmd\controllers;


class BlogController {

	private $postsPerPage = 6;
	private $commentsPerPage = 10;
	private $pageNumber;

	private $msg = array();
	private $err;


	public function __construct() {
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}


	public function postsList() {
		$postManager = new \jmd\models\managers\PostManager();
		$pagesCount = $postManager->countPages($this->postsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}

		$firstIndex = ($this->pageNumber - 1) * $this->postsPerPage;

		$resp;

		if (isset($_GET["category"])) {
			$categoryManager = new \jmd\models\managers\CategoryManager();
			$categoryList = $categoryManager->getCategoryList();

			foreach ($categoryList as $value) {
				
				if ($_GET["category"] == strtolower($value->getName())) {
					$resp = $postManager->getPostsPerCat($firstIndex, $this->postsPerPage, $_GET["category"]);

					return $resp;
				}
			}
		} else {
			$resp = $postManager->getPublishedPosts($firstIndex, $this->postsPerPage);
			return $resp;
		}		
	}

	public function postCommentsList($postId) {
		$commentManager = new \jmd\models\managers\CommentManager();
		$pagesCount = $commentManager->countPages($this->commentsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}

		$firstIndex = ($this->pageNumber - 1) * $this->commentsPerPage;

		$comments = $commentManager->getPostComments($firstIndex, $this->commentsPerPage, $postId);

		return $comments;		
	}


	public function renderHomeBlog() {
		$categoryManager = new \jmd\models\managers\CategoryManager();
		$categories = $categoryManager->getCountPostByCat();

		$posts = $this->postsList();

		$postManager = new \jmd\models\managers\PostManager(); 
		$recentPosts = $postManager->getRecentPosts(5);

		$paintingManager = new \jmd\models\managers\PaintingManager();
		$paintings = $paintingManager->getRecentPaintings($max = 6);
		
		$postImgManager = new \jmd\models\managers\PostImgManager();
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
		$pagesCount = $postManager->countPages($this->postsPerPage);;

		$twig = \jmd\models\Twig::initTwig("src/views/");

		//var_dump($postImgs);
		echo $twig->render('blogContent.twig', [
			"postsImgs" => $postsImgsUrl,
			"posts" => $posts,
			"recentPosts" => $recentPosts,
			"paintings" => $paintings,
			"categories" => $categories,
			"numberOfPages" => $pagesCount,
			"pageNumber" => $pageNumber]);
	}


	public function renderOnePost($postId) {
		$categoryManager = new \jmd\models\managers\CategoryManager();
		$categories = $categoryManager->getCountPostByCat();

		$postManager = new \jmd\models\managers\PostManager();
		$post = $postManager->getOnePost($postId);
		$recentPosts = $postManager->getRecentPosts(5);

		$paintingManager = new \jmd\models\managers\PaintingManager();
		$paintings = $paintingManager->getRecentPaintings($max = 6);
		
		$imgManager = new \jmd\models\managers\ImgManager();
		$postImgs = $imgManager->getPostImgs($postId);

		$commentManager = new \jmd\models\managers\CommentManager();
		$pagesCount = $commentManager->countPostCommentsPages($this->commentsPerPage, $postId);

		$comments = $this->postCommentsList($postId);

		$pageNumber = $this->pageNumber;

		if (isset($_SESSION["comment-msg"])) {
			$msg = $_SESSION["comment-msg"];
			unset($_SESSION["comment-msg"]);
		} else {
			$msg = null;
		}


		$twig = \jmd\models\Twig::initTwig("src/views/");

		//var_dump($postImgs);
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

	public function newComment($postId, $name, $content) {
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

        	$commentManager = new \jmd\models\managers\CommentManager();
			$commentManager->addComment($name, $content, $postId);	
        }

        header("location:index.php?action=blog&postId=$postId");
	}

	public function reportComment($commentId, $postId) {
		$commentManager =  new \jmd\models\managers\CommentManager();
		$resp = $commentManager->updateReported($commentId);

		header("location:index.php?action=blog&postId=$postId");
	}

}

