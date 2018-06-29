<?php 

ini_set("display_errors", 1);

error_reporting(E_ALL);

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use jmd\controllers\Routeur;

require 'vendor/autoload.php';


	if (isset($_GET["action"])) { 

		$request = $_GET["action"];

	} else {
		$request = "";
	}

$routeur = new Routeur($request);
$routeur->renderController();