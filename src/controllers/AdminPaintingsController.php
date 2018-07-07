<?php 

namespace jmd\controllers;

use jmd\models\managers\PaintingManager;
use jmd\models\managers\ImgManager;

/**
 * 
 */
class AdminPaintingsController {
	
	private $pageNumber;
	private $paintingsPerPage = 8;

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
	 * [get how many pages of paintings por pagination]
	 * @return [int] [how many pages]
	 */
	public function getPageCount()
	{
		$paintingManager = new PaintingManager();
		$count = $paintingManager->countPaintings();

		if(($count->getCount() % $this->paintingsPerPage) == 0) {
			$pages = $count->getCount() / $this->paintingsPerPage;
		} else {
			$pages = ceil($count->getCount() / $this->paintingsPerPage);
		}
		return $pages;
	}

	/**
	 * [Get data for displaying the view og paintings admin]
	 */
	public function renderPaintingsAdmin()
	{
		$numberOfPages = $this->getPageCount();
		if ($this->pageNumber > $numberOfPages) {
			$this->pageNumber = $numberOfPages;
		}

		$firstIndex = ($this->pageNumber - 1) * $this->paintingsPerPage;

		$paintingManager = new PaintingManager();
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

		$twig = Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentAdminPaintings.twig', [
			"paintings" => $paintings,
			"action" => $action,
			"pageNumber" => $pageNumber,
			"numberOfPages" => $numberOfPages]);
	}

	/**
	 * [Display the page to add a new painting]
	 */
	public function renderAddPainting()
	{
		$action = $_GET["action"];

		$twig = Twig::initTwig("src/views/backoffice/");

		echo $twig->render('contentAddPainting.twig', ["action" => $action]);
	}

	/**
	 * [get datas from the input type file, to upload a img file]
	 */
	public function upload()
	{
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

		 				$url = "assets/img/paintings/" .$fileNameNew;

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

	/**
	 * [delete an img file from the directory]
	 */
	public function delete()
	{
		$path = "assets/img/paintings/" .$_GET["name"];

		$open = opendir("assets/img/paintings/");

		unlink($path);

		closedir($open);

		header("location:index.php?action=addPainting");	
	}
	
	/**
	 * [set URL and img name to add a new entry in the img table]
	 * @return [int] [id of the img added in DB]
	 */
	public function addImg()
	{
		$url = "assets/img/paintings/" .$_GET["name"];

		$imgManager = new ImgManager();
		$imgManager->newImg($url, $_GET["name"]);

		$img = $imgManager->getPaintingId($url);
		$id = $img->getId();

		return $id;		
	}

	/**
	 * [function to check the inputs from the painting Form]
	 * @param  [string] $input  [trigger which input]
	 * @param  [mixed] $return [value returned if input is empty]
	 * @return [mixed]         [value of the input]
	 */
	public function checkInput($input, $return)
	{
		if (empty($_POST[$input])) {
			return $return;
		} else {
			return $_POST[$input];
		}
	}

	/**
	 * [Get tested inputs from the form and add the new painting datas in DB]
	 */
	public function newPainting()
	{
		$img_id = $this->addImg();

		$title = $this->checkInput("title", null);
		$width = $this->checkInput("width", 0);
		$height = $this->checkInput("height", 0);
		$creation;
		$technic = $this->checkInput("technic", null);
		$price = $this->checkInput("price", null);
		$theme = $this->checkInput("theme", null);
		$category = $this->checkInput("category", null);
		$sold = $this->checkInput("sold", false);
		$published = $this->checkInput("published", true);

		if (empty($_POST["creation"])) {
			$creation = null;
		} else {
			$tmp = explode("/", $_POST["creation"]);
			$creation = $tmp[1]. "-" .$tmp[0];
		}
		
		$paintingManager = new PaintingManager();
		$paintingManager->addPainting($title, $width, $height, $img_id, $creation, $technic, $price, $theme, $category, $sold, $published);

		header("location:index.php?action=adminPaintings");
	}

	/**
	 * [Display the view to modify a painting]
	 * @param  [in] $id [id of the painting to modify]
	 */
	public function displayModify($id)
	{
		$paintingManager = new PaintingManager();
		$painting = $paintingManager->getOnePainting($id);
		$date = $painting->getCreation();
		$tmp = explode("-", $date);
		$newDate = $tmp[1]. "/" .$tmp[0];
		

		$action = $_GET["action"];

		$twig = Twig::initTwig("src/views/backoffice/");
		
		echo $twig->render('contentModifyPainting.twig', [
			"painting" => $painting,
			"action" => $action,
			"date" => $newDate]);
	}

	/**
	 * [Send modified painting datas to DB]
	 */
	public function updatePainting()
	{
		$id = $_GET["id"];

		$title = $this->checkInput("title", null);
		$width = $this->checkInput("width", 0);
		$height = $this->checkInput("height", 0);
		$creation;
		$technic = $this->checkInput("technic", null);
		$price = $this->checkInput("price", null);
		$theme = $this->checkInput("theme", null);
		$category = $this->checkInput("category", null);
		$sold = $this->checkInput("sold", false);
		$published = $this->checkInput("published", true);

		if (empty($_POST["creation"])) {
			$creation = null;
		} else {
			$tmp = explode("/", $_POST["creation"]);
			$creation = $tmp[1]. "-" .$tmp[0];
		}

		$paintingManager = new PaintingManager();
		$painting = $paintingManager->updateOnePainting($id, $title, $width, $height, $creation, $technic, $price, $theme, $category, $sold, $published);

		header("location:index.php?action=adminPaintings");
	}

	/**
	 * [Change the publication status of the paiting to true in DB]
	 * @param  [int] $id [id of the painting to update]
	 * @return [bool]     [DB response : succes or fail]
	 */
	public function updatePublicationStatus($id)
	{
		$paintingManager = new PaintingManager();
		$resp = $paintingManager->publish($id);

		header("location:index.php?action=adminPaintings");
	}

	/**
	 * [Delete painting in DB]
	 * @param  [int] $paintingId [id of the painting to delete from DB]
	 */
	public function deletePainting($paintingId)
	{
		$paintingManager = new PaintingManager();
		$imgManager = new ImgManager();

		$painting = $paintingManager->getOnePainting($paintingId);

		$img_id = $painting->getImg_id();

		$paintingManager->delete($paintingId);
		$imgManager->delete($img_id);

		$page = $this->pageNumber;

		header("location:index.php?action=adminPaintings&page=$page");
	}

}

		