<?php 

namespace jmd\models\managers;

use jmd\models\entities\Comment;

class CommentManager extends Manager {
	
	
	// CREATE

	
	/**
	 * [Insert a new comment line in comments table]
	 * @param [string] $author  [First name of the author]
	 * @param [string] $comment [text content of the comment]
	 * @param [int] $post_id [id of the post concerned]
	 */
	public function addComment($author, $comment, $post_id)
	{
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
	 * [Get a list of comments for one page]
	 * @param  [int] $firstIndex      [index where to start the list]
	 * @param  [int] $commentsPerPage [number of line to pick from the table]
	 * @return [array]                [object Comment list]
	 */
	public function getComments($firstIndex, $commentsPerPage)
	{
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
	 		$comment = new Comment;
	 		$comment->hydrate($data);
	 		$comments[] = $comment;
	 	}

	 	$req->closeCursor();
	 	return $comments;
	} 
	
	/**
	 * [Get a list of comments from the later]
	 * @param  [int] $max [number of line to pick from the table]
	 * @return [array]      [object Comment list]
	 */
	public function getRecentComments($max)
	{
	 	
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
	 		$comment = new Comment;
	 		$comment->hydrate($data);
	 		$comments[] = $comment;
	 	}

	 	$req->closeCursor();

	 	return $comments;
	} 

	/**
	 * [Get list of comments linked to one same post]
	 * [with an index where to start to pick]
	 * [and a number of lines to pick]
	 * @param  [int] $firstIndex      [which line to start]
	 * @param  [int] $commentsPerPage [number of lineto pick]
	 * @param  [int] $postId          [id of the concerned post]
	 * @return [array]                [object Comment array]
	 */
	public function getPostComments($firstIndex, $commentsPerPage, $postId)
	{
	 	
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
	 		$comment = new Comment;
	 		$comment->hydrate($data);
	 		$comments[] = $comment;
	 	}

	 	$req->closeCursor();

	 	return $comments;
	} 

	/**
	 * [count of many page of comments]
	 * @param  [int] $commentsPerPage [number of comments for one page]
	 * @return [int]                  [number of pages]
	 */
	public function countPages($commentsPerPage)
	{
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
	 * [Get a count of pages for all the comment linked to one post]
	 * @param  [int] $commentsPerPage [how many comments in one page]
	 * @param  [int] $postId          [id of the post]
	 * @return [int]                  [count of pages]
	 */
	public function countPostCommentsPages($commentsPerPage, $postId)
	{
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
	 * [Changes the reported status of a comment to true in the comments table]
	 * @param  [int] $id [comment id]
	 * @return [bool]    [in case of succes]
	 */
	public function updateReported($id)
	{
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
	 * [Changes the validated status of a comment to true in the comments table]
	 * @param  [int] $commentId [description]
	 * @return [bool]           [true in case of success]
	 */
	public function updateValidated($commentId)
	{
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
	 * [Delete a comment line in the comments table]
	 * @param  [int] $commentId [comment id]
	 * @return [bool]           [true in case of success]
	 */
	public function deleteComment($commentId)
	{
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
	 * [Delete a comment linked to a post]
	 * @param  [int] $id [post id]
	 * @return [bool]    [in case of success]
	 */
	public function deletePostComments($id)
	{
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
