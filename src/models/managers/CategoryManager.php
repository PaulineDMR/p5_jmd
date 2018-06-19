<?php 

namespace jmd\models\managers;


/**
 * 
 */
class CategoryManager extends Manager {
	
	
	public function getCountPostByCat() {
		
		$db = $this->dbConnect();
		$req = $db->query("SELECT COUNT(post_id) AS post_id, name  FROM cat_post cp JOIN categories c ON cp.cat_id = c.id JOIN posts p ON cp.post_id = p.id WHERE published = TRUE GROUP BY name");

		/*$req = $db->query("SELECT name, COUNT(post_id) FROM categories c JOIN cat_post cp ON cp.cat_id = c.id JOIN posts p ON cp.post_id = p.id GROUP BY name WHERE published = TRUE");*/

		$categories = array();

		while ($data = $req->fetch()) {
			$category = new Category();
			$category->hydrate($data);
			$categories[] = $category;

		}

		$req->closeCursor();

		return $categories;
	}

	public function getCategoryList()
	{
		$db = $this->dbConnect();
		$req = $db->query("SELECT name FROM categories GROUP BY name");

		$categories = array();

		while ($data = $req->fetch()) {
			$category = new Category();
			$category->hydrate($data);
			$categories[] = $category;
		}

		$req->closeCursor();
		return $categories;
	}

}


