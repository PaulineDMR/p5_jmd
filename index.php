<?php 

session_start();

require 'vendor/autoload.php';

use jmd\controllers\Routeur;


	if (isset($_GET["action"])) { 

		$request = $_GET["action"];

	} else {
		$request = "";
	}

$routeur = new Routeur($request);
$routeur->renderController();