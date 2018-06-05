<?php 

require_once("Manager.php");
require_once("Post.php");

class PostManager extends Manager {

	// CREATE
	
	// READ
	
	public function getLastTwoPosts() {
		$db = $this->dbConnect();
		$req = $db->query('SELECT title, content, publication FROM jmd_posts WHERE published = TRUE AND user_id = 1 ORDER BY publication DESC LIMIT 2');
		
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



