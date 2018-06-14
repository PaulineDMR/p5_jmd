<?php 

namespace jmd\controllers;

use jmd\controllers\HomeController;
use jmd\controllers\PortfolioController;


class Routeur {

	private $request;

	public function __construct($request) {
		$this->request = $request;
	}

	public function renderController() {

		$request = $this->request;
 
		//... Création de tous les objets nécessaires
		$homeController = new HomeController(); // 2
		$portfolioController = new PortfolioController(); //1

		//Est-il possible de créer une fonction qui instancie tous les objets automatiquement?
		

		try {

			// Home page 
			if ($request == "home") {
				$homeController->displayHome();
			}

			// Portfolio page
			elseif ($request == "portfolio") {

				/* EXEMPLE 
				if (isset($_GET["id"]) && $_GET["id"] > 0) {
				$postCommentsController->postComments();
				}
				else {
					throw new Exception('Aucun identifiant de billet envoyé');
				}*/

				$portfolioController->render();

			}

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