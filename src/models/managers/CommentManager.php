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
		$req = $db->prepare("INSERT INTO comments(prenom, content, post_id, creation) VALUES (:author, :content, :id, NOW())");
		$req->bindValue("author", $author, \PDO::PARAM_STR);
		$req->bindValue("content", $comment, \PDO::PARAM_STR);
		$req->bindValue("id", $post_id, \PDO::PARAM_INT);
		$resp = $req->execute();

		if ($resp == false) {
			throw new \Exception("Impossible d'ajouter le commentaire", 1);
		} else {	
			$req->closeCursor();
			return $resp;
		}	    
	}

	// READ
	 
	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function getComments($firstIndex, $commentsPerPage) {
	 	$db = $this->dbConnect();
	 	$req = $db->prepare("
	 		SELECT c.id AS id, prenom, c.creation AS creation, c.content AS content, reported, validated, title AS post_title
	 			FROM comments c
	 			JOIN posts p ON c.post_id = p.id
	 			ORDER BY reported DESC, validated ASC, creation DESC
	 			LIMIT :firstIx, :commentsNumber");

	 	$req->bindValue("firstIx", $firstIndex, \PDO::PARAM_INT);
	 	$req->bindValue("commentsNumber", $commentsPerPage, \PDO::PARAM_INT);
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
	
	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function getRecentComments($max) {
	 	
	 	$db = $this->dbConnect();
	 	$req = $db->prepare("
	 		SELECT c.prenom AS prenom, DATE_FORMAT(c.creation, '%d-%m-%Y') AS creation, c.content AS content, p.title AS post_title, reported, mail
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

	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function getPostComments($firstIndex, $commentsPerPage, $postId) {
	 	
	 	$db = $this->dbConnect();
	 	$req = $db->prepare("
	 		SELECT c.id AS id, c.prenom AS prenom, c.creation AS creation, c.content AS content, p.title AS post_title, reported, validated
	 			FROM comments c
	 			JOIN posts p ON p.id = c.post_id
	 			WHERE c.post_id = :post_id
	 			ORDER BY c.creation DESC
	 			LIMIT :first, :max");

	 	$req->bindValue("post_id", $postId, \PDO::PARAM_INT);
	 	$req->bindValue("first", $firstIndex, \PDO::PARAM_INT);
	 	$req->bindValue("max", $commentsPerPage, \PDO::PARAM_INT);
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

	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function countPages($commentsPerPage) {
		$db = $this->dbConnect();
		$req = $db->query('SELECT id FROM comments WHERE reported = FALSE');
		$numberOfComments = $req->rowCount();
		if ($numberOfComments == 0) {
			$pagesCount = 1;
		} elseif ($numberOfComments > 0 && ($numberOfComments % $commentsPerPage) == 0) {
			$pagesCount = $numberOfComments / $commentsPerPage;
		} elseif ($numberOfComments / $commentsPerPage < 0) {
			$pagesCount = 1;
		} else {
			$pagesCount = ceil($numberOfComments / $commentsPerPage);
		}
		
		$req->closeCursor();
		return $pagesCount;
	}

	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function countPostCommentsPages($commentsPerPage, $postId) {
		$db = $this->dbConnect();
		$req = $db->prepare('SELECT id FROM comments WHERE reported = FALSE AND post_id = :id');

		$req->bindValue("id", $postId, \PDO::PARAM_INT);
		$req->execute();

		$numberOfComments = $req->rowCount();
		if ($numberOfComments == 0) {
			$pagesCount = 1;
		} elseif ($numberOfComments > 0 && ($numberOfComments % $commentsPerPage) == 0) {
			$pagesCount = $numberOfComments / $commentsPerPage;
		} elseif ($numberOfComments / $commentsPerPage < 0) {
			$pagesCount = 1;
		} else {
			$pagesCount = ceil($numberOfComments / $commentsPerPage);
		}
		
		$req->closeCursor();
		return $pagesCount;
	}

	
	// UPDATE
	
	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function updateReported($id) {
	 	$db = $this->dbConnect();
		$req = $db->prepare("UPDATE comments SET reported = true WHERE id = :commentId");

		$req->bindValue("commentId", $id, \PDO::PARAM_INT);		
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de mettre à jour les informations dans la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	} 

	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function updateValidated($commentId) {
		$db = $this->dbConnect();
		$req = $db->prepare("UPDATE comments SET reported = false, validated = true WHERE id = :id");

		$req->bindValue("id", $commentId, \PDO::PARAM_INT);		
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
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
	public function deleteComment($commentId) {
	 	$db = $this->dbConnect();
		$req = $db->prepare("DELETE FROM comments  WHERE id = :id");

		$req->bindValue("id", $commentId, \PDO::PARAM_INT);
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de supprimer le commentaire de la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}
	
	
	/**
	 * [getComments description]
	 * @param  [type] $firstIndex      [description]
	 * @param  [type] $commentsPerPage [description]
	 * @return [type]                  [description]
	 */
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
