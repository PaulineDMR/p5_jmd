<?php 

namespace jmd\models\managers;

class PaintingManager extends Manager {

	// CREATE
	
	public function addPainting($title, $width, $height, $img_id, $creation, $technic, $price, $theme, $category, $sold, $published) 	 {
	 	$db = $this->dbConnect();
		$req = $db->prepare("INSERT INTO paintings (title, img_id, width, height, creation, technic, price, theme, category, sold, published) VALUES (:title, :img_id, :width, :height, :creation, :technic, :price, :theme, :category, :sold, :published)");
		$req->bindValue("title", $title, \PDO::PARAM_STR);
		$req->bindValue("width", $width, \PDO::PARAM_INT);
		$req->bindValue("height", $height, \PDO::PARAM_INT);
		$req->bindValue("img_id", $img_id, \PDO::PARAM_INT);
		$req->bindValue("creation", $creation, \PDO::PARAM_INT);
		$req->bindValue("technic", $technic, \PDO::PARAM_STR);
		$req->bindValue("price", $price, \PDO::PARAM_INT);
		$req->bindValue("theme", $theme, \PDO::PARAM_STR);
		$req->bindValue("category", $category, \PDO::PARAM_STR);
		$req->bindValue("sold", $sold, \PDO::PARAM_INT);
		$req->bindValue("published", $published, \PDO::PARAM_INT);

		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible d'ajouter le tableau à la base de donnée", 1);
		} else {
			return $resp;
		}
	}
		
	
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

	public function getPaintings($firstIndex, $paintingsPerPage) {
		$db = $this->dbConnect();
		$req = $db->prepare("SELECT p.id AS id, title, width, height, price, sold, theme, technic, creation, url, published FROM paintings p JOIN img i ON i.id = p.img_id ORDER BY creation DESC LIMIT :firstIndex, :paintingsPerPage");

		$req->bindValue('firstIndex', $firstIndex, \PDO::PARAM_INT);
		$req->bindValue('paintingsPerPage', $paintingsPerPage, \PDO::PARAM_INT);
		$req->execute();

		$paintings = array();

		while ($data = $req->fetch()) {
			$painting = new \jmd\models\entities\Painting();
			$painting->hydrate($data);
			$paintings[] = $painting;
		}

		return $paintings;
	}

	public function getAllPaintings() {
		$db = $this->dbConnect();
		$req = $db->query("SELECT p.id as id, title, width, height, price, sold, theme, technic, creation, url, published FROM paintings p JOIN img i ON i.id = p.img_id ORDER BY creation DESC");

		$paintings = array();

		while ($data = $req->fetch()) {
			$painting = new \jmd\models\entities\Painting();
			$painting->hydrate($data);
			$paintings[] = $painting;
		}

		$req->closeCursor();

		return $paintings;
	}

	public function countPaintings() {
		$db = $this->dbConnect();
		$req = $db->query("SELECT COUNT(id) AS count FROM paintings");

		$data = $req->fetch();
		$painting = new \jmd\models\entities\Painting();
		$painting->hydrate($data);

		$req->closeCursor();
		return $painting;

	}

	public function getOnePainting($id) {
		$db = $this->dbConnect();
		$req = $db->prepare("SELECT p.id as id, title, width, height, technic, creation, price, theme, category, sold, published, url FROM paintings p JOIN img i ON p.img_id = i.id WHERE p.id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$req->execute();

		$data = $req->fetch();
		$painting = new \jmd\models\entities\Painting();
		$painting->hydrate($data);

		return $painting;
	}

	
	// UPDATE
	
	public function updateOnePainting($id, $title, $width, $height, $creation, $technic, $price, $theme, $category, $sold, $published) {
		$db = $this->dbConnect();
		$req = $db->prepare("UPDATE paintings SET title = :title, width = :width, height = :height, technic = :technic, creation = :creation, price = :price, theme = :theme, category = :category, sold = :sold, published = :published WHERE id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$req->bindValue("title", $title, \PDO::PARAM_STR);
		$req->bindValue("width", $width, \PDO::PARAM_INT);
		$req->bindValue("height", $height, \PDO::PARAM_INT);
		$req->bindValue("creation", $creation, \PDO::PARAM_INT);
		$req->bindValue("technic", $technic, \PDO::PARAM_STR);
		$req->bindValue("price", $price, \PDO::PARAM_INT);
		$req->bindValue("theme", $theme, \PDO::PARAM_STR);
		$req->bindValue("category", $category, \PDO::PARAM_STR);
		$req->bindValue("sold", $sold, \PDO::PARAM_STR);
		$req->bindValue("published", $published, \PDO::PARAM_STR);
		
		$req->execute();

		if ($req === false) {
			throw new \Exception("Impossible de mettre à jour les informations dans la base de données", 1);
		} else {
			return $req;
		}

		
	}
	

	// DELETE
	 
	
	 
	 
}



