<?php 

require_once("Manager.php");
require_once("Painting.php");

class PaintingManager extends Manager {

	// CREATE
	
	// READ
	
	public function getLastTenPaintingImg() {
		$db = $this->dbConnect();
		$req = $db->query("SELECT title, technic, url FROM paintings p JOIN img i ON i.id = p.img_id ORDER BY creation DESC LIMIT 10");

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