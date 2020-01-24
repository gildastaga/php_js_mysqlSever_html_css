<?php

require_once "lib/parsedown-1.7.3/Parsedown.php";

class Post extends Model {

    public $PostId;
    public $AuthorId;
    public $Title;
    public $Body;
    public $Timestamp;
    public $AcceptedAnswerId;
    public $ParentId;

    public function __construct($AuthorId, $Title, $Body, $Timestamp, $AcceptedAnswerId, $Parentid, $PostId = -1) {
        $this->PostId = $PostId;
        $this->AuthorId = $AuthorId;
        $this->Title = $Title;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->AcceptedAnswerId = $AcceptedAnswerId;
        $this->ParentId = $Parentid;
    }

    public function markdown(){
        //$markdown = "Ceci est un *texte* **Markdown**";
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        $html = $Parsedown->text($this->Body);
        return $html;
    }

    public static function affichepost() {

        $query = self::execute("select * from post join user on user.UserId=post.PostId "
                        . "where body IS NOT NULL and ParentId IS NULL ORDER BY Timestamp DESC", array());
        $array = $query->fetchAll();
        $resul = [];
        foreach ($array as $row) {
            $resul[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row["PostId"]);
        }
        return $resul;
    }

    public static function post() {
        $query = self::execute(("SELECT post.*, max_score FROM post,(SELECT parentid, max(score) max_score
            FROM (SELECT post.postId, ifnull(post.parentid, post.postId) parentid, ifnull(sum(vote.updown), 0) score
            FROM post LEFT JOIN vote ON vote.postId = post.postId
            GROUP BY post.postId) AS tbl1
            GROUP by parentid
            ) AS q1
            WHERE post.postId = q1.parentid
            ORDER BY q1.max_score DESC, timestamp DESC"), array());
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new Post( $row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"],
                    $row["ParentId"],$row["PostId"]);
        }
        return $results;
    }

    // return false ou le post d'un postId
    public static function get_quetion($PostId) {
        $query = self::execute("SELECT * FROM post where PostId =:PostId", array("PostId" => $PostId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Post( $row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row["PostId"]);
        }
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public  function validate() {
        $errors = array();
        if (!(isset($this->Title) )) {
            $errors[] = "Incorrect Title";
        }
        if (!(isset($this->Body)  )) {
            $errors[] = "Body must be filled";
        }
        return $errors;
    } 
    //revoir les answer et autorid d'un post
    public function getAllAnswerAndAutorIdbypost($PosId){
         $query = self::execute("select AuthorId,AcceptedAnswerId  from  Post where PosId = :PosId "
                 . "GROUP by AuthorId, ORDER BY Timestamp",array("PosId" => $PosId));
         $data = $query->fetchAll();
         return $data;
    } 
   // renvoir le nombre de reponse sur une question  
    public function getAllAnswerByPost($PosId){
         $query = self::execute("select count(AcceptedAnswerId)as nbr_answer from  Post where PosId = :PosId ",
                 array("PosId" => $PosId));
         $data = $query->fetch();
         return $data;
    }   

    //renvoie la question d'un postid si trouver si non false
    public static function get_post_PostId($PostId) {
        $query = self::execute("select * from post  where PostId =:PostId", array("PostId" => $PostId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Post( $row['AuthorId'],$row['Title'], $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row['PostId']);
        }
    }

    //renvoie tous les post d'un auteur ou false si null
    public static function getAllPost_by_user($user) {
        $query = self::execute("SELECT * FROM post where AuthorId = :UserId order by Timestamp DESC", array("UserId" => $user->UserId));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $resul=[];
            foreach ($data as $row) {
                $resul= $post[] = new Post( $row['AuthorId'],$row['Title'], $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row['PostId']);
            }
            return $resul;
        }
    }

    //ajoute un post ou update un post
    public function update() {
        if ($this->PostId == -1) {           
            self::execute("INSERT INTO post(AuthorId,Title,Body,Timestamp,AcceptedAnswerId,ParentId) "
                    . "VALUES(:AuthorId,:Title,:Body,:Timestamp,:AcceptedAnswerId,:ParentId)", 
                array("AuthorId" => $this->AuthorId, "Title" => $this->Title, "Body" => $this->Body,
                "Timestamp"=> $this->Timestamp, "AcceptedAnswerId" => $this->AcceptedAnswerId, "ParentId" => $this->ParentId));
            return $this;
        } else {var_dump("lol");
            self::execute("UPDATE post SET  AuthorId:AuthorId, Title:Title, Body:Body, Timestamp:Timestamp,"
                    . "AcceptedAnswerId:AcceptedAnswerId, ParentId:ParentId WHERE PostId=:PostId ", 
                        array("AuthorId" => $this->AuthorId, "Title" => $this->Title, "Body" => $this->Body, "Timestamp" => $this->Timestamp,
                            "AcceptedAnswerId" => $this->AcceptedAnswerId, "ParentId" => $this->ParentId,"PostId"=> $this->PostId));    
                 return $this;
        }
    }

    public static function newest() {
        $query = self::execute(("SELECT post.*, max_score
                                FROM post, (
                          SELECT parentid, max(score) max_score
                          FROM (
                              SELECT post.postid, ifnull(post.parentid, post.postid) parentid, ifnull(sum(vote.updown), 0) score
                              FROM post LEFT JOIN vote ON vote.postid = post.postid
                              GROUP BY post.postid
                          ) AS tbl1
                          GROUP by parentid
                      ) AS q1
                      WHERE post.postid = q1.parentid
                      ORDER BY q1.max_score DESC, timestamp DESC "), array());
        $data = $query->fetchAll();
        $newest = [];
        foreach ($data as $value) {
            $newest[] = new Post( $value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"],$value["PostId"]);
        }
        return $newest;
    }
    public function delete($initiator) {
        if ($this->AuthorId == $initiator->UserId ) {
            self::execute('DELETE FROM post WHERE postid = :postid', array('postid' => $this->postid));
            return $this;
        }
        return false;
    }
}
