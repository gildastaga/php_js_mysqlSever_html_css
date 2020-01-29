<?php


class Vote extends Model{
    
    public $UserId;
    public $PostId;
    public $UpDowm;
    
    function __construct($UserId, $PostId, $UpDowm) {
        $this->UserId = $UserId;
        $this->PostId = $PostId;
        if ($UpDowm) {
            $this->UpDowm = 1;
        } else {
            $this->UpDowm = -1;
        }
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
            $votes[] = new Vote( $value["UserId"], $value["PostId"], $value["UpDown"]);
        }
        return $votes;
    }
    public static function countVote(){
        $query = self::execute(("SELECT count(UpDown) from vote where PostId in (SELECT PostId from post"),array());
        return $query->fetchAll();
    }

    
    
}
