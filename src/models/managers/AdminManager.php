<?php 

namespace jmd\models\managers;

use jmd\models\entities\Admin;


class AdminManager extends Manager {

	// CREATE
	
	// READ
	
	/**
	 * [get administrator infos from admins table]
	 * @return [array] [array of object Admin]
	 */
	public function getAdmins()
	{
		$db = $this->dbConnect();
		$resp = $db->query("SELECT id, nom, prenom, login, pwd FROM admins");

		$admins = array();

			while ($donnees = $resp->fetch()) {
				$admin = new Admin();
				$admin->hydrate($donnees);
				$admins[] = $admin;
			}

		$resp->closeCursor();
		return $admins;
	}
	
	// UPDATE
	
	// DELETE

}
