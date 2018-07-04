<?php 

namespace jmd\models\managers;

/**
 * 
 */
class PostImgManager extends Manager {
	
	/**
	 * [getPostImg description]
	 * @return [type] [description]
	 */
	public function getPostImg() {
		$db = $this->dbConnect();
		$req = $db->query('
			SELECT img_id, post_id, url, fileName
				FROM posts_img p
				JOIN img i ON p.img_id = i.id');

		$postImgs = array();

		while ($data = $req->fetch()) {
			$postImg = new \jmd\models\entities\PostImg();
			$postImg->hydrate($data);
			$postImgs[] = $postImg;
		}

		$req->closecursor();

		return $postImgs;
	}


}