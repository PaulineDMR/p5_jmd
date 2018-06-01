<?php 

require ('Model.php');


class Post extends Model {

	private $post_id;
	private $post_title;
	private $post_content;
	private $post_creation;
	private $post_publication;
	private $post_published;


    /**
     * @return int
     */
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param int $post_id
     *
     * @return self
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * @return str
     */
    public function getPostTitle()
    {
        return $this->post_title;
    }

    /**
     * @param str $post_title
     *
     * @return self
     */
    public function setPostTitle($post_title)
    {
        $this->post_title = $post_title;

        return $this;
    }

    /**
     * @return str
     */
    public function getPostContent()
    {
        return $this->post_content;
    }

    /**
     * @param str $post_content
     *
     * @return self
     */
    public function setPostContent($post_content)
    {
        $this->post_content = $post_content;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostCreation()
    {
        return $this->post_creation;
    }

    /**
     * @param mixed $post_creation
     *
     * @return self
     */
    public function setPostCreation($post_creation)
    {
        $this->post_creation = $post_creation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostPublication()
    {
        return $this->post_publication;
    }

    /**
     * @param mixed $post_publication
     *
     * @return self
     */
    public function setPostPublication($post_publication)
    {
        $this->post_publication = $post_publication;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostPublished()
    {
        return $this->post_published;
    }

    /**
     * @param mixed $post_published
     *
     * @return self
     */
    public function setPostPublished($post_published)
    {
        $this->post_published = $post_published;

        return $this;
    }
}
