<?php

namespace jmd\models\managers;

class Manager {

	private $db;
	
	protected function dbConnect() {
		if ($this->db === null) {
			$this->db = new \PDO('mysql:host=localhost:8889;dbname=p5jmd;charset=utf8', 'root', 'root');
		}
			
		return $this->db;
	}
}