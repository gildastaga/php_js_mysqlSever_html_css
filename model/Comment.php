<?php
require_once "framework/Model.php";
require_once "model/Post.php";
class Comment extends Model {
    public $CommentId;
    public $UserId;    
    public $PostId;
    public $Body;
    public $Timestamp;
    function __construct($UserId, $PostId, $Body, $Timestamp,$CommentId=-1) {
        $this->CommentId = $CommentId;
        $this->UserId = $UserId;
        $this->PostId = $PostId;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
    }
    
     public function markdown() {
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        $html = $Parsedown->text($this->Body);
        return $html;
    }
    
     public function temp_ago() {
        $datetime1 = new DateTime();
        $datetime2 = new DateTime($this->Timestamp);
        $interval = $datetime1->diff($datetime2);
        $tempago = array();
        if ($interval->y != 0) {
            $tempago [] = " " . ( $interval->y ) . " yaer(s) ago";
        }
        if ($interval->m != 0) {
            $tempago [] = " " . ( $interval->m ) . " month(s) ago";
        }
        if ($interval->d != 0) {
            $tempago [] = " " . ( $interval->d ) . " day(s) ago";
        }
        if ($interval->h != 0) {
            $tempago [] = " " . ( $interval->h ) . " heure(s) ago";
        }
        if ($interval->i != 0) {
            $tempago [] = " " . ( $interval->i ) . " minute(s) ago";
        }
        if ($interval->s != 0) {
            $tempago [] = " " . ( $interval->s ) . " second(s) ago";
        }
        if ($interval->s < 1) {
            $tempago [] = "a l'instant";
        }
        return $tempago;
    }
    
    public static function get_all_comment() {
        $query = self::execute("select * from comment  GROUP BY CommentId ORDER BY Timestamp DESC", array());
        $array = $query->fetchAll();
        $resul = [];
        foreach ($array as $row) {
            $resul[] = new Comment($row["UserId"], $row["PostId"], $row["Body"], $row["Timestamp"], $row["CommentId"]);
        }
        return $resul;
    }
    
    public function valicomment() {
        $errors = array();
        if (!($this->Body)) {
            $errors[] = "Body must be filled";
        }
        return $errors;
    }
    
    public function name() {
        return User::get_user_by_UserId($this->UserId)->FullName . ' ';
    }
    
    public function get_nbre_Answer_By_Post() {
        $query = self::execute("select count(CommentId)as nbr_answer from  comment where PostId = :PostId ", array("PostId"=> $this->PostId));
        $data = $query->fetch();
        if ($data != 0) {
            return $data["nbr_answer"];
        }
        return 0;
    }
    
    // return false ou le comment du commentId
    public static function get_comment($CommentId) {
        $query = self::execute("SELECT * FROM comment where CommentId =:CommentId", array("CommentId" => $CommentId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Comment($row["UserId"], $row["PostId"], $row["Body"], $row["Timestamp"], $row["CommentId"]);       
        }
    }
    
    //ajoute un post ou update un post
    public function update() {
        if ($this->CommentId == -1) {
            self::execute("INSERT INTO post(UserId,PostId,Body,Timestamp)"
                    . "VALUES(:UserId,:PostId,:Body,:Timestamp)",
                    array("UserId" => $this->UserId, "PostId" => $this->PostId, "Body" => $this->Body,"Timestamp" => $this->Timestamp));
            return $this;
        } else {
            self::execute("UPDATE post SET  AuthorId=:AuthorId, Title=:Title, Body=:Body, Timestamp=:Timestamp,AcceptedAnswerId=:AcceptedAnswerId, ParentId=:ParentId WHERE PostId=:PostId ",
                    array("AuthorId" => $this->AuthorId, "Title" => $this->Title, "Body" => $this->Body,
                "Timestamp" => $this->Timestamp, "AcceptedAnswerId" => $this->AcceptedAnswerId, "ParentId" => $this->ParentId, "CommentId" => $this->CommentId));
            return $this;
        }
    }
}
