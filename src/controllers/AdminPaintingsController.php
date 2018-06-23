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

		$pages = ceil($count->getCount() / $this->paintingsPerPage);

		return $pages;
	}

	public function renderPaintingsAdmin() {
		$firstIndex = ($this->pageNumber - 1) * $this->paintingsPerPage;

		$paintingManager = new \jmd\models\managers\PaintingManager();
		$paintings = $paintingManager->getPaintings($firstIndex, $this->paintingsPerPage);

		$action = $_GET["action"];

		$pageNumber = $this->pageNumber;

		$numberOfPages = $this->getPageCount();

		$twig = \jmd\models\Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentAdminPaintings.twig', [
			"paintings" => $paintings,
			"action" => $action,
			"pageNumber" => $pageNumber,
			"numberOfPages" => $numberOfPages]);
	}

	public function renderAddPainting() {

		$action = $_GET["action"];

		$twig = \jmd\models\Twig::initTwig("src/views/backoffice/");

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

		 				$url = "http://localhost:8888/jmd/assets/img/paintings/" .$fileNameNew;

		 				$action = $_GET["action"];
		 				
		 				if ( isset($_GET["img"])) {
		 					$img = $_GET["img"];
		 				} else {
		 					$img = null;
		 				}

						$twig = \jmd\models\Twig::initTwig("src/views/backoffice/");

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

		header("location:index.php?action=addPainting");	
	}
		
	public function addImg() {

		$url = "http://localhost:8888/jmd/assets/img/paintings/" .$_GET["name"];

		$imgManager = new \jmd\models\managers\ImgManager();
		$imgManager->insertImg($url);

		$img = $imgManager->getPaintingId($url);
		$id = $img->getId();

		return $id;		
	}


	public function addPaintings() {
	
		//récupérer le titre, dim, prix etc
		//Verifier la validité des données
		//ajouter le tout en base de donner
		//
		
	}

	public function newPainting() {

		$img_id = $this->addImg();
		$title = $_POST["title"];
		$width = $_POST["width"];
		$height = $_POST["height"];
		$creation = $_POST["creation"];
		$technic = $_POST["technic"];
		$price = $_POST["price"];
		$sold = $_POST["sold"];
		$published = $_POST["published"];
		$theme = $_POST["theme"];
		$category = ($_POST["category"]);

		$paintingManager = new \jmd\models\managers\PaintingManager();
		$resp = $paintingManager->addPainting($title, $width, $height, $img_id, $creation, $technic, $price, $theme, $category, $sold, $published);

		header("location:index.php?action=adminPaintings");
	}	
}

		