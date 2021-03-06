<?php 

namespace jmd\models\entities;

class Admin extends Model{

    private $id;
    private $nom;
    private $prenom;
    private $street;
    private $cp;
    private $town;
    private $country;
    private $phone;
    private $mail;
    private $login;
    private $pwd;


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
        if (!is_numeric($id) || $id < 0) {
            throw new \Exception("L'id n'est pas valide", 1);    
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     *
     * @return self
     */
    public function setNom($nom)
    {
        if (!is_string($nom)) {
            throw new Exception("Le nom n'est pas une chaîne de caractère", 1);
        }

        $this->nom = $nom;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     *
     * @return self
     */
    public function setPrenom($prenom)
    {
        if (!is_string($prenom)) {
            throw new Exception("Le prénom n'est pas une chaîne de caractère", 1);
        }    

        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return string
     */
    public function getStreet() {
        return $this->street;
    }

    /**
     * @param string $street
     *
     * @return self
     */
    public function setStreet($street)
    {
        if (!is_string($street)) {
            throw new Exception("La rue n'est pas une chaîne de caractère", 1);
        } 

        $this->street = $street;

        return $this;
    }

    /**
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * @param string $cp
     *
     * @return self
     */
    public function setCp($cp)
    {
        if (!is_numeric($cp)) {
            throw new Exception("Le code postal est invalide", 1);
        }

        $this->cp = $cp;

        return $this;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param string $town
     *
     * @return self
     */
    public function setTown($town)
    {
        if (!is_string($street)) {
            throw new Exception("La ville n'est pas une chaîne de caractère", 1);
        }

        $this->town = $town;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     *
     * @return self
     */
    public function setCountry($country)
    {
        if (!is_string($street)) {
            throw new Exception("Le pays n'est pas une chaîne de caractère", 1);
        }

        $this->country = $country;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return self
     */
    public function setPhone($phone)
    {
        if (!is_string($street)) {
            throw new Exception("Le no de téléphone n'est pas valide", 1);
        }

        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param string $mail
     *
     * @return self
     */
    public function setMail($mail)
    {
        if (strpos($mail, "@") == false) {
            throw new Exception("Le mail n'est pas valide", 1);
        }

        $this->mail = $mail;

        return $this;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     *
     * @return self
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return string
     */
    public function getPwd() {
        return $this->pwd;
    }

    /**
     * @param string $pwd
     *
     * @return self
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;

        return $this;
    }
}