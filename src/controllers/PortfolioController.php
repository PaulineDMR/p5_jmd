<?php 

namespace jmd\controllers;

use jmd\models\managers\PaintingManager;
use jmd\helpers\FrenchDate;

class PortfolioController {
    
    /**
     * [Divise a array of painting in group of column]
     * @param  [array] $paintings [list aof object painting]
     * @param  [int] $group     [group number (from 1)]
     * @return [array]            [list of paiting for this group]
     */
    public function groupPaintings($paintings, $group) {
        $paintingGroup = [];

        $ix = $group-1;

        for ($i = $ix; $i < count($paintings); $i = $i + 3) {
            $paintingGroup[] = $paintings[$i];
        }

        return $paintingGroup;
    }

    /**
     * [Set the datas for portfolio page]
     */
    public function render() {
        $paintingManager = new PaintingManager();
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

        $twig = Twig::initTwig("src/views/");

        echo $twig->render("portfolioContent.twig",
            [
            "paintings" => $paintings,
            "col1" => $group1,
            "col2" => $group2,
            "col3" => $group3
            ]);
    }

}