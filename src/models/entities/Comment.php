<?php 

namespace jmd\models\entities;


/**
 * 
 */
class Comment extends Model {
	
	private $id;
	private $prenom;
	private $creation;
	private $mail;
	private $content;
	private $post_id;
    private $post_title;
	private $reported;
	private $validated;
	private $answered_id;
	private $answer;



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
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     *
     * @return self
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

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
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     *
     * @return self
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
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
    public function getPostId()
    {
        return $this->post_id;
    }

    /**
     * @param mixed $post_id
     *
     * @return self
     */
    public function setPostId($post_id)
    {
        $this->post_id = $post_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPost_title()
    {
        return $this->post_title;
    }

    /**
     * @param mixed $post_title
     *
     * @return self
     */
    public function setPost_title($post_title)
    {
        $this->post_title = $post_title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReported()
    {
        return $this->reported;
    }

    /**
     * @param mixed $reported
     *
     * @return self
     */
    public function setReported($reported)
    {
        $this->reported = $reported;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidated()
    {
        return $this->validated;
    }

    /**
     * @param mixed $validated
     *
     * @return self
     */
    public function setValidated($validated)
    {
        $this->validated = $validated;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnsweredId()
    {
        return $this->answered_id;
    }

    /**
     * @param mixed $answered_id
     *
     * @return self
     */
    public function setAnsweredId($answered_id)
    {
        $this->answered_id = $answered_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     *
     * @return self
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    
}

