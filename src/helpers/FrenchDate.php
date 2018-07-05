<?php 

namespace jmd\helpers;

/**
 * 
 */
class FrenchDate {

	private $frenchDate;
	
	function __construct($date) {
		$newDate = new \DateTime($date);

		$finalDate = $newDate->format('d-m-Y');

		$this->frenchDate = $finalDate;
	}

    /**
     * @return mixed
     */
    public function getFrenchDate()
    {
        return $this->frenchDate;
    }

    /**
     * @param mixed $frenchDate
     *
     * @return self
     */
    public function setFrenchDate($frenchDate)
    {
        $this->frenchDate = $frenchDate;

        return $this;
    }
}
