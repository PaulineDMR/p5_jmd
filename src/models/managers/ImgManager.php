<?php 

namespace jmd\models\managers;

/**
 * 
 */
class ImgManager extends Manager {
	
	//CREATE
	
	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function newImg($url, $fileName)	{
		$db = $this->dbConnect();
		$req = $db->prepare("INSERT INTO img (url, fileName) VALUES (:url, :fileName)");

		$req->bindValue("url", $url, \PDO::PARAM_STR);
		$req->bindValue("fileName", $fileName, \PDO::PARAM_STR);

		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception('Impossible d\'ajouter l\'image à la base de donnée !');
		} else {
			$req->closeCursor();
			return $resp;
	 	} 
	}

	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function newPostImg($post_id, $img_id) {
		$db = $this->dbConnect();
		$req = $db->prepare(" INSERT INTO posts_img (img_id, post_id) VALUES (:imgId, :postId)");
		
		$req->bindValue("imgId", $img_id, \PDO::PARAM_INT);
		$req->bindValue("postId", $post_id, \PDO::PARAM_INT);
		
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception('Impossible d\'ajouter l\'image à la base de donnée !');
		} else {
			$req->closeCursor();
			return $resp;
	 	} 
	}

	//READ
	
	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function getPaintingId($url) {
		$db = $this->dbConnect();
		$req = $db->prepare("SELECT id FROM img WHERE url = :url");
		$req->bindValue("url", $url, \PDO::PARAM_STR);
		$req->execute();

		$data = $req->fetch();
		$img = new \jmd\models\entities\Img();
		$img->hydrate($data);

		$req->closeCursor();
		return $img;
	}

	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function getPostImgs($post_id) {
		$db = $this->dbConnect();
		$req = $db->prepare("
			SELECT pi.img_id AS img_id, url, fileName, post_id
				FROM posts_img pi
				JOIN img i ON i.id = pi.img_id
				WHERE pi.post_id = :id");

		$req->bindValue("id", $post_id, \PDO::PARAM_INT);
		$req->execute();

		$imgs = array();

		while ($data = $req->fetch()) {
			$img = new \jmd\models\entities\PostImg();
			$img->hydrate($data);
			$imgs[] = $img;
		}

		$req->closeCursor();
		return $imgs;
	}

	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function getImg($url) {
		$db = $this->dbConnect();
		$req = $db->prepare("
			SELECT id, url FROM img i WHERE url = :url");

		$req->bindValue("url", $url, \PDO::PARAM_STR);
		$req->execute();

		$data = $req->fetch();
			$img = new \jmd\models\entities\Img();
			$img->hydrate($data);

		$req->closeCursor();
		return $img;
	}

	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function getPostImg($postId) {
		$db = $this->dbConnect();
		$req = $db->prepare('
			SELECT img_id, post_id, url
				FROM posts_img pi
				JOIN img i ON i.id = pi.img_id
				WHERE post_id = :id
				LIMIT 1');

		$req->bindValue("id", $postId, \PDO::PARAM_INT);
		$req->execute();

		$data = $req->fetch();
		$postImg = new \jmd\models\entities\PostImg();
		$postImg->hydrate($data);

		$req->closecursor();
		return $postImg;
		
	}
	

	//UPDATE
	
	
	//DELETE
	
	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function delete($id) {
		$db = $this->dbConnect();
		$req = $db->prepare("DELETE FROM img  WHERE id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de supprimer cette photo de la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}

	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function deletePostImgs($id) {
		$db = $this->dbConnect();
		$req = $db->prepare("DELETE FROM posts_img  WHERE post_id = :id");

		$req->bindValue("id", $id, \PDO::PARAM_INT);
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de supprimer les images de la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}

	/**
	 * [newImg description]
	 * @param  [type] $url      [description]
	 * @param  [type] $fileName [description]
	 * @return [type]           [description]
	 */
	public function deleteFile($file) {
		$db = $this->dbConnect();
		$req = $db->prepare("DELETE FROM img  WHERE fileName = :file");

		$req->bindValue("file", $file, \PDO::PARAM_STR);
		$resp = $req->execute();

		if ($resp === false) {
			throw new \Exception("Impossible de supprimer les fichhiers de la base de données", 1);
		} else {
			$req->closeCursor();
			return $resp;
		}
	}
	
}



