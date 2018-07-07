<?php 

namespace jmd\controllers;

class Twig {

	/**
	 * [Initialize Twig]
	 * @param  [string] $path [path to the template twig called]
	 * [return a twig object]
	 */
	public static function initTwig($path)
	{

		$loader = new \Twig_Loader_Filesystem($path);
		$twig = new \Twig_Environment($loader);
		$twig->addExtension(new \Twig_Extensions_Extension_Text());

		return $twig;
	}
}