<?php 

namespace jmd\controllers;

class MentionsLegalesController
{
	
	/**
	 * [Set mentions légales page]
	 */
	public function renderMentions()
	{
		$twig = Twig::initTwig("src/views/");

		echo $twig->render('mentionsLegales.twig');
	}
}