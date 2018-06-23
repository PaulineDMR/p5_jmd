<?php 

namespace jmd\models\managers;

class PostManager extends Manager {

	// CREATE
	
	// READ
	
	public function getRecentPosts($max) {
		$db = $this->dbConnect();
		$req = $db->prepare('SELECT title, content, publication FROM posts WHERE published = TRUE ORDER BY publication DESC LIMIT :max');

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

	public function countPages($postsPerPage) {
		$db = $this->dbConnect();
		$resp = $db->query('SELECT id FROM posts WHERE published = TRUE');
		$numberOfPosts = $resp->rowCount();
		$numberOfPages = ceil($numberOfPosts / $postsPerPage);

		$resp->closeCursor();
		return $numberOfPages;
	}

	public function getPosts($firstIndex, $postsPerPage) {
		$db = $this->dbConnect();
		$resp = $db->prepare('
			SELECT p.id AS id, title, p.content AS content, publication, COUNT(ct.id) AS countComments
				FROM posts p
				LEFT JOIN comments ct ON p.id = ct.post_id
				WHERE published = TRUE
				GROUP BY p.id
				ORDER BY publication
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


	public function getOnePost($post_id) {
		$db = $this->dbConnect();
		$req = $db->prepare("
			SELECT p.id AS id, title, p.content AS content, publication
				FROM posts p
				WHERE p.id = :post_id");

		$req->bindValue('post_id', $post_id, \PDO::PARAM_INT);
		$req->execute();

		$postX = array();

		$data = $req->fetch();
		$post = new \jmd\models\entities\Post;
		$post->hydrate($data);
		$postX[] = $post;

		$req->closeCursor();

		return $postX;
	}
	
	// UPDATE
	
	// DELETE
}



