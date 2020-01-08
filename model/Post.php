<?php

class Post extends Model {

    public $postId;
    public $author;
    public $Title;
    public $Body;
    public $Timestamp;
    public $AcceptedAnwerId;
    public $Parentid;

    public function __construct($postId, $author, $Title, $Body, $Timestamp, $AcceptedAnwerId = null, $Parentid = null) {
        $this->postId = $postId;
        $this->author = $author;
        $this->Title = $Title;
        $this->Body = $Body;
        $this->Timestamp = $Timestamp;
        $this->AcceptedAnwerId = $AcceptedAnwerId;
        $this->ParentId = $Parentid;
    }

   
//  public static function affichepost() {
//
//        $query = self::execute("select * from post join user on user.UserId=post.PostId "
//                        . "where body IS NOT NULL and ParentId IS NULL ORDER BY Timestamp DESC", array());
//        $array = $query->fetchAll();
//
//        $resul = [];
//        foreach ($array as $row) {
//            $post = new Post($row["PostId"], $row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"]);
//            //$post[] = new Post($row['PostId'], $row['AuthorId'], $row['Title'], $row['Body'], $row['Timestamp'], $row['AcceptedAnswerId'], $row['ParentId']);
//            $resul[] = $post;
//        }
//        return $resul;
//    }

    public static function post() {

        $query = self::execute(( "SELECT post.*, max_score FROM post, (SELECT parentid, max(score) max_score
            FROM (
            SELECT post.postId, ifnull(post.parentid, post.postId) parentid, ifnull(sum(vote.updown), 0) score
            FROM post LEFT JOIN vote ON vote.postId = post.postId
            GROUP BY post.postId
            ) AS tbl1
            GROUP by parentid
            ) AS q1
            WHERE post.postId = q1.parentid
            ORDER BY q1.max_score DESC, timestamp DESC "), array());
        $data = $query->fetchAll();
        
        $results = [];
        foreach ($data as $row) {
            $post = new Post($row["PostId"], $row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"]);
            $results[] = $post;
        }
        return $results;
    }

}
