<?php

require_once "lib/parsedown-1.7.3/Parsedown.php";

require_once "framework/model.php";
require_once "model/User.php";
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
    public function get_temp($param) {
        $datetime1 = new DateTime($param);
        $datetime2 = new DateTime('Y-m-d H:i:s');
        $interval = $datetime1->diff($datetime2);
        echo $interval->format('%R%a days');
    }
    public static function filter($search){
        $query= self::execute("select * from post where 
                 AuthorId LIKE :AuthorId or Title LIKE :Title or Body LIKE :Body
                or AcceptedAnswerId LIKE: AcceptedAnswerId or  ParentId LIKE:ParentId ",
                array("AuthorId"=>"%".$search."%","Title"=>"%".$search."%","Body"=>"%".$search."%","AcceptedAnswerId"=>"%".$search."%",
                    "ParentId"=>"%".$search."%"));
        $data=$query->fetchAll();
        $resul=[];
        foreach ($data as $row) {
            $resul[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row["PostId"]);
        }
        return $resul;    
    }

    public function markdown(){
        //$markdown = "Ceci est un *texte* **Markdown**";
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        $html = $Parsedown->text($this->Body);
        return $html;
    }

    public static function get_all_post() {

        $query = self::execute("select * from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL GROUP BY PostId ORDER BY Timestamp DESC", array());
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

    
    
    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public  function validate() {
        $errors = array();
        if (!(($this->Title) )) {
            $errors[] = "Incorrect Title";
        }
        if (!(($this->Body)  )) {
            $errors[] = "Body must be filled";
        }
        return $errors;
    } 
    public  function validates() {
        $errors = array();
        if (!($this->Body)) {
            $errors[] = "Body must be filled";
        }
        return $errors;
    }
    public function name($AuthorId){
        return User::get_user_by_UserId($AuthorId)->FullName.' ';
    }
    //revoir les answer et autorid d'un post
    public static function get_All_Answer_by_postid($PostId){
         $query = self::execute("select *  from  post where ParentId = :PostId "
                 . " ORDER BY Timestamp DESC",array("PostId" =>$PostId));
         $data = $query->fetchAll();
         $resul=[];
            foreach ($data as $row) {
                $resul [] = $post[] = new Post( $row['AuthorId'],$row['Title'], $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row['PostId']);
            }
            return $resul;
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
    
   // renvoir le nombre de reponse sur une question  
    public function get_All_Answer_By_Post($PosId){
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
        } else {        
                self::execute("UPDATE post SET  AuthorId=:AuthorId, Title=:Title, Body=:Body, Timestamp=:Timestamp,AcceptedAnswerId=:AcceptedAnswerId, ParentId=:ParentId WHERE PostId=:PostId ", 
                          array("AuthorId" => $this->AuthorId, "Title" => $this->Title, "Body" => $this->Body,
                "Timestamp"=> $this->Timestamp, "AcceptedAnswerId" => $this->AcceptedAnswerId, "ParentId" => $this->ParentId,"PostId"=> $this->PostId));  
                          var_dump("ok")   ;
                     return $this;         
        }
    }

    public static function get_newest() {
                $query = self::execute(("select * from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL group by PostId ORDER BY Timestamp DESC"), array());
        $data = $query->fetchAll();
        $newest = [];
        foreach ($data as $value) {
            $newest[] = new Post( $value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"],$value["PostId"]);
        }
        return $newest;
    }
    public function delete() {
            self::execute(("DELETE FROM post WHERE PostId =:PostId"), array("PostId" => $this->PostId));
            
    }
    
    public static function get_unanswere(){
        $query = self::execute("SELECT * FROM post where ParentId IS NULL and  AcceptedAnswerId IS NULL order by Timestamp DESC", array());
        $data = $query->fetchAll();
         $result = [];
        foreach ($data as $value) {
            $result[] = new Post( $value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"],$value["PostId"]);
        }
        return $result;
    }
    public static function getJours(){
        $query = self::execute("SELECT Timestamp from post, vote where vote.PostId=post.PostId GROUP BY PostId");
        $data = $query->fetch();
        $date1= $data;
        $date2= date("Y-m-d H:i:s");
        $nbreJours= (strtotime($date2)-strtotime($date1))/86400;
        return $nbreJours;
    }
    public static function getName(){
        $query= self::execute("SELECT UserName from user,post where user.UserId=post.AuthorId ",array("user" => $this->UserName));
        return $query->fetchAll();
    }
    public  function count_Answer($PostId){
        $query = self::execute(("SELECT count(AcceptedAnswerId) as nbranswer from post  WHERE PostId =:PostId group by PostId"), array("PostId" => $PostId));
        return $query->fetch()["nbranswer"];
    }
    public function nbr_vote($PostId) {
        $query = self::execute(("SELECT SUM(UpDown) as nbrvote FROM vote  where PostId=:PostId"), array("PostId" => $PostId));
            return $query->fetch()["nbrvote"];    
    }
    
}
