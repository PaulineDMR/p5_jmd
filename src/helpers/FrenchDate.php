<?php 

namespace jmd\helpers;

/**
 * 
 */
class FrenchDate {

	private $frenchDate;
	
	/**
     * [Set the date in french format]
     * @param [type] $date [description]
     */
    public function __construct($date) {
		$newDate = new \DateTime($date);

		$finalDate = $newDate->format('d-m-Y');

		$this->frenchDate = $finalDate;
	}

    /**
     * @return string
     */
    public function getFrenchDate()
    {
        return $this->frenchDate;
    }

    /**
     * @param string $frenchDate
     *
     * @return self
     */
    public function setFrenchDate($frenchDate)
    {
        $this->frenchDate = $frenchDate;

        return $this;
    }
}
