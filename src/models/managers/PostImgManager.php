<?php 

namespace jmd\models\managers;

use jmd\models\entities\PostImg;

class PostImgManager extends Manager {
	
	
	public function getPostImg()
	{
		$db = $this->dbConnect();
		$req = $db->query('
			SELECT img_id, post_id, url, fileName
				FROM posts_img p
				JOIN img i ON p.img_id = i.id');

		$postImgs = array();

		while ($data = $req->fetch()) {
			$postImg = new PostImg();
			$postImg->hydrate($data);
			$postImgs[] = $postImg;
		}

		$req->closecursor();
		return $postImgs;
	}

}