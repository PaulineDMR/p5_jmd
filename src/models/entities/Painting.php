<?php 

namespace jmd\models\entities;


class Painting extends Model {

	private $id;
	private $url;
    private $img_id;
	private $title;
	private $width;
	private $height;
	private $price;
	private $sold;
	private $theme;
	private $technic;
	private $creation;
    private $published;
    private $count;


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
    public function setId($id)
    {
        if (!is_numeric($id) || $id < 0) {
            throw new \Exception("L'id n'est pas valide", 1);    
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        
        $this->url = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getImg_id()
    {
        return $this->img_id;
    }

    /**
     * @param int $img_id
     *
     * @return self
     */
    public function setImg_id($img_id)
    {
        $this->img_id = $img_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
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
     * @return string
     */
    public function getSold()
    {
        return $this->sold;
    }

    /**
     * @param string $sold
     *
     * @return self
     */
    public function setSold($sold)
    {
        $this->sold = $sold;

        return $this;
    }

    /**
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param string $theme
     *
     * @return self
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return string
     */
    public function getTechnic()
    {
        return $this->technic;
    }

    /**
     * @param string $technic
     *
     * @return self
     */
    public function setTechnic($technic)
    {
        $this->technic = $technic;

        return $this;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->creation;
    }

    /**
     * @param string $creation
     *
     * @return self
     */
    public function setDate($creatione)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * @return string
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param string $published
     *
     * @return self
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }


    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     *
     * @return self
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * @param string $creation
     *
     * @return self
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }
}