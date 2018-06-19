<?php 

namespace jmd\models\managers;

class PaintingManager extends Manager {

	// CREATE
	
	// READ
	
	public function getRecentPaintings($max) {
		$db = $this->dbConnect();
		$req = $db->prepare("SELECT title, technic, url FROM paintings p JOIN img i ON i.id = p.img_id ORDER BY creation DESC LIMIT :max");

		$req->bindValue("max", $max, \PDO::PARAM_INT);
		$req->execute();

		$paintings = array();

		while ($data = $req->fetch()) {
			$painting = new \jmd\models\entities\Painting();
			$painting->hydrate($data);
			$paintings[] = $painting;
		}

		$req->closeCursor();

		return $paintings;
	}

	public function getAllPaintings() {
		$db = $this->dbConnect();
		$req = $db->query("SELECT title, width, height, price, sold, theme, technic, creation, url FROM paintings p JOIN img i ON i.id = p.img_id ORDER BY creation DESC");

		$paintings = array();

		while ($data = $req->fetch()) {
			$painting = new Painting();
			$painting->hydrate($data);
			$paintings[] = $painting;
		}

		$req->closeCursor();

		return $paintings;
	}

	
	// UPDATE
	
	// DELETE
}