<?php 


namespace jmd\controllers;

/**
 * 
 */
class AdminPaintingsController {
	
	private $pageNumber;
	private $paintingsPerPage = 8;


	public function __construct() {
		if (!empty($_GET["page"]) && is_numeric($_GET["page"])) {
			$this->pageNumber = $_GET["page"];
		} else {
			$this->pageNumber = 1;
		}
	}

	public function getPageCount() {
		$paintingManager = new \jmd\models\managers\PaintingManager();
		$count = $paintingManager->countPaintings();

		if(($count->getCount() % $this->paintingsPerPage) == 0) {
			$pages = $count->getCount() / $this->paintingsPerPage;
		} else {
			$pages = ceil($count->getCount() / $this->paintingsPerPage);
		}
		return $pages;
	}

	public function renderPaintingsAdmin() {
		$firstIndex = ($this->pageNumber - 1) * $this->paintingsPerPage;

		$paintingManager = new \jmd\models\managers\PaintingManager();
		$paintings = $paintingManager->getPaintings($firstIndex, $this->paintingsPerPage);

		foreach ($paintings as $value) {
			$date = $value->getCreation();
			if ($date != null) {
				$newDate = new \DateTime($date);
				$frenchDate = $newDate->format("m-Y");
				$value->setCreation($frenchDate);
			}
		}

		$action = $_GET["action"];

		$pageNumber = $this->pageNumber;

		$numberOfPages = $this->getPageCount();

		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentAdminPaintings.twig', [
			"paintings" => $paintings,
			"action" => $action,
			"pageNumber" => $pageNumber,
			"numberOfPages" => $numberOfPages]);
	}

	public function renderAddPainting() {

		$action = $_GET["action"];

		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentAddPainting.twig', ["action" => $action]);
	}


	public function upload() {
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
		 				
		 				$fileDestination = 'assets/img/paintings/' .$fileNameNew;
		 				move_uploaded_file($fileTmpName, $fileDestination);

		 				$url = "http://localhost:8888/jmd/assets/img/paintings/" .$fileNameNew; // OU http://jmd.pdmrweb/assets/img/paintings/

		 				$action = $_GET["action"];
		 				
		 				if ( isset($_GET["img"])) {
		 					$img = $_GET["img"];
		 				} else {
		 					$img = null;
		 				}

						$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");

						echo $twig->render('contentAddPainting.twig', [
							"action" => $action,
						 	"url" => $url,
						 	"img" => $img,
						 	"name" => $fileNameNew]);


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

	public function delete() {
		$path = "assets/img/paintings/" .$_GET["name"];

		$open = opendir("assets/img/paintings/");

		unlink($path);

		closedir($open);

		header("location:index.php?action=addPainting");	
	}
		
	public function addImg() {

		$url = "http://localhost:8888/jmd/assets/img/paintings/" .$_GET["name"];

		$imgManager = new \jmd\models\managers\ImgManager();
		$imgManager->newImg($url);

		$img = $imgManager->getPaintingId($url);
		$id = $img->getId();

		return $id;		
	}


	public function newPainting() {

		$img_id = $this->addImg();

		$title;
		$width;
		$height;
		$creation;
		$technic;
		$price;
		$theme;
		$category;
		$sold;
		$published;

		if (empty($_POST["title"])) {
			$title = null;
		} else {
			$title = $_POST["title"];
		}

		if (empty($_POST["width"])) {
			$width = 0;
		} else {
			$width = $_POST["width"];
		}

		if (empty($_POST["height"])) {
			$height = 0;
		} else {
			$height = $_POST["height"];
		}

		if (empty($_POST["creation"])) {
			$creation = null;
		} else {
			$creation = $_POST["creation"];
		}

		if (empty($_POST["technic"])) {
			$technic = null;
		} else {
			$technic = $_POST["technic"];
		}

		if (empty($_POST["price"])) {
			$price = null;
		} else {
			$price = $_POST["price"];
		}

		if (empty($_POST["theme"])) {
			$theme = null;
		} else {
			$theme = $_POST["theme"];
		}

		if (empty($_POST["category"])) {
			$category = null;
		} else {
			$category = $_POST["category"];
		}

		if (empty($_POST["sold"])) {
			$sold = false;
		} else {
			$sold = $_POST["sold"];
		}

		if (empty($_POST["published"])) {
			$published = null;
		} else {
			$published = $_POST["published"];
		}
		
		$paintingManager = new \jmd\models\managers\PaintingManager();
		$resp = $paintingManager->addPainting($title, $width, $height, $img_id, $creation, $technic, $price, $theme, $category, $sold, $published);

		header("location:index.php?action=adminPaintings");
	}

	public function displayModify($id) {
		$paintingManager = new \jmd\models\managers\PaintingManager();
		$painting = $paintingManager->getOnePainting($id);
		$date = $painting->getCreation();
		$tmp = explode("-", $date);
		$newDate = $tmp[1]. "/" .$tmp[0];
		

		$action = $_GET["action"];

		$twig = \jmd\views\Twig::initTwig("src/views/backoffice/");
		
		echo $twig->render('contentModifyPainting.twig', [
			"painting" => $painting,
			"action" => $action,
			"date" => $newDate]);
	}

	public function updatePainting() {

		$id = $_GET["id"];

		$title;
		$width;
		$height;
		$creation;
		$technic;
		$price;
		$theme;
		$category;
		$sold;
		$published;

		if (empty($_POST["title"])) {
			$title = null;
		} else {
			$title = $_POST["title"];
		}

		if (empty($_POST["width"])) {
			$width = 0;
		} else {
			$width = $_POST["width"];
		}

		if (empty($_POST["height"])) {
			$height = 0;
		} else {
			$height = $_POST["height"];
		}

		if (empty($_POST["creation"])) {
			$creation = null;
		} else {
			$tmp = explode("/", $_POST["creation"]);
			$year = $tmp[1];
			$month = $tmp[0];
			$creation = $year. "-" .$month;
		}

		if (empty($_POST["technic"])) {
			$technic = null;
		} else {
			$technic = $_POST["technic"];
		}

		if (empty($_POST["price"])) {
			$price = null;
		} else {
			$price = $_POST["price"];
		}

		if (empty($_POST["theme"])) {
			$theme = null;
		} else {
			$theme = $_POST["theme"];
		}

		if (empty($_POST["category"])) {
			$category = null;
		} else {
			$category = $_POST["category"];
		}

		if (empty($_POST["sold"])) {
			$sold = 0;
		} else {
			$sold = $_POST["sold"];
		}

		if (empty($_POST["published"])) {
			$published = 0;
		} else {
			$published = $_POST["published"];
		}

		$paintingManager = new \jmd\models\managers\PaintingManager();
		$painting = $paintingManager->updateOnePainting($id, $title, $width, $height, $creation, $technic, $price, $theme, $category, $sold, $published);

		header("location:index.php?action=adminPaintings");
	}

	public function updatePublicationStatus($id) {
		$paintingManager = new \jmd\models\managers\PaintingManager();
		$resp = $paintingManager->publish($id);

		header("location:index.php?action=adminPaintings");
	}

	public function deletePainting($paintingId) {
		$paintingManager = new \jmd\models\managers\PaintingManager();
		$imgManager = new \jmd\models\managers\ImgManager();

		$painting = $paintingManager->getOnePainting($paintingId);

		$img_id = $painting->getImg_id();

		$paintingManager->delete($paintingId);
		$imgManager->delete($img_id);

		$page = $this->pageNumber;

		header("location:index.php?action=adminPaintings&page=$page");
	}

}

		