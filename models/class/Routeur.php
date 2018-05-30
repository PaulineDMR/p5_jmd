<?php 

require_once('');


class Routeur {

	private $request;

	public function __construct($request) {
		$this->request = $request;
	}

	public function renderController() {

		$request = $this->request;

		//$postsController = new PostsController(); - Exemple - 
		//... Création de tous les objets nécessaires
		
		//Est-il possible de créer une fonction qui instancie tous les objets automatiquement?
		

		try {

			// Display the list of posts on the frontend view 
			if ($request == "listPosts") {
				$postsController->postsList();
			}

			// Display one post with his comments on the frontend post view
			elseif ($request == "post") { // EXEMPLE
				if (isset($_GET["id"]) && $_GET["id"] > 0) {
				$postCommentsController->postComments();
				}
				else {
					throw new Exception('Aucun identifiant de billet envoyé');
				}
			}

			// ...

			else { // si aucune "action" dans l'url -> que dois afficher la page d'accueil?
				$postsController->postsList(); // - Exemple -
			}
		}
		catch(Exception $e) {
			echo 'Erreur: ' . $e->getMessage();
		}
	}
}