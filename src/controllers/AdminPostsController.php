<?php 

namespace jmd\controllers;

use jmd\models\managers\PostManager;
use jmd\models\managers\ImgManager;
use jmd\models\managers\CommentManager;
use jmd\models\managers\CategoryManager;
use jmd\helpers\FrenchDate;


class AdminPostsController
{

	private $pageNumber;
	private $postsPerPage = 8;

	/**
	 * [Set the number of the current page]
	 */
	public function __construct() {
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}

	/**
	 * [Get the list of x post for this page (pagination)]
	 * @return [array] [list of object Post]
	 */
	public function postList()
	{
		$postManager = new PostManager();

		$pagesCount = $postManager->countPages($this->postsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}
		$firstIndex = ($this->pageNumber - 1) * $this->postsPerPage;

		$resp = $postManager->getPosts($firstIndex, $this->postsPerPage);

		foreach ($resp as $value) {
			$date = $value->getCreation();
			$newDate = new FrenchDate($date);
			$frenchDate = $newDate->getFrenchDate();
			$value->setCreation($frenchDate);
		}

		return $resp;
	}

	/**
	 * [Display the admin for post administration]
	 */
	public function renderPostsAdmin() {

		$posts = $this->postList();

		$postManager = new PostManager();

		$numberOfPages = $postManager->countPages($this->postsPerPage);

		$categoryManager = new CategoryManager();

		$categories = $categoryManager->getCatPost();
	
		$twig = Twig::initTwig("src/views/backoffice/");

		$action = $_GET["action"];
		$choice = null;
		if (isset($_GET["choice"])) {
			$choice = $_GET["choice"];
		}

		echo $twig->render('contentAdminPosts.twig', [
			"posts" => $posts,
			"pageNumber" => $this->pageNumber,
			"numberOfPages" => $numberOfPages,
			"categories" => $categories,
			"action" => $action,
			"choice" => $choice]);
	}

	/**
	 * [get list of category not atributed to a post]
	 * @param  [int] $id [id of the post]
	 * @return [array]     [list of category not use for this post]
	 */
	public function nonPostCatList($id) {
		$categoryManager = new CategoryManager();
		$postCat = $categoryManager->catPerPost($id);
		$cat = $categoryManager->getCategoryList();

		$postCatList = array();
		foreach ($postCat as $value) {
			$catName = $value->getName();
			$postCatList[] = $catName;
		}

		$catList = array();
		foreach ($cat as $value) {
			$catName = $value->getName();
			$catList[] = $catName;
		}

		$list = array();

		foreach ($catList as $value) {

		 	if (!in_array("$value", $postCatList)) {
				$list[] = $value;
			}			
		}

		return $list;

	}

	/**
	 * [Display the view to modify a post data]
	 * @param  [int] $id [Id of the post to modify]
	 */
	public function renderModifyPost($id) {
		$postManager = new PostManager();
		$post = $postManager->getOnePost($id);

		$categoryManager = new CategoryManager();
		$categories = $categoryManager->catPerPost($id);

		$imgManager = new ImgManager();
		$imgs = $imgManager->getPostImgs($id);

		$action = $_GET["action"];
		$choice = $_GET["choice"];

		$list = $this->nonPostCatList($id);
		
		$twig = Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentModifyPost.twig', [
			"post" => $post,
			"categories" => $categories,
			"cats" => $list,
			"imgs" => $imgs,
			"action" => $action,
			"choice" => $choice]);
	}

	/**
	 * [Unlink a category to a post]
	 * @param  [int] $id  [id of the post]
	 * @param  [string] $cat [name of the category to remove]
	 * @return [bool]      [db response : succes]
	 */
	public function deleteCat($id, $cat) {
		$categoryManager = new CategoryManager();
		$categories = $categoryManager->deleteCatPost($cat);

		header("location:index.php?action=adminPosts&choice=modify&id=$id");
	}

	/**
	 * [Set input file data to upload a img]
	 * @return [string] [name of the img uploaded]
	 */
	public function uploadImg() {
		if (isset($_POST["submit"])) {
		 	$file = $_FILES["file"];

		 	$fileName = $file["name"];
		 	$fileTmpName = $file["tmp_name"];
		 	$fileErr = $file["error"];
		 	$fileSize = $file["size"];
		 	$fileType = $file["type"];

		 	$fileExt = explode(".", $fileName);
		 	$fileActualExt = strtolower(end($fileExt));

		 	$allowed = array("jpg", "jpeg", "png", "tiff");

		 	if (in_array($fileActualExt, $allowed)) {
		 		if ($fileErr === 0) {
		 			if ($fileSize < 8000000) {
		 				$fileNameNew = uniqid('', true). "." .$fileActualExt;
		 				
		 				$fileDestination = 'assets/img/posts/' .$fileNameNew;
		 				move_uploaded_file($fileTmpName, $fileDestination);

		 				return $fileNameNew;

		 			} else {
		 				throw new \Exception("Le fichier est trop lourd pour être téléchargé.", 1);
		 			}
		 		} else {
		 			throw new \Exception("Une erreur est survenue pendant le chargement.", 1);			
		 		}
		 	} else {
		 		throw new \Exception("Ce fichier n'est pas autorisé.", 1);		
		 	}
		} 
	}

	/**
	 * [add a entry in DB to link an img to a post]
	 * @param [int] $post_id [id of the post]
	 */
	public function addImg($post_id) {
		$fileName = $this->uploadImg();
		$url = "assets/img/posts/" .$fileName; 

		$imgManager = new ImgManager();
		$imgManager->newImg($url, $fileName);
		$img = $imgManager->getImg($url);
		$img_id = $img->getId();
		$imgManager->newPostImg($post_id, $img_id);

		header("location:index.php?action=adminPosts&choice=modify&id=$post_id");
	}

	/**
	 * [Update modified post datas in DB]
	 * @param  [int] $post_id [id of the post]
	 * @param  [string] $title   [title of the post]
	 * @param  [string] $content [text content of the post]
	 */
	public function modifyPost($post_id, $title, $content) {
		$postManager = new PostManager();
		$postManager->updatePost($post_id, $title, $content);

		$categoryManager = new CategoryManager();
		
		foreach ($_POST["category"] as $value) {
			
			$resp = $categoryManager->getOneCat($value);
			$id = $resp->getId();	
					
			$categoryManager->newCatPost($post_id, $id);
		}

		header("location:index.php?action=adminPosts");

	}

	/**
	 * [Set the post status of publication to true in DB]
	 * @param  [int] $id     [id of the post]
	 * @param  [bool] $status [true = published]
	 */
	public function publish($id, $status) {
		$postManager = new PostManager();
		$postManager->updatePublished($id, $status);

		header("location:index.php?action=adminPosts");

	}

	/**
	 * [Set the post status of publication to false in DB]
	 * @param  [int] $id     [id of the post]
	 * @param  [bool] $status [false = non published]
	 */
	public function nonPublish($id, $status) {
		$postManager = new PostManager();
		$postManager->publishedToNo($id, $status);

		header("location:index.php?action=adminPosts");

	}

	/**
	 * [Remove img file from the directory]
	 * @param  [string] $file [file name]
	 */
	public function unlinkImg($file) {

		$path = "assets/img/posts/" .$file;
		$open = opendir("assets/img/posts/");
		unlink($path);
		closedir($open);
	}

	/**
	 * [Remove img from directory and from DB]
	 * @param  [string] $file [file name]
	 * @param  [id] $id   [id of the file in IMG table]
	 */
	public function deleteImg($file, $id) {
		$this->unlinkImg($file);

		$imgManager = new ImgManager();
		$imgManager->deleteFile($file);

		header("location:index.php?action=adminPosts&choice=modify&id=$id");
	}

	/**
	 * [Delete a post from DB and the comments linked to it]
	 * @param  [int] $post_id [id of the post to delete]
	 */
	public function delete($post_id) {
		$postManager = new PostManager();
		$postManager->deletePost($post_id);

		$commentManager = new CommentManager();
		$commentManager->deletePostComments($post_id);

		$imgManager = new ImgManager();

		$imgs = $imgManager->getPostImgs($post_id);
		foreach ($imgs as $value) {
			$file = $value->getFileName();
			$this->unlinkImg($file);
		}

		$imgManager->deletePostImgs($post_id);

		$categoryManager = new CategoryManager();
		$categoryManager->deletePostCats($post_id);

		$page = $this->pageNumber;

		header("location:index.php?action=adminPosts&page=$page");
	}

	/**
	 * [Add a new entry post in posts table in DB]
	 */
	public function createNewPost() {
		$postManager = new PostManager();
		$postManager->newPost();

		$post = $postManager->getLastPost();
		$id = $post->getId();
		
		$categoryManager = new CategoryManager();
		$categories = $categoryManager->getCategoryList();

		$twig = Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentWritePost.twig', [
			"postId" => $id,
			"categories" => $categories]);
	}

}

