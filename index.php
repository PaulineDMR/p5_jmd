<?php 

session_start();

require_once('models/class/Routeur.php');

	if (isset($_GET["action"])) { 

		$request = $_GET["action"];

	} else {
		$request = "";
	}

$routeur = new Routeur($request);
$routeur->renderController();