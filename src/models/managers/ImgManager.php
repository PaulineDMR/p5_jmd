<?php 

namespace jmd\models\managers;

/**
 * 
 */
class ImgManager extends Manager {
	
	//CREATE
	
	public function insertImg($url)	{
		$db = $this->dbConnect();
		$req = $db->prepare("INSERT INTO img (url) VALUES (:url)");

		$req->bindValue("url", $url, \PDO::PARAM_STR);
		$resp = $req->execute();

		if ($req === false) {
			throw new Exception('Impossible d\'ajouter l\'image à la base de donnée !');
		} else {
			return $resp;
	 	} 
	}

	//READ
	
	public function getPaintingId($url) {
		$db = $this->dbConnect();
		$req = $db->prepare("SELECT id FROM img WHERE url = :url");
		$req->bindValue("url", $url, \PDO::PARAM_STR);
		$req->execute();

		$data = $req->fetch();
		$img = new \jmd\models\entities\Img();
		$img->hydrate($data);

		return $img;
	}


	//UPDATE
	//DELETE
}