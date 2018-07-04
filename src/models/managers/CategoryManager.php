<?php 

namespace jmd\models\managers;


/**
 * 
 */
class CategoryManager extends Manager {


    // CREATE
    
    /**
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
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
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
     */
    public function getCountPostByCat() {
        
        $db = $this->dbConnect();
        $req = $db->query("SELECT COUNT(post_id) AS post_id, name  FROM cat_post cp JOIN categories c ON cp.cat_id = c.id JOIN posts p ON cp.post_id = p.id WHERE published = TRUE GROUP BY name");

        $categories = array();

        while ($data = $req->fetch()) {
            $category = new \jmd\models\entities\Category();
            $category->hydrate($data);
            $categories[] = $category;

        }

        $req->closeCursor();

        return $categories;
    }

    /**
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
     */
    public function getCategoryList()
    {
        $db = $this->dbConnect();
        $req = $db->query("SELECT name FROM categories");

        $categories = array();

        while ($data = $req->fetch()) {
            $category = new \jmd\models\entities\Category();
            $category->hydrate($data);
            $categories[] = $category;
        }

        $req->closeCursor();
        return $categories;
    }

    /**
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
     */
    public function getCatPost() {
        $db = $this->dbConnect();
        $req = $db->query("
            SELECT name, post_id
                FROM cat_post cp
                JOIN categories c ON cp.cat_id = c.id
                JOIN posts p ON cp.post_id = p.id");

        $categories = array();

        while ($data = $req->fetch()) {
            $category = new \jmd\models\entities\Category();
            $category->hydrate($data);
            $categories[] = $category;
        }

        $req->closeCursor(); 

        return $categories;
    }

    /**
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
     */
    public function catPerPost($id) {
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
            $category = new \jmd\models\entities\Category();
            $category->hydrate($data);
            $categories[] = $category;
        }

        $req->closeCursor(); 

        return $categories;
    }

    /**
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
     */
    public function getOneCat($name) {
        $db = $this->dbConnect();
        $req = $db->prepare(" SELECT id    FROM categories WHERE name = :name");

        $req->bindValue("name", $name, \PDO::PARAM_STR);
        $req->execute();

        $data = $req->fetch();
        $category = new \jmd\models\entities\Category();
        $category->hydrate($data);
        
        $req->closeCursor(); 

        return $category;
    }

    // UPDATE

    // DELETE
    
    /**
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
     */
    public function deleteCatPost($id) {
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
     * [newCatPost description]
     * @param  [type] $post_id [description]
     * @param  [type] $cat_id  [description]
     * @return [type]          [description]
     */
    public function deletePostCats($id) {
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


