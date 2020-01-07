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

    public function validate() {
        $errors = array();
        if (!(isset($this->author) && is_a($this->author, "user") && User::get_member_by_username($this->author->UserNamme))) {
            $errors[] = "Incorrect author";
        }
        if (!(isset($this->Title) && is_a($this->Title, "Member") && User::get_member_by_username($this->Title->UserName))) {
            $errors[] = "Incorrect recipient";
        }
        if (!(isset($this->Body) && is_string($this->Body) && strlen($this->Body) > 0)) {
            $errors[] = "Body must be filled";
        }
        //if(!(isset($this->private) && is_bool($this->private))){
        //    $errors[] = "Private status must be boolean";
        // }
        return $errors;
    }

    public static function get_post() {
        $query = self::execute("select * from post where Title = :UserName order by Timestamp DESC", array());
        $data = $query->fetchAll();
        $post = [];
        foreach ($data as $row) {
            $post[] = new Post($row['postId'], User::get_member_by_username($row['author']), User::get_member_by_username($row['Title']), $row['Body'], $row['Timestamp']);
        }
        return $post;
    }

    public static function get_postid($postId) {
        $query = self::execute("select * from post where postId = :postId", array("postId" => $postId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Post($row['postId'], User::get_member_by_username($row['author']), User::get_member_by_username($row['Title']), $row['Body'], $row['Timestamp']);
        }
    }

    public function delete($initiator) {
        if ($this->author == $initiator || $this->Title == $initiator) {
            self::execute('DELETE FROM post WHERE postId = :postId', array('postId' => $this->postId));
            return $this;
        }
        return false;
    }

    public function update() {
        if ($this->postid == NULL) {
            $errors = $this->validate();
            if (empty($errors)) {
                self::execute('INSERT INTO post (author, Title, body) VALUES (:author,:Title,:body)', array(
                    'author' => $this->author->UserName,
                    'Title' => $this->Title->UserName,
                    'Body' => $this->Body,
                        // 'private' => $this->private ? 1 : 0
                ));
                $post = self::get_postid(self::lastInsertId());
                $this->postid = $post->postid;
                $this->Timestamp = $post->Timestamp;
                return $this;
            } else {
                return $errors; //un tableau d'erreur
            }
        } else {
            //on ne modifie jamais les posts : pas de "UPDATE" SQL.
            throw new Exception("Not Implemented.");
        }
    }

    public static function affichepost() {
        
        $query = self::execute("select * from post join user on UserId=Post.AuthordId "
                        . "where body is NOT NULL and ParentIdIS NULL ORDER BY Timesamp DESC", array());
        $array = $query->fetchAll();
        $post=[];
        foreach ($array as $row) {
            $post = new Post($row['postId'],$row['author'], $row['Title'], $row['Body'], $row['Timestamp'],$row['AcceptedAnwerId'],$row['Parentid']);
        }
        return $post;
    }
    

    /* public  function print() {


      $query = self::execute(( SELECT post.*, max_score FROM post, (SELECT parentid, max(score) max_score
      FROM (
      SELECT post.postId, ifnull(post.parentid, post.postId) parentid, ifnull(sum(vote.updown), 0) score
      FROM post LEFT JOIN vote ON vote.postId = post.postId
      GROUP BY post.postId
      ) AS tbl1
      GROUP by parentid
      ) AS q1
      WHERE post.postId = q1.parentid
      ORDER BY q1.max_score DESC, timestamp DESC ),array())
      } */
}
