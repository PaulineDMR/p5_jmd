<?php 

require_once("Manager.php");
require_once("Post.php");

class PostManager extends Manager {

	// CREATE
	
	// READ
	
	public function getLastTwoPosts() {
		$db = $this->dbConnect();
		$req = $db->query("SELECT post_title, post_content, post_publication FROM posts WHERE post_published = TRUE ORDER BY post_publication DESC LIMIT 2");

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



