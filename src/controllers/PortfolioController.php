<?php 

namespace jmd\controllers;

class PortfolioController extends Controller {
	

	public function groupPaintings($paintings, $group) {
		$paintingGroup = [];

		$ix = $group-1;

		for ($i = $ix; $i < count($paintings); $i = $i + 3) {
			$paintingGroup[] = $paintings[$i];
		}

		return $paintingGroup;
	}

	public function render() {
		$paintings = $this->paintingManager->GetAllPaintings();

		$group1 = $this->groupPaintings($paintings, $group = 1);
		$group2 = $this->groupPaintings($paintings, $group = 2);
		$group3 = $this->groupPaintings($paintings, $group = 3);

		$twig = \jmd\models\Twig::initTwig("src/views/");

		echo $twig->render("portfolioContent.twig",
			[
			"paintings" => $paintings,
			"col1" => $group1,
			"col2" => $group2,
			"col3" => $group3
			]);

	}

}