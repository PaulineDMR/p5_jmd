<?php 

namespace jmd\models\entities;


class Category extends Model {
	
	private $id;
	private $post_id;
	private $cat_id;
	private $name;


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
     * @return int
     */
    public function getPost_id()
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     *
     * @return self
     */
    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getCat_id()
    {
        return $this->cat_id;
    }

    /**
     * @param int $cat_id
     *
     * @return self
     */
    public function setCat_id($cat_id)
    {
        $this->cat_id = $cat_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}