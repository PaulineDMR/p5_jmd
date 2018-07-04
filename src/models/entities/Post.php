<?php 

namespace jmd\models\entities;


class Post extends Model {

	private $id;
    private $user_id;
	private $title;
	private $content;
	private $creation;
	private $publication;
	private $published;
    private $countComments;
    private $url;


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId($id) {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId() {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     *
     * @return self
     */
    public function setUser_id($user_id) {
        $this->user_id = $user_id;

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
     * @return str
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param str $content
     *
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreation()
    {
        return $this->creation;
    }

    /**
     * @param mixed $creation
     *
     * @return self
     */
    public function setCreation($creation)
    {
        $this->creation = $creation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param mixed $publication
     *
     * @return self
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * @param mixed $published
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
    public function getCountComments()
    {
        return $this->countComments;
    }

    /**
     * @param int $countComments
     *
     * @return self
     */
    public function setCountComments($countComments)
    {
        $this->countComments = $countComments;

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
