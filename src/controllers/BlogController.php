<?php 

namespace jmd\controllers;


class BlogController {

	private $postsPerPage = 5;
	private $commentsPerPage = 10;
	private $pageNumber;


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
				
				if ($_GET["category"] == $value->getName()) {
					$resp = $postManager->getPostsPerCat($firstIndex, $this->postsPerPage, $_GET["category"]);

					return $resp;
				}
			}
		}

		$resp = $postManager->getPublishedPosts($firstIndex, $this->postsPerPage);

		return $resp;		
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

		$twig = \jmd\models\Twig::initTwig("src/views/");

		//var_dump($postImgs);
		echo $twig->render('blogContent.twig', [
			"imgs" => $postImgs,
			"posts" => $posts,
			"recentPosts" => $recentPosts,
			"paintings" => $paintings,
			"categories" => $categories]);
	}


	public function renderOnePost() {
		$categoryManager = new \jmd\models\managers\CategoryManager();
		$categories = $categoryManager->getCountPostByCat();

		$postManager = new \jmd\models\managers\PostManager();
		$post = $postManager->getOnePost($_GET["postId"]);
		$recentPosts = $postManager->getRecentPosts(5);

		$paintingManager = new \jmd\models\managers\PaintingManager();
		$paintings = $paintingManager->getRecentPaintings($max = 6);
		
		$imgManager = new \jmd\models\managers\ImgManager();
		$postImgs = $imgManager->getPostImgs($_GET["postId"]);

		$commentManager = new \jmd\models\managers\CommentManager();
		$pagesCount = $commentManager->countPages($this->commentsPerPage);

		$comments = $this->postCommentsList($_GET["postId"]);

		$pageNumber = $this->pageNumber;


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
			"pageNumber" => $pageNumber]);
	}

	public function newComment($post_id) {
		$commentManager = new \jmd\models\managers\CommentManager();
		$commentManager->addComment($_POST["name"], $_POST["comment"], $post_id);

		header("location:index.php?action=blog&postId=$post_id");
	}

}

