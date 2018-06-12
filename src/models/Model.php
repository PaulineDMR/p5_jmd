<?php 

namespace jmd\models;

class Model {

	/**
	 * [hydate method]
	 * @param  array  $datas [datas from DB request]
	 */
	public function hydrate(array $datas) {

	  	foreach ($datas as $key => $value) {
	    	$method = 'set'.ucfirst($key);
	        
	    	if (method_exists($this, $method)) {
	      		$this->$method($value);
	    	}
	  	}
	}
	
}
