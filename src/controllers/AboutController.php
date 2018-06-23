<?php 

namespace jmd\controllers;

/**
 * 
 */
class AboutController extends Controller {
	
	public function render() {
		
		$twig = \jmd\models\Twig::initTwig("src/views/");

		echo $twig->render('aboutTemplate.twig');
	}
}