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

    public static function votes() {
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
        $votes = [];
        foreach ($data as $value) {
            $votes[] = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
        }
        return $votes;
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
