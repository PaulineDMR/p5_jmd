<?php 

namespace jmd\models\entities;


/**
 * 
 */
class Img extends Model {
	
	private $id;
	private $url;
    private $fileName;



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
     * @return str
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param str $fileName
     *
     * @return self
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }
}

