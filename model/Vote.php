<?php

require_once 'framework/Model.php';
require_once "lib/parsedown-1.7.3/Parsedown.php";

class Vote extends Model {

    public $UserId;
    public $PostId;
    public $UpDown;

    public function __construct($UserId, $PostId, $UpDown) {
        $this->UserId = $UserId;
        $this->PostId = $PostId;
        $this->UpDown = $UpDown;
    }

    //ajoute un vote ou update un vote
    public function update() {
            self::execute("INSERT INTO vote(UserId, PostId, UpDown)VALUES(:UserId,:PostId, :UpDown)", 
                    array("UserId" => $this->UserId, "PostId" => $this->PostId, "UpDown" => $this->UpDown));
            return $this; 
    }
    public function delete() {
            self::execute(("DELETE FROM vote WHERE  UserId =:UserId and PostId =:PostId"),
                    array( "UserId" => $this->UserId,"PostId" => $this->PostId));
          return $this;
    }
    public static function deletes($PostId) {
           $query = self::execute(("DELETE FROM vote WHERE   PostId =:PostId"),
                    array( "PostId" => $PostId));
          return $query;
    }

    // return false ou le vote d'un postId
    public static function get_vote($PostId, $UserId) {
        $query = self::execute("SELECT * FROM vote where PostId =:PostId and UserId =:UserId", array("PostId" => $PostId, "UserId" => $UserId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Vote($row["UserId"], $row["PostId"], $row["UpDown"]);
        }
    } 
//nombre de vote par post
    public function nbr_vote($PostId) {
        $query = self::execute(("SELECT SUM(UpDown) as nbrvote FROM vote  where PostId=:PostId"), array("PostId" => $PostId));
        return $query->fetch()["nbrvote"];
    }

    //nbre de personne eyant vote pour un post
//    public function nbr_vote($PostId) {
//        $query = self::execute(("SELECT count(UserId) as nbrUser FROM vote  where PostId=:PostId"),
//                array("PostId" => $PostId));
//            return $query->fetch()["nbrUser"];    
//    }
}
