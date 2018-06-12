<?php 

namespace jmd\models;

class PostManager extends Manager {

	// CREATE
	
	// READ
	
	public function getRecentPosts($max) {
		$db = $this->dbConnect();
		$req = $db->prepare('SELECT title, content, publication FROM jmd_posts WHERE published = TRUE AND user_id = 1 ORDER BY publication DESC LIMIT :max');

		$req->bindValue('max', $max, PDO::PARAM_INT);
		$req->execute();
		
		$posts = array();

		while ($data = $req->fetch()) {
			$post = new Post();
			$post->hydrate($data);
			$posts[] = $post;
		}
		
		$req->closeCursor();

		return $posts;
	}
	
	// UPDATE
	
	// DELETE
}



