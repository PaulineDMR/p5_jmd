<?php

namespace jmd\models\managers;

class Manager {
	
	protected function dbConnect() {
		$db = new \PDO('mysql:host=localhost:8889;dbname=p5jmd;charset=utf8', 'root', 'root');
		return $db;
	}
}