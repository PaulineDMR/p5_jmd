<?php 

namespace jmd\models\managers;


/**
 * 
 */
class CommentManager extends Manager {
	
	
	// CREATE

	/**
	 * @param [string]
	 * @param [string]
	 * @param [int]
	 */
	public function addComment($author, $comment, $post_id) {
		$db = $this->dbConnect();
		$new_comment = $db->prepare("INSERT INTO comments(prenom, content, post_id, creation) VALUES (?, ?, ?, NOW())");
		$new_entry = $new_comment->execute(array($author, $comment, $post_id));

		$new_comment->closeCursor();

	    return $new_entry;
	}

	// READ
	
	public function getRecentComments($max) {
	 	
	 	$db = $this->dbConnect();
	 	$req = $db->prepare("
	 		SELECT c.prenom AS prenom, c.creation AS creation, c.content AS content, p.title AS post_title
	 			FROM comments c
	 			JOIN posts p ON p.id = c.post_id
	 			ORDER BY c.creation DESC
	 			LIMIT :max");

	 	$req->bindValue("max", $max, \PDO::PARAM_INT);
	 	$req->execute();

	 	$comments = array();

	 	while ($data = $req->fetch()) {
	 		$comment = new \jmd\models\entities\Comment;
	 		$comment->hydrate($data);
	 		$comments[] = $comment;
	 	}

	 	$req->closeCursor();

	 	return $comments;
	} 
	
	// UPDATE
	 

	// DELETE
	
	public function deletePostComments($id) {
		$db = $this->dbConnect();
		$req = $db->prepare("DELETE FROM comments  WHERE post_id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de supprimer les commentaires de la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}
}
