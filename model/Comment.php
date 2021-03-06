<?php

require_once "framework/Model.php";
require_once "model/Post.php";

class Comment extends Model {

    public $CommentId;
    public $UserId;
    public $PostId;
    public $Body;
    public $Timestamp;

    function __construct($UserId, $PostId, $Body, $Timestamp, $CommentId = -1) {
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

    public static function get_all_comment($PostId) {
        $query = self::execute("select * from comment where PostId=:PostId  ORDER BY Timestamp DESC", array("PostId" => $PostId));
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
        $query = self::execute("select count(CommentId)as nbr_answer from  comment where PostId = :PostId ", array("PostId" => $this->PostId));
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
    

    //ajout ou  update un comment
    public function update() {
        if ($this->CommentId == -1) {
            self::execute("INSERT INTO comment(UserId,PostId,Body,Timestamp)VALUES(:UserId,:PostId,:Body,:Timestamp)", array("UserId" => $this->UserId, "PostId" => $this->PostId, "Body" => $this->Body, "Timestamp" => $this->Timestamp));
            return $this;
        } else {
            self::execute("UPDATE comment SET  UserId=:UserId, PostId=:PostId, Body=:Body, Timestamp=:Timestamp WHERE CommentId=:CommentId ", array("UserId" => $this->UserId, "PostId" => $this->PostId, "Body" => $this->Body,
                "Timestamp" => $this->Timestamp, "CommentId" => $this->CommentId));
            return $this;
        }
    }

    public function delete() {
        self::execute(("DELETE FROM comment WHERE CommentId =:CommentId"), array("CommentId" => $this->CommentId));
        return $this;
    }

    public static function getActiviy($parm) {
        $query1 = self::execute("SELECT * FROM comment  where comment.UserId = :UserId AND comment.TimeStamp>:time ", array("UserId" => $this->UserId, "time" => $time));
        $data1 = $query1->fetchAll();
        $result =[];
        foreach ($data1 as $row) {
            $c = new Comment($row["UserId"], $row["PostId"], $row["Body"], $row["Timestamp"], $row["CommentId"]);
            $post = Post::get_post_PostId($c->PostId);
//            $post =[$parent->Title];
            $post->type = ["create/update comment"];
            $post->moment = [$c->temp_ago()];
            $result[] = $post;
        }
        return $result;
    }

}
