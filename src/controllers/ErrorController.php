<?php 

namespace jmd\controllers;


/**
 * 
 */
class ErrorController {

	private $errMsg;
	
	function __construct($msg){
		$this->errMsg = $msg;
		$this->renderErr();
	}

	public function renderErr() {
		$msg = $this->errMsg;

		$twig = \jmd\views\Twig::initTwig("src/views/");

		echo $twig->render('errorMessage.twig', [
			"msg" => $msg]);
	}
}