<?php 

namespace jmd\controllers;

/**
 * 
 */
class AdminPostsController {

	private $pageNumber;
	private $postsPerPage = 8;

	public function __construct() {
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}

	public function postList() {
		$firstIndex = ($this->pageNumber - 1) * $this->postsPerPage;

		$postManager = new \jmd\models\managers\PostManager();

		$pagesCount = $postManager->countPages($this->postsPerPage);

		if ($this->pageNumber > $pagesCount) {
			$this->pageNumber = $pagesCount;
		}

		$resp = $postManager->getPosts($firstIndex, $this->postsPerPage);

		foreach ($resp as $value) {
			$date = $value->getCreation();
			$newDate = new \jmd\helpers\FrenchDate($date);
			$frenchDate = $newDate->getFrenchDate();
			$value->setCreation($frenchDate);
		}

		return $resp;
	}

	
	public function renderPostsAdmin() {

		$posts = $this->postList();

		$postManager = new \jmd\models\managers\PostManager();

		$numberOfPages = $postManager->countPages($this->postsPerPage);

		$categoryManager = new \jmd\models\managers\CategoryManager();

		$categories = $categoryManager->getCatPost();
	
		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

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

	public function nonPostCatList($id) {
		$categoryManager = new \jmd\models\managers\CategoryManager();
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

	public function renderModifyPost($id) {
		$postManager = new \jmd\models\managers\PostManager();
		$post = $postManager->getOnePost($id);

		$categoryManager = new \jmd\models\managers\CategoryManager();
		$categories = $categoryManager->catPerPost($id);

		$imgManager = new \jmd\models\managers\ImgManager();
		$imgs = $imgManager->getPostImgs($id);

		$action = $_GET["action"];
		$choice = $_GET["choice"];

		$list = $this->nonPostCatList($id);
		
		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentModifyPost.twig', [
			"post" => $post,
			"categories" => $categories,
			"cats" => $list,
			"imgs" => $imgs,
			"action" => $action,
			"choice" => $choice]);
	}

	public function deleteCat($id, $cat) {
		$categoryManager = new \jmd\models\managers\CategoryManager();
		$categories = $categoryManager->deleteCatPost($cat);

		header("location:index.php?action=adminPosts&choice=modify&id=$id");
	}

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

	public function addImg($post_id) {
		$fileName = $this->uploadImg();
		$url = "http://localhost:8888/jmd/assets/img/posts/" .$fileName; //http:localhost:8888/jmd/assets/img/posts/ ou http://jmd.pdmrweb.com/assets/img/posts/ 

		$imgManager = new \jmd\models\managers\ImgManager();
		$imgManager->newImg($url, $fileName);
		$img = $imgManager->getImg($url);
		$img_id = $img->getId();
		$imgManager->newPostImg($post_id, $img_id);

		header("location:index.php?action=adminPosts&choice=modify&id=$post_id");
	}

	public function modifyPost($post_id, $title, $content) {
		$postManager = new \jmd\models\managers\PostManager();
		$postManager->updatePost($post_id, $title, $content);

		$categoryManager = new \jmd\models\managers\CategoryManager();
		
		foreach ($_POST["category"] as $value) {
			
			$resp = $categoryManager->getOneCat($value);
			$id = $resp->getId();	
					
			$categoryManager->newCatPost($post_id, $id);
		}

		header("location:index.php?action=adminPosts");

	}

	public function publish($id, $status) {
		$postManager = new \jmd\models\managers\PostManager();
		$postManager->updatePublished($id, $status);

		header("location:index.php?action=adminPosts");

	}

	public function nonPublish($id, $status) {
		$postManager = new \jmd\models\managers\PostManager();
		$postManager->publishedToNo($id, $status);

		header("location:index.php?action=adminPosts");

	}

	public function unlinkImg($file) {

		$path = "assets/img/posts/" .$file;
		$open = opendir("assets/img/posts/");
		unlink($path);
		closedir($open);
	}

	public function deleteImg($file, $id) {
		$this->unlinkImg($file);

		$imgManager = new \jmd\models\managers\ImgManager();
		$imgManager->deleteFile($file);

		header("location:index.php?action=adminPosts&choice=modify&id=$id");
	}

	public function delete($post_id) {
		$postManager = new \jmd\models\managers\PostManager();
		$postManager->deletePost($post_id);

		$commentManager = new \jmd\models\managers\CommentManager();
		$commentManager->deletePostComments($post_id);

		$imgManager = new \jmd\models\managers\ImgManager();

		$imgs = $imgManager->getPostImgs($post_id);
		foreach ($imgs as $value) {
			$file = $value->getFileName();
			$this->unlinkImg($file);
		}

		$imgManager->deletePostImgs($post_id);

		$categoryManager = new \jmd\models\managers\CategoryManager();
		$categoryManager->deletePostCats($post_id);

		$page = $this->pageNumber;

		header("location:index.php?action=adminPosts&page=$page");
	}

	public function createNewPost() {
		$postManager = new \jmd\models\managers\PostManager();
		$postManager->newPost();

		$post = $postManager->getLastPost();
		$id = $post->getId();
		
		$categoryManager = new \jmd\models\managers\CategoryManager();
		$categories = $categoryManager->getCategoryList();

		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentWritePost.twig', [
			"postId" => $id,
			"categories" => $categories]);

	}

}

