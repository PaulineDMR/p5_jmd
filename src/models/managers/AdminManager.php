<?php 

namespace jmd\models\managers;

/**
 * 
 */
class AdminManager extends Manager {

	// CREATE
	
	// READ
	
	/**
	 * [getAdmins description]
	 * @return [type] [description]
	 */
	public function getAdmins() {
	$db = $this->dbConnect();
	$resp = $db->query("SELECT id, nom, prenom, login, pwd FROM admins");

	$admins = array();

		while ($donnees = $resp->fetch()) {
			$admin = new \jmd\models\entities\Admin();
			$admin->hydrate($donnees);
			$admins[] = $admin;
		}

		$resp->closeCursor();
		return $admins;
	}
	
	// UPDATE
	
	// DELETE

}
