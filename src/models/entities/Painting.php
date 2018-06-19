<?php 

namespace jmd\models\entities;


class Painting extends Model {

	private $id;
	private $url;
	private $title;
	private $width;
	private $height;
	private $price;
	private $sold;
	private $theme;
	private $technic;
	private $creation;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId($id) {
        if (!is_int($id) || $id < 0) {
            throw new Exception("L'id n'est pas valide", 1);    
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return str
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param str $url
     *
     * @return self
     */
    public function setUrl($url) {
        
        $this->url = $url;

        return $this;
    }

    /**
     * @return str
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param str $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param int $width
     *
     * @return self
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $height
     *
     * @return self
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     *
     * @return self
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSold()
    {
        return $this->sold;
    }

    /**
     * @param mixed $sold
     *
     * @return self
     */
    public function setSold($sold)
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * @return str
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param str $theme
     *
     * @return self
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return str
     */
    public function getTechnic()
    {
        return $this->technic;
    }

    /**
     * @param str $technic
     *
     * @return self
     */
    public function setTechnic($technic)
    {
        $this->technic = $technic;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->creation;
    }

    /**
     * @param mixed $creation
     *
     * @return self
     */
    public function setDate($creatione)
    {
        $this->creation = $creation;

        return $this;
    }
}