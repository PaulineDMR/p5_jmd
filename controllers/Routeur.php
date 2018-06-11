<?php 

require_once('controllers/HomeController.php');
 //namespace pour l'autoload

class Routeur {

	private $request;

	public function __construct($request) {
		$this->request = $request;
	}

	public function renderController() {

		$request = $this->request;
 
		//... Création de tous les objets nécessaires
		$homeController = new HomeController();

		//Est-il possible de créer une fonction qui instancie tous les objets automatiquement?
		

		try {

			// Display the list of posts on the frontend view 
			if ($request == "home") {
				$homeController->displayHome();
			}

			// Display one post with his comments on the frontend post view
			/*elseif ($request == "portfolio") { // EXEMPLE
				if (isset($_GET["id"]) && $_GET["id"] > 0) {
				$postCommentsController->postComments();
				}
				else {
					throw new Exception('Aucun identifiant de billet envoyé');
				}
			}*/

			// ...

			else { // si aucune "action" dans l'url -> que dois afficher la page d'accueil?
				$homeController->displayHome();
			}
		}
		catch(Exception $e) {
			echo 'Erreur: ' . $e->getMessage();
		}
	}
}