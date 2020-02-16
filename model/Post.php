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
        $this->AuthorId = $AuthorId;
        $this->Title = $Title;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->AcceptedAnswerId = $AcceptedAnswerId;
        $this->ParentId = $Parentid;
        $this->PostId = $PostId;
    }

    public function temp_ago() {
        $datetime1 = new DateTime();
        $datetime2 = new DateTime($this->Timestamp);
        $interval = $datetime1->diff($datetime2);
        $tempago = array();
        if ($interval->y != 0) {
           $tempago [] =" " . ( $interval->y ) . " yaer(s)";
        }
        if ($interval->m != 0) {
            $tempago [] =" " . ( $interval->m ) . " month(s)";
        }
        if ($interval->d != 0) {
            $tempago [] =" " . ( $interval->d ) . " day(s)";
        }
        if ($interval->h != 0) {
            $tempago [] =" " . ( $interval->h ) . " heure(s)";
        }
        if ($interval->i != 0 ) {
            $tempago [] =" " . ( $interval->i ) . " minute(s)";
        }
        if($interval->s != 0){
            $tempago [] =" " . ( $interval->s ) . " second(s)";
        }
        if($interval->s < 1){
            $tempago [] ="a l'instant";
        }
        return $tempago;
    }
    public static function get_filter($search){
            $query= self::execute("select * from post where  Title LIKE :Title or Body LIKE :Body ",
                array("Title"=>"%".$search."%","Body"=>"%".$search."%"));
            $data=$query->fetchAll();
            $resul=[];
            foreach ($data as $row) {
                $resul[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row["PostId"]);
            }
             return $resul;         
    }

    public function markdown(){
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
    public function name(){
        return User::get_user_by_UserId($this->AuthorId)->FullName.' ';
    }
    //revoir les answer d'un post
    public  function get_All_Answer_by_postid(){
         $query = self::execute("select *  from  post where ParentId =:PostId "
                 . " ORDER BY Timestamp DESC",array("PostId" => $this->PostId));
         $data = $query->fetchAll();
         $resul=[];
            foreach ($data as $row) {
                $resul [] = $post[] = new Post( $row['AuthorId'],$row['Title'], $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"],$row['PostId']);
            }
            return $resul;
    } 
    
     // renvoir le nombre de reponse sur une question  
    public function get_nbre_Answer_By_Post(){
         $query = self::execute("select count(ParentId)as nbr_answer from  Post where ParentId = :ParentId ",
                 array("ParentId" => $this->PosId));
         $data = $query->fetch();
         if($data!=0){
            return $data["nbr_answer"];
         }
         return 0;  
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
    
     //renvoie le post d'un postid si trouver si non false
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
            return $this;
    }
    
    public static function get_unanswere(){
        $query = self::execute("SELECT * FROM post where ParentId IS NULL and  AcceptedAnswerId IS NULL group by PostId,ParentId order by Timestamp DESC", array());
        $data = $query->fetchAll();
         $result = [];
        foreach ($data as $value) {
            $result[] = new Post( $value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"],$value["PostId"]);
        }
        return $result;
    }
    
    public  function count_Answer(){
        $query = self::execute(("SELECT count(AcceptedAnswerId) as nbranswer from post  WHERE PostId =:PostId group by PostId"),
                array("PostId" => $this->PostId));
        return $query->fetch()["nbranswer"];
    }
    public function nbr_vote() {
        $query = self::execute(("SELECT SUM(UpDown) as nbrvote FROM vote  where PostId=:PostId"),
                array("PostId" => $this->PostId));
            $votenbr=$query->fetch();
        if($votenbr["nbrvote"]==0){
                return 0;
            }else{
            return $votenbr["nbrvote"];  
            }
    }
    public function get_vote() {
        $query = self::execute("SELECT * FROM vote where PostId =:PostId and UserId =:UserId",
                array("PostId" => $this->PostId, "UserId" => $this->AuthorId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return TRUE;
        }
    }
     public function valeur_vote() {
        if($this->get_vote()){
             $query = self::execute("SELECT * FROM vote where PostId =:PostId and UserId =:UserId",
                     array("PostId" => $this->PostId, "UserId" => $this->AuthorId));
             return $query->fetch()["UpDown"];
        }else{
             return FALSE;
        }                                                      
    }
    public static function delete_all_vote_in_fille($answers) {
        foreach ($answers as $ligne ){
            if($ligne->nbr_vote()!=0){
                Vote::deletes($ligne->PostId);
            }   
        }
    }
}
