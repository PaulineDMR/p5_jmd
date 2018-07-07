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
				$aboutController = new AboutController(null, null, null, null);
				$aboutController->render();
			}

			elseif ($request == "blog") {
				$blogController = new BlogController();
	
				if (isset($_GET["choice"]) && $_GET["choice"] == "report") {
					if (isset($_GET["commentId"]) && is_numeric($_GET["commentId"])) {
						if (isset($_GET["postId"]) && is_numeric($_GET["postId"])) {
							$blogController->reportComment($_GET["commentId"], $_GET["postId"]);
						}
					}
				} elseif (isset($_GET["postId"]) && is_numeric($_GET["postId"])) {
					$blogController->renderOnePost($_GET["postId"]);
				} else {
					$blogController->renderHomeBlog();
				}
			}

			elseif ($request == "addComment") {
				$blogController = new BlogController();

				if (isset($_GET["id"]) && is_numeric($_GET["id"]) && $_GET["id"] > 0) {
					$blogController->newComment($_GET["id"], $_POST["name"], $_POST["comment"]);

				} else {
					throw new \Exception("L'id de l'article est incorrect", 1);
				}
			}

			elseif ($request == "contactMe") {
				$aboutController = new AboutController("smtp.pdmrweb.com", "pauline@pdmrweb.com", "pauline.desmares@aliceadsl.fr", "6LeCJWIUAAAAAD2ZM5C4Vdht4m6Xo7EGKa754iHX");
				$aboutController->contactMe($_POST["g-recaptcha-response"]);
			}

			elseif ($request == "login") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$adminController = new AdminController();
					$adminController->renderMainAdmin();
				} else {
					$loginController = new LoginController();
					$loginController->login();
				}
			}

			elseif ($request == "auth") {
				$loginController = new LoginController();
				$loginController->authentification();
			}

			elseif ($request == "mainAdmin") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$adminController = new AdminController();
					$adminController->renderMainAdmin();
				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
			}


			elseif ($request == "adminPaintings") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$paintingsAdminController = new AdminPaintingsController();
					if (isset($_GET["choice"]) && $_GET["choice"] == "deletePainting") {
						if (isset($_GET["pId"]) && is_numeric($_GET["pId"])) {
							$paintingsAdminController->deletePainting($_GET["pId"]);
						}	
					} else {
						$paintingsAdminController->renderPaintingsAdmin();
					}
				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
			}

			elseif ($request == "addPainting") { 
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
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
				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
			}

			elseif ($request == "modifyPainting") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$paintingsAdminController = new AdminPaintingsController();
					if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
						$paintingsAdminController->displayModify($_GET["id"]);
					} else {
						$paintingsAdminController->renderPaintingsAdmin();
					}
				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
			}

			elseif ($request == "updatePainting") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$paintingsAdminController = new AdminPaintingsController();
					if (isset($_GET["id"]) && is_numeric($_GET["id"])) {	
						$paintingsAdminController->updatePainting();
					} else {
						$paintingsAdminController->renderPaintingsAdmin();
					}
				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
			}
			
			elseif ($request == "publishPainting") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$paintingsAdminController = new AdminPaintingsController();
					if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
						$paintingsAdminController->updatePublicationStatus($_GET["id"]);
					} else {
					$paintingsAdminController->renderPaintingsAdmin();
					}
				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
			}

			elseif ($request == "adminPosts") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$adminPostsController = new AdminPostsController();

					if (isset($_GET["choice"]) && $_GET["choice"] == "write") {
						//
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "modify") {
						$adminPostsController->renderModifyPost($_GET["id"]);
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "publish") {
						$adminPostsController->publish($_GET["id"], $_GET["publish"]);
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "noPublish") {
						$adminPostsController->NonPublish($_GET["id"], $_GET["publish"]);
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "writePost") {
						$adminPostsController->createNewPost();
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "deletePost") {
						if (isset($_GET["postId"]) && is_numeric($_GET["postId"])) {
							$adminPostsController->delete($_GET["postId"]);
						}
					} else {
						$adminPostsController->renderPostsAdmin();
					}

				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
				
			}

			elseif ($request == "updatePost") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$adminPostsController = new AdminPostsController();

					if (isset($_GET["choice"]) && $_GET["choice"] == "deleteCat") {
						$adminPostsController->deleteCat($_GET["id"], $_GET["cat"]);

					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "addCat") {
						$adminPostsController->addCat($_GET["id"]);

					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "addImg") {
						$adminPostsController->addImg($_GET["id"]);

					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "deleteImg") {
						$adminPostsController->deleteImg($_GET["file"], $_GET["id"]);

					} else {
						$adminPostsController->modifyPost($_GET["id"], $_POST["title"], $_POST["content"]);
					}

				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}
				
			}

			elseif ($request == "adminComments") {
				if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
					$adminCommentsController = new AdminCommentsController();

					if (isset($_GET["choice"]) && $_GET["choice"] == "validate") {
						if (isset($_GET["commentId"]) && is_numeric($_GET["commentId"])) {
							$adminCommentsController->commentValidation($_GET["commentId"]);
						}
					} elseif (isset($_GET["choice"]) && $_GET["choice"] == "delete") {
						if (isset($_GET["commentId"]) && is_numeric($_GET["commentId"])) {	
							$adminCommentsController->commentDeletion($_GET["commentId"]);
						}
					} else {
						$adminCommentsController->renderAdminComments();
					}


				} else {
						throw new \Exception("Vous n'avez pas l'autorisation d'accès");
				}

			}

			elseif ($request == "logout") {
					session_unset();
					session_destroy();
					header('location: index.php');

			} else { // si aucune "action" dans l'url -> que dois afficher la page d'accueil?
				$homeController = new HomeController();
				$homeController->displayHome();
			}
		}
		catch(\Exception $e) {

			$errorMessage = $e->getMessage();
    		$errorController = new \jmd\controllers\ErrorController($errorMessage);
		}
	}
}