<?php

class Post extends Model {

    public $PostId;
    public $AuthorId;
    public $Title;
    public $Body;
    public $Timestamp;
    public $AcceptedAnwerId;
    public $Parentid;

    public function __construct($PostId=-1, $AuthorId, $Title, $Body, $Timestamp, $AcceptedAnwerId , $Parentid ) {
        $this->PostId = $PostId;
        $this->AuthorId = $AuthorId;
        $this->Title = $Title;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->AcceptedAnwerId = $AcceptedAnwerId;
        $this->ParentId = $Parentid;
    }

   
  public static function affichepost() {

        $query = self::execute("select * from post join user on user.UserId=post.PostId "
                        . "where body IS NOT NULL and ParentId IS NULL ORDER BY Timestamp DESC", array());
        $array = $query->fetchAll();

        $resul = [];
        foreach ($array as $row) {
            $post = new Post($row["PostId"], $row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"]);
            //$post[] = new Post($row['PostId'], $row['AuthorId'], $row['Title'], $row['Body'], $row['Timestamp'], $row['AcceptedAnswerId'], $row['ParentId']);
            $resul[] = $post;
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
            $results[] = new Post($row["PostId"], $row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"]);
             
        }
        return $results;
    }
    public static function Ak_a_question(){
        
    }
     //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public function validate(){
        $errors = array();
        if(!(isset($this->AuthorId ) && is_a($this->AuthorId,"User") && User::get_member_by_username($this->AuthorId->UserName))){
            $errors[] = "Incorrect authorid";
        }
        if(!(isset($this->Title) && is_a($this->Title,"User") && User::get_member_by_username($this->Title->UserName))){
            $errors[] = "Incorrect Title";
        }
        if(!(isset($this->Body) && is_string($this->Body) && strlen($this->Body) > 0)){
            $errors[] = "Body must be filled";
        }
        return $errors;
    }
    public static function get_post_user($user) {
        $query = self::execute("select * from FROM Post join user on user.UserId=Post.AuthorId "
                . "where UserName = :UserName order by Timestamp", array("UserName" => $user->UserName));
        $data = $query->fetchAll();
        $post = [];
        foreach ($data as $row) {
            $post[] = new Message($row['PostId'],User::get_member_by_username($row['AuthorId']), User::get_member_by_username($row['Title']), $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"]);

        }
        return $post;
    }
    public static function get_post_PostId($PostId) {
        $query = self::execute("select * from post join user on user.UserId=post.PostId where post_id = :id", array("id" => $post_id));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Message($row['PostId'],User::get_member_by_username($row['AuthorId']), User::get_member_by_username($row['Title']), $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"]);
        }
    }

    public static function newest(){
        $query= self::execute(("SELECT post.*, max_score
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
            $newest[]=new Post($value["PostId"], $value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"]);
         }
         return $newest;   
    }
}
