<?php 

namespace jmd\controllers;

class PortfolioController {
    

    public function groupPaintings($paintings, $group) {
        $paintingGroup = [];

        $ix = $group-1;

        for ($i = $ix; $i < count($paintings); $i = $i + 3) {
            $paintingGroup[] = $paintings[$i];
        }

        return $paintingGroup;
    }

    public function render() {
        $paintingManager = new \jmd\models\managers\PaintingManager();
        $paintings;
        $categoryList = ["paysage", "scene", "abstrait", "nature-morte"];

        if (isset($_GET["category"]) && in_array($_GET["category"], $categoryList)) {
            $paintings = $paintingManager->getPaintingsBycategory($_GET["category"]);
        } else {
            $paintings = $paintingManager->getAllPaintings();
        }

        foreach ($paintings as $value) {
            $date = $value->getCreation();
            if ($date != null) {
                $newDate = new \DateTime($date);
                $frenchDate = $newDate->format("m-Y");
                $value->setCreation($frenchDate);
            }
        }


        $group1 = $this->groupPaintings($paintings, $group = 1);
        $group2 = $this->groupPaintings($paintings, $group = 2);
        $group3 = $this->groupPaintings($paintings, $group = 3);

        $twig = \jmd\views\Twig::initTwig("src/views/");

        echo $twig->render("portfolioContent.twig",
            [
            "paintings" => $paintings,
            "col1" => $group1,
            "col2" => $group2,
            "col3" => $group3
            ]);

    }

}