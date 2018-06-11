<?php 

session_start();

require 'vendor/autoload.php'; //autoload à configurer

require_once('controllers/Routeur.php');

	if (isset($_GET["action"])) { 

		$request = $_GET["action"];

	} else {
		$request = "";
	}

$routeur = new Routeur($request);
$routeur->renderController();