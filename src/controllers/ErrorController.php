<?php 

namespace jmd\controllers;


class ErrorController {

	private $errMsg;
	
	function __construct($msg){
		$this->errMsg = $msg;
		$this->renderErr();
	}

	/**
	 * [Display the page with error message cautgh by exceptions]
	 */
	public function renderErr() {
		$msg = $this->errMsg;

		$twig = Twig::initTwig("src/views/");

		echo $twig->render('errorMessage.twig', [
			"msg" => $msg]);
	}
}