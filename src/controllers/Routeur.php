<?php 

namespace jmd\controllers;


class Routeur {

	private $request;

	public function __construct($request) {
		$this->request = $request;
	}

	public function renderController() {

		$request = $this->request;
		
		try {

			// Home page 
			if ($request == "home") {
				$homeController = new HomeController();
				$homeController->displayHome();
			}

			// Portfolio page
			elseif ($request == "portfolio") {
				$portfolioController = new PortfolioController();
		 		$portfolioController->render();
			}

			// About page 
			elseif ($request == "about") {
				$aboutController = new AboutController();
				$aboutController->render();
			}

			elseif ($request == "blog") {
				$blogController = new BlogController();

				if (isset($_GET["postId"]) && is_numeric($_GET["postId"])) {
					$blogController->renderOnePost();

				} else {
					$blogController->renderHomeBlog();
				}
			}

			elseif ($request == "contactMe") {
				$sendMailController = new SendMailController();
				$sendMailController->contactMe();
			}

			elseif ($request == "login") {
				$loginController = new LoginController();
				$loginController->login();
			}

			elseif ($request == "auth") {
				$loginController = new LoginController();
				$loginController->authentification();
			}

			elseif ($request == "mainAdmin") {
				$adminController = new AdminController();
				$adminController->renderMainAdmin();
			}


			elseif ($request == "adminPaintings") {
				$paintingsAdminController = new AdminPaintingsController();
				$paintingsAdminController->renderPaintingsAdmin();
			}

			elseif ($request == "addPainting") { //Mettre l'ajout dans la même page que Paintings Admin, genre en dessous de la liste des tableaux
				$paintingsAdminController = new AdminPaintingsController();
				if (isset($_GET["img"]) && $_GET["img"] == "check") {
					$paintingsAdminController->upload();
				} elseif (isset($_GET["img"]) && $_GET["img"] == "delete") {
					$paintingsAdminController->delete();
				} elseif (isset($_GET["img"]) && $_GET["img"] == "record") {
					$paintingsAdminController->newPainting();	
				} else {
					$paintingsAdminController->renderAddPainting();
				}

			}

			elseif ($request == "modifyPainting") {
				if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
					$paintingsAdminController = new AdminPaintingsController();
					$paintingsAdminController->displayModify($_GET["id"]);

				}
			}

			elseif ($request == "updatePainting") {
				if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
					$paintingsAdminController = new AdminPaintingsController();
					$paintingsAdminController->updatePainting();

				}
			}
			
			/* EXEMPLE 
				if (isset($_GET["id"]) && $_GET["id"] > 0) {
				$postCommentsController->postComments();
				}
				else {
					throw new Exception('Aucun identifiant de billet envoyé');
				}*/

			// ...

			else { // si aucune "action" dans l'url -> que dois afficher la page d'accueil?
				$homeController = new HomeController();
				$homeController->displayHome();
			}
		}
		catch(Exception $e) {
			echo 'Erreur: ' . $e->getMessage();
		}
	}
}