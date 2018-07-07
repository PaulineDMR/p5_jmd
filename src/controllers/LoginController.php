<?php 

namespace jmd\controllers;

use jmd\models\managers\AdminManager;


class LoginController {
	
	private $errorLoginMsg = "";

	/**
	 * [Check if an error message exists for the login page to display]
	 */
	public function __construct() {
		if (array_key_exists("authentification", $_SESSION) && !$_SESSION["authentification"]) {
			$this->errorLoginMsg = "Votre pseudo ou votre de mot de passe est incorrect. A nouveau saississez vos identifiants.";
		} else {
			$this->errorLoginMsg = "";
		}
	}

	/**
	 * [Display the login page]
	 */
	public function login() {
		$msg = $this->errorLoginMsg;
		
		$twig = Twig::initTwig("src/views/");

		echo $twig->render('loginTemplate.twig', [
			"msg" => $msg]);	
	}

	/**
	 * [Check if there was an authentification already in the same SESSION]
	 */
	public function checkAuthentification()
	{
		if (array_key_exists("authentification" , $_SESSION) && $_SESSION["authentification"]) {
			return true;
		} else {
			throw new \Exception("Vous n'avez pas l'autorisation d'accès");
		}
	}

	/**
	 * [Compare user input to the DB datas]
	 * [create a SESSION variable]
	 * [or set an error login message]
	 */
	public function authentification()
	{

		if (!empty($_POST["pseudo"]) && !empty($_POST["mdp"])) {
			$adminManager = new AdminManager();
			$admins = $adminManager->getAdmins();
			
			foreach ($admins as $value) {

				if ($value->getLogin() == $_POST["pseudo"] AND password_verify($_POST["mdp"], $value->getPwd())) {
					
					$_SESSION["userId"] = $value->getId();
					
					$_SESSION["authentification"] = TRUE;

					header("Location: index.php?action=mainAdmin");

				} else {
					$_SESSION["authentification"] = false;
					
					header("Location: index.php?action=login");
				}
			}

		} else {
			$this->errorLoginMsg = "Veuillez renseigner tous les champs. A nouveau saisissez votre identifiant et votre mot de passe.";

			$this->login();
		}
	
	}
}
