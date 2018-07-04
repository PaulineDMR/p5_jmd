<?php 

namespace jmd\models\managers;

class PostManager extends Manager {

	// CREATE
	
	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function newPost() {
	 	$db = $this->dbConnect();
		$req = $db->exec("INSERT INTO posts (creation) VALUES (NOW())");

		if ($req === false) {
			throw new \Exception("Impossible d'ajouter le tableau à la base de données", 1);
		} else {
			return $req;
		}
	}
	
	// READ
	
	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function getPosts($firstIndex, $postsPerPage) {
		$db = $this->dbConnect();
		$resp = $db->prepare('
			SELECT p.id AS id, title, p.content AS content, DATE_FORMAT(publication, "%d-%m-%Y") AS publication, published, DATE_FORMAT(p.creation, "%d-%m-%Y") AS creation, COUNT(ct.id) AS countComments
				FROM posts p
				LEFT JOIN comments ct ON p.id = ct.post_id
				GROUP BY p.id
				ORDER BY p.creation
				DESC LIMIT :firstIndex, :postsPerPage');
		
		$resp->bindValue('firstIndex', $firstIndex, \PDO::PARAM_INT);
		$resp->bindValue('postsPerPage', $postsPerPage, \PDO::PARAM_INT);
		$resp->execute();

		$posts = array();

		while ($data = $resp->fetch()) {
			$post = new \jmd\models\entities\Post;
			$post->hydrate($data);
			$posts[] = $post;
		}
		
		$resp->closeCursor();
		return $posts;
	}
	
	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function getRecentPosts($max) {
		$db = $this->dbConnect();
		$req = $db->prepare('
			SELECT p.id AS id, title, p.content AS content, DATE_FORMAT(publication, "%d-%m-%Y") AS publication, COUNT(ct.id) AS countComments
				FROM posts p
				LEFT JOIN comments ct ON p.id = ct.post_id
				WHERE published = TRUE
				GROUP BY p.id ORDER BY publication LIMIT :max');

		$req->bindValue('max', $max, \PDO::PARAM_INT);
		$req->execute();
		
		$posts = array();

		while ($data = $req->fetch()) {
			$post = new \jmd\models\entities\Post();
			$post->hydrate($data);
			$posts[] = $post;
		}
		
		$req->closeCursor();

		return $posts;
	}

	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function countPages($postsPerPage) {
		$db = $this->dbConnect();
		$resp = $db->query('SELECT id FROM posts WHERE published = TRUE');
		$numberOfPosts = $resp->rowCount();

		if ($numberOfPosts % $postsPerPage == 0) {
			$numberOfPages = $numberOfPosts / $postsPerPage;
		} else {
			$numberOfPages = ceil($numberOfPosts / $postsPerPage);
		}
		$resp->closeCursor();
		return $numberOfPages;
	}

	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function getPublishedPosts($firstIndex, $postsPerPage) {
		$db = $this->dbConnect();
		$resp = $db->prepare('
			SELECT p.id AS id, title, p.content AS content, DATE_FORMAT(publication, "%d-%m-%Y") AS publication, COUNT(ct.id) AS countComments
				FROM posts p
				LEFT JOIN comments ct ON p.id = ct.post_id
				WHERE published = TRUE
				GROUP BY p.id
				ORDER BY publication
				LIMIT :firstIndex, :postsPerPage');
		
		$resp->bindValue('firstIndex', $firstIndex, \PDO::PARAM_INT);
		$resp->bindValue('postsPerPage', $postsPerPage, \PDO::PARAM_INT);
		$resp->execute();

		$posts = array();

		while ($data = $resp->fetch()) {
			$post = new \jmd\models\entities\Post;
			$post->hydrate($data);
			$posts[] = $post;
		}
		
		$resp->closeCursor();
		return $posts;
	}

	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function getPostsPerCat($firstIndex, $postsPerPage, $category) {

		$db = $this->dbConnect();
		$resp = $db->prepare('
			SELECT p.id AS id, title, p.content AS content, publication, COUNT(ct.id) AS countComments
				FROM posts p
				JOIN cat_post cp ON p.id = cp.post_id
				JOIN categories c ON cp.cat_id = c.id
				LEFT JOIN comments ct ON p.id = ct.post_id
				WHERE published = TRUE AND name = :catName
				GROUP BY p.id
				ORDER BY publication
				DESC LIMIT :firstIndex, :postsPerPage');
		
		$resp->bindValue('catName', $category, \PDO::PARAM_STR);
		$resp->bindValue('firstIndex', $firstIndex, \PDO::PARAM_INT);
		$resp->bindValue('postsPerPage', $postsPerPage, \PDO::PARAM_INT);
		$resp->execute();

		$posts = array();

		while ($data = $resp->fetch()) {
			$post = new \jmd\models\entities\Post;
			$post->hydrate($data);
			$posts[] = $post;
		}
		
		$resp->closeCursor();
		return $posts;
	}


	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function getOnePost($post_id) {
		$db = $this->dbConnect();
		$req = $db->prepare("
			SELECT id, title, content, DATE_FORMAT(publication, '%d-%m-%Y')AS publication, published, DATE_FORMAT(creation, '%d-%m-%Y') AS creation
				FROM posts
				WHERE id = :post_id");

		$req->bindValue('post_id', $post_id, \PDO::PARAM_INT);
		$req->execute();

		$data = $req->fetch();
		$post = new \jmd\models\entities\Post;
		$post->hydrate($data);

		$req->closeCursor();
		return $post;
	}

	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function getLastPost() {
		$db = $this->dbConnect();
		$req = $db->query("
			SELECT id, title, content, DATE_FORMAT(publication, '%d-%m-%Y')AS publication, published, DATE_FORMAT(creation, '%d-%m-%Y') AS creation
				FROM posts
				ORDER BY id DESC
				LIMIT 1");

		$data = $req->fetch();
		$post = new \jmd\models\entities\Post;
		$post->hydrate($data);

		$req->closeCursor();
		return $post;
	}
	
	// UPDATE
	
	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function updatePost($id, $title, $content) {
		$db = $this->dbConnect();
		$req = $db->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$req->bindValue("title", $title, \PDO::PARAM_STR);
		$req->bindValue("content", $content, \PDO::PARAM_STR);
		
		
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de mettre à jour les informations dans la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}

	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function updatePublished($id, $status) {
		$db = $this->dbConnect();
		$req = $db->prepare("UPDATE posts SET published = :status publication = NOW() WHERE id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$req->bindValue("status", $status, \PDO::PARAM_STR);
		
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de mettre à jour les informations dans la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}

	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function publishedToNo($id, $status) {
		$db = $this->dbConnect();
		$req = $db->prepare("UPDATE posts SET published = :status WHERE id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$req->bindValue("status", $status, \PDO::PARAM_STR);
		
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de mettre à jour les informations dans la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}
	
	// DELETE
	
	/**
	 * [newPost description]
	 * @return [type] [description]
	 */
	public function deletePost($id) {
		$db = $this->dbConnect();
		$req = $db->prepare("DELETE FROM posts  WHERE id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de supprimer cet article de la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}
}



