<?php 

namespace jmd\models\managers;

class PostImg extends Model {

	private $id;
	private $post_id;
	private $img_id;
    private $url;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPost_id()
    {
        return $this->post_id;
    }

    /**
     * @param mixed $post_id
     *
     * @return self
     */
    public function setPost_id($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImg_id()
    {
        return $this->img_id;
    }

    /**
     * @param mixed $img_id
     *
     * @return self
     */
    public function setImg_id($img_id)
    {
        $this->img_id = $img_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     *
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }
}
