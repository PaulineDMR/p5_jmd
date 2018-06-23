<?php 

namespace jmd\models;

class Twig {

	/*public function __construct() {
		self::initTwig();
	}*/

	public static function initTwig($path) {

		$loader = new \Twig_Loader_Filesystem($path);
		$twig = new \Twig_Environment($loader);
		$twig->addExtension(new \Twig_Extensions_Extension_Text());

		return $twig;
	}
	


}