<?php 

namespace jmd\models\managers;

use jmd\models\entities\Category;


class CategoryManager extends Manager {


    // CREATE
    
    /**
     * [Link a post to a category and reccord in D]
     * @param  [int] $post_id [id of the post]
     * @param  [int] $cat_id  [id of the category]
     * [Exception in case of fail from db or a TRUE for success]
     */
    public function newCatPost($post_id, $cat_id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("INSERT INTO cat_post (post_id, cat_id) VALUES (:postId, :catId)");

        $req->bindValue("postId", $post_id, \PDO::PARAM_INT);
        $req->bindValue("catId", $cat_id, \PDO::PARAM_INT);
        $resp = $req->execute();

        if ($resp === false) {
            throw new \Exception("Impossible d'effectuer cette action.", 1);
        } else {
            $req->closeCursor();
            return $resp;
        }
    }
    
    // READ
       
    /**
     * [Count How many post for each category]
     * @return [array] [array of Category object]
     */
    public function getCountPostByCat()
    {
        
        $db = $this->dbConnect();
        $req = $db->query("SELECT COUNT(post_id) AS post_id, name  FROM cat_post cp JOIN categories c ON cp.cat_id = c.id JOIN posts p ON cp.post_id = p.id WHERE published = TRUE GROUP BY name");

        $categories = array();

        while ($data = $req->fetch()) {
            $category = new Category();
            $category->hydrate($data);
            $categories[] = $category;

        }

        $req->closeCursor();

        return $categories;
    }

    /**
     * [Get the list of all the existing categories]
     * @return [array] [object Category array]
     */
    public function getCategoryList()
    {
        $db = $this->dbConnect();
        $req = $db->query("SELECT name FROM categories");

        $categories = array();

        while ($data = $req->fetch()) {
            $category = new Category();
            $category->hydrate($data);
            $categories[] = $category;
        }

        $req->closeCursor();
        return $categories;
    }
  
    /**
     * [Get each line of the cat_post table]
     * @return [array] [a list of object Category]
     */
    public function getCatPost()
    {
        $db = $this->dbConnect();
        $req = $db->query("
            SELECT name, post_id
                FROM cat_post cp
                JOIN categories c ON cp.cat_id = c.id
                JOIN posts p ON cp.post_id = p.id");

        $categories = array();

        while ($data = $req->fetch()) {
            $category = new Category();
            $category->hydrate($data);
            $categories[] = $category;
        }

        $req->closeCursor(); 

        return $categories;
    }

    /**
     * [Get a list of category for one post]
     * @param  [int] $id [id f the post]
     * @return [array]     [Object Category array]
     */
    public function catPerPost($id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("
            SELECT name, cp.id
                FROM cat_post cp
                JOIN categories c ON cp.cat_id = c.id
                JOIN posts p ON cp.post_id = p.id
                WHERE cp.post_id = :id");

        $req->bindValue("id", $id, \PDO::PARAM_INT);
        $req->execute();

        $categories = array();

        while ($data = $req->fetch()) {
            $category = new Category();
            $category->hydrate($data);
            $categories[] = $category;
        }

        $req->closeCursor(); 

        return $categories;
    }
  
    /**
     * [Get the name og a category]
     * @param  [string] $name [name of the category]
     * @return [array]       [one Category object]
     */
    public function getOneCat($name)
    {
        $db = $this->dbConnect();
        $req = $db->prepare(" SELECT id    FROM categories WHERE name = :name");

        $req->bindValue("name", $name, \PDO::PARAM_STR);
        $req->execute();

        $data = $req->fetch();
        $category = new Category();
        $category->hydrate($data);
        
        $req->closeCursor(); 

        return $category;
    }

    // UPDATE
    

    // DELETE
    
    /**
     * [Delete the link between a post and a category in DB]
     * @param  [int] $id [id of the concern line in db]
     * [Throw an exception if fail]
     * [return True if succes]
     */
    public function deleteCatPost($id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("DELETE FROM cat_post WHERE id = :id");

        $req->bindValue("id", $id, \PDO::PARAM_INT);
        $resp = $req->execute();

        if ($resp === false) {
            throw new \Exception("Impossible d'effectuer cette action.", 1);
        } else {
            $req->closeCursor();
            return $resp;
        }
    }
    
    /**
     * [Delete lines concerning one post in cat_post table]
     * @param  [int] $id [post id]
     * [Throw an exception if fail]
     * [return True if succes]
     */
    public function deletePostCats($id)
    {
        $db = $this->dbConnect();
        $req = $db->prepare("DELETE FROM cat_post WHERE post_id = :id");

        $req->bindValue("id", $id, \PDO::PARAM_INT);
        $resp = $req->execute();

        if ($resp === false) {
            throw new \Exception("Impossible d'effectuer cette action.", 1);
        } else {
            $req->closeCursor();
            return $resp;
        }
    }

}


