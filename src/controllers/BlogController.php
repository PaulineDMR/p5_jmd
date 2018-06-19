<?php 

namespace jmd\controllers;


class BlogController extends Controller {

	private $postsPerPage = 5;
	private $pageNumber;
	private $firstIndex;


	public function __construct() {
		parent::__construct();
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}

		$this->firstIndex = ($this->pageNumber - 1) * $this->postsPerPage;
	}


	public function postsList() {
		$pagesCount = $this->postManager->countPages($this->postsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}

		$resp;

		if (isset($_GET["category"])) {

			$categoryList = $this->categoryManager->getCategoryList();

			foreach ($categoryList as $value) {
				
				if ($_GET["category"] == $value->getName()) {
					$resp = $this->postManager->getPostsPerCat($this->firstIndex, $this->postsPerPage, $_GET["category"]);

					return $resp;
				}
			}
		}

		$resp = $this->postManager->getPosts($this->firstIndex, $this->postsPerPage);

		return $resp;		
	}


	public function renderHomeBlog() {

		$categories = $this->categoryManager->getCountPostByCat();

		$posts = $this->postsList();

		$paintings = $this->paintingManager->getRecentPaintings($max = 6);
		
		$postImgs = $this->postImgManager->getPostImg(2);

		$twig = \jmd\models\Twig::initTwig();

		//var_dump($postImgs);
		echo $twig->render('blogContent.twig', [
			"imgs" => $postImgs,
			"posts" => $posts,
			"paintings" => $paintings,
			"categories" => $categories]);
	}


	public function renderOnePost() {

		$categories = $this->categoryManager->getCountPostByCat();

		$post = $this->postManager->getOnePost($_GET["postId"]);

		$paintings = $this->paintingManager->getRecentPaintings($max = 6);
		
		$postImgs = $this->postImgManager->getPostImg();

		$twig = \jmd\models\Twig::initTwig();

		//var_dump($postImgs);
		echo $twig->render('blogPostContent.twig', [
			"imgs" => $postImgs,
			"post" => $post,
			"paintings" => $paintings,
			"categories" => $categories]);
	}

}

