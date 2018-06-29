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
				$paintingsAdminController = new AdminPaintingsController();
				if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
					$paintingsAdminController->displayModify($_GET["id"]);
				} else {
					$paintingsAdminController->renderPaintingsAdmin();
				}
			}

			elseif ($request == "updatePainting") {
				$paintingsAdminController = new AdminPaintingsController();
				if (isset($_GET["id"]) && is_numeric($_GET["id"])) {	
					$paintingsAdminController->updatePainting();
				} else {
					$paintingsAdminController->renderPaintingsAdmin();
				}
			}
			
			elseif ($request == "publishPainting") {
				$paintingsAdminController = new AdminPaintingsController();
				if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
					$paintingsAdminController->updatePublicationStatus($_GET["id"]);
				} else {
					$paintingsAdminController->renderPaintingsAdmin();
				}
			}

			elseif ($request == "deletePainting") {
				$paintingsAdminController = new AdminPaintingsController();
				if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
					$paintingsAdminController->deletePainting($_GET["id"]);
				} else {
					$paintingsAdminController->renderPaintingsAdmin();
				}
			}

			elseif ($request == "adminPosts") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$adminPostsController = new \jmd\controllers\AdminPostsController();

					if (isset($_GET["choice"]) && $_GET["choice"] == "write") {
						//
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "modify") {
						$adminPostsController->renderModifyPost($_GET["id"]);
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "publish") {
						$adminPostsController->publish($_GET["id"], $_GET["publish"]);
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "noPublish") {
						$adminPostsController->NonPublish($_GET["id"], $_GET["publish"]);
					} else {
						$adminPostsController->renderPostsAdmin();
					}

				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
				
			}

			elseif ($request == "updatePost") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$adminPostsController = new \jmd\controllers\AdminPostsController();

					if (isset($_GET["choice"]) && $_GET["choice"] == "deleteCat") {
						$adminPostsController->deleteCat($_GET["id"], $_GET["cat"]);

					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "addCat") {
						$adminPostsController->addCat($_GET["id"]);

					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "addImg") {
						$adminPostsController->addImg($_GET["id"]);

					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "deleteImg") {
						$adminPostsController->deleteImg($_GET["file"], $_GET["id"]);

					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "deletePost") {
						$adminPostsController->delete($_GET["id"]);

					} else {
						$adminPostsController->modifyPost($_GET["id"], $_POST["title"], $_POST["content"]);
					}

				} else {
						throw new Exception("Vous n'avez pas l'autorisation d'accès");
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