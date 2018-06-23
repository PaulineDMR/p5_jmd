<?php 

namespace jmd\controllers;


/**
 * 
 */
class LoginController extends Controller {
	
	private $errorLoginMsg = "";

	public function __construct() {
		parent::__construct();
		if (array_key_exists("authentification", $_SESSION) && !$_SESSION["authentification"]) {
			$this->errorLoginMsg = "Votre pseudo ou votre de mot de passe est incorrect. A nouveau saississez vos identifiants.";
		} else {
			$this->errorLoginMsg = "";
		}
	}

	public function login() {
		$msg = $this->errorLoginMsg;
		
		$twig = \jmd\models\Twig::initTwig("src/views/");

		echo $twig->render('loginTemplate.twig', [
			"msg" => $msg]);	
	}

	public function authentification() {

		if (!empty($_POST["pseudo"]) && !empty($_POST["mdp"])) {
			$admins = $this->adminManager->getAdmins();
			
			foreach ($admins as $value) {

				if ($value->getLogin() == $_POST["pseudo"] AND password_verify($_POST["mdp"], $value->getPwd())) {
					
					$_SESSION["userId"] = $value->getId();
					
					$_SESSION["authentification"] = TRUE;

					header("Location: index.php?action=homeAdmin");

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
