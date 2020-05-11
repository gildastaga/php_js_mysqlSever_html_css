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

    public static function get_filter($search,$nbpage,$offset) {
        $query = self::execute("select * from post where  Title LIKE :Title or Body LIKE :Body LIMIT $nbpage OFFSET $offset ", array("Title" => "%" . $search . "%", "Body" => "%" . $search . "%"));
        $resul = [];
        if ($query->rowCount() == 0) {
            return 0;
        } else {
            $data = $query->fetchAll();
            foreach ($data as $row) {
                $post = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
                if ($post->ParentId != null) {
                    $postParent = Post::get_quetion($post->ParentId);
                    $resul[] = $postParent;
                } else {
                    $resul[] = $post;
                }
            }
            return $resul;
        }
    }

    public function markdown() {
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        $html = $Parsedown->text($this->Body);
        return $html;
    }

    public static function get_all_post($nbpage,$offset) {
        $query = self::execute("select * from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL GROUP BY PostId ORDER BY Timestamp DESC LIMIT $nbpage OFFSET $offset ", array());
        $array = $query->fetchAll();
        $resul = [];
        foreach ($array as $row) {
            $resul[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
        }
        return $resul;
    }

    public static function get_newest($nbpage,$offset) {
        $query = self::execute(("select * from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL group by PostId ORDER BY Timestamp DESC LIMIT $nbpage OFFSET $offset "), array());
        $data = $query->fetchAll();
        $newest = [];
        foreach ($data as $value) {
            $newest[] = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
        }
        return $newest;
    }

    //renvoie un tableau d'erreur(s) 
    //le tableau est vide s'il n'y a pas d'erreur.
    public function validate() {
        $errors = array();
        if (!(($this->Title) )) {
            $errors[] = "Incorrect Title";
        }
        if (!(($this->Body) )) {
            $errors[] = "Body must be filled";
        }
        return $errors;
    }

    public function validates() {
        $errors = array();
        if (!($this->Body)) {
            $errors[] = "Body must be filled";
        }
        return $errors;
    }

    public function name() {
        return User::get_user_by_UserId($this->AuthorId)->FullName . ' ';
    }

    //revoir les answer d'un post
    public function get_All_Answer_by_postid() {
        $query = self::execute("select *  from  post where ParentId =:PostId "
                        . " ORDER BY Timestamp DESC", array("PostId" => $this->PostId));
        $data = $query->fetchAll();
        $resul = [];
        foreach ($data as $row) {
            $resul [] = $post[] = new Post($row['AuthorId'], $row['Title'], $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row['PostId']);
        }
        return $resul;
    }

    // renvoir le nombre de reponse sur une question  
    public function get_nbre_Answer_By_Post() {
        $query = self::execute("select count(ParentId)as nbr_answer from  Post where ParentId = :ParentId ", array("ParentId" => $this->PosId));
        $data = $query->fetch();
        if ($data != 0) {
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
            return new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
        }
    }

    //renvoie le post d'un postid si trouver si non false
    public static function get_post_PostId($PostId) {
        $query = self::execute("select * from post  where PostId =:PostId", array("PostId" => $PostId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Post($row['AuthorId'], $row['Title'], $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row['PostId']);
        }
    }

    //renvoie tous les post d'un auteur ou false si null
    public static function getAllPost_by_user($user) {
        $query = self::execute("SELECT * FROM post where AuthorId = :UserId order by Timestamp DESC", array("UserId" => $user->UserId));
        $data = $query->fetchAll();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $resul = [];
            foreach ($data as $row) {
                $resul = $post[] = new Post($row['AuthorId'], $row['Title'], $row['Body'], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row['PostId']);
            }
            return $resul;
        }
    }

    //ajoute un post ou update un post
    public function update() {
        if ($this->PostId == -1) {
            self::execute("INSERT INTO post(AuthorId,Title,Body,Timestamp,AcceptedAnswerId,ParentId) "
                    . "VALUES(:AuthorId,:Title,:Body,:Timestamp,:AcceptedAnswerId,:ParentId)", array("AuthorId" => $this->AuthorId, "Title" => $this->Title, "Body" => $this->Body,
                "Timestamp" => $this->Timestamp, "AcceptedAnswerId" => $this->AcceptedAnswerId, "ParentId" => $this->ParentId));
            return $this;
        } else {
            self::execute("UPDATE post SET  AuthorId=:AuthorId, Title=:Title, Body=:Body, Timestamp=:Timestamp,AcceptedAnswerId=:AcceptedAnswerId, ParentId=:ParentId WHERE PostId=:PostId ", array("AuthorId" => $this->AuthorId, "Title" => $this->Title, "Body" => $this->Body,
                "Timestamp" => $this->Timestamp, "AcceptedAnswerId" => $this->AcceptedAnswerId, "ParentId" => $this->ParentId, "PostId" => $this->PostId));
            return $this;
        }
    }

    public function delete() {
        $query=self::execute(("select * FROM posttag WHERE PostId =:PostId"), array("PostId" => $this->PostId));
        $data = $query->fetchAll();
        foreach ($data as $value) {
            if($value["PostId"]== $this->PostId){
                self::execute(("DELETE FROM posttag WHERE PostId =:PostId"), array("PostId" => $this->PostId));
            }
        }
        self::execute(("DELETE FROM post WHERE PostId =:PostId"), array("PostId" => $this->PostId));
        return $this;
    }

    public static function get_unanswere($nbpage,$offset) {
        $query = self::execute("SELECT * FROM post where ParentId IS NULL and  AcceptedAnswerId IS NULL group by PostId,ParentId order by Timestamp DESC LIMIT $nbpage OFFSET $offset ", array());
        $data = $query->fetchAll();
        $result = [];
        foreach ($data as $value) {
            $result[] = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
        }
        return $result;
    }

    public function count_Answer() {
        $query = self::execute(("SELECT count(AcceptedAnswerId) as nbranswer from post  WHERE PostId =:PostId group by PostId"), array("PostId" => $this->PostId));
        return $query->fetch()["nbranswer"];
    }

    public static function nbr_vote($PostId) {
        $query = self::execute(("SELECT SUM(UpDown) as nbrvote FROM vote  where PostId=:PostId"), array("PostId" => $PostId));
        $votenbr = $query->fetch();
        if ($votenbr["nbrvote"] == 0) {
            return 0;
        } else {
            return $votenbr["nbrvote"];
        }
    }

    public static function delete_all_vote_in_fille($answers) {
        foreach ($answers as $ligne) {
            if ($ligne->nbr_vote() != 0) {
                Vote::deletes($ligne->PostId);
            }
        }
    }

    public static function getvotes($nbpage,$offset) {
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
                      ORDER BY q1.max_score DESC, timestamp DESC LIMIT $nbpage OFFSET $offset "), array());
        $data = $query->fetchAll();
        $post = [];
        foreach ($data as $value) {
            $post[] = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
        }
        return $post;
    }

    public static function getactive($nbpage,$offset) {
        $query = self::execute(("select question.PostId, question.AuthorId, question.Title, question.Body, question.ParentId, question.Timestamp, question.AcceptedAnswerId 
                from post as question, 
                     (select post_updates.postId, max(post_updates.timestamp) as timestamp from (
                        select q.postId as postId, q.timestamp from post q where q.parentId is null
                        UNION
                        select a.parentId as postId, a.timestamp from post a where a.parentId is not null
                        UNION
                        select c.postId as postId, c.timestamp from comment c 
                        UNION 
                        select a.parentId as postId, c.timestamp 
                        from post a, comment c 
                        WHERE c.postId = a.postId and a.parentId is not null
                        ) as post_updates
                      group by post_updates.postId) as last_post_update
                where question.postId = last_post_update.postId and question.parentId is null
                order by last_post_update.timestamp DESC LIMIT $nbpage OFFSET $offset "), array());
        $data = $query->fetchAll();
        $post = [];
        foreach ($data as $value) {
            $post[] = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
        }
        return $post;
    }

    public function get_posttag($PostId) {
        $query = self::execute("select *  from  posttag where TagId =:PostId ", array("PostId" => $this->PostId));
        $data = $query->fetchAll();
        return $data;
    }
    
    public static function get_post_bytag($TagId) {
        $query = self::execute(("select * from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL where PostId=:TagId group by PostId ORDER BY Timestamp DESC"),
                array("TagId"=>$TagId));
        $data = $query->fetchAll();
        $postByTag = [];
        foreach ($data as $value) {
            $postByTag[] = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
        }
        return $postByTag;
    }
    public static function get_AllPost_byTas($TagId,$nbpage,$offset) {
        $query = self::execute(("SELECT * FROM posttag where TagId=:TagId "), array("TagId"=>$TagId));
        $data = $query->fetchAll();   
        $postByTag = [];
        foreach ($data as $value) {
            $query1 = self::execute(("select * from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL and PostId=:PostId group by PostId ORDER BY Timestamp DESC  "),
                                                 array("PostId"=>$value["PostId"]));
            $data1 =$query1->fetchAll();
            foreach ($data1 as $row) {
                $postByTag[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
            }         
        }
        return $postByTag;
    }
    public static function get_AllPost_byTag($TagId,$nbpage,$offset) {        
//        $query = self::execute(("select * from  posttag  where TagId=:TagId"),array("TagId"=>$TagId));
//        $data = $query->fetchAll();
        $query = self::execute(("select * from post,posttag "
                . "where post.postid=posttag.postid  and  post.Body IS NOT NULL and post.Title IS NOT NULL and post.ParentId IS NULL "
                . "and posttag.TagId=:TagId GROUP BY post.PostId ORDER BY Timestamp DESC "),array("TagId"=>$TagId));
        $data = $query->fetchAll();        
        $postByTag = [];        
        foreach ($data as $row) {
            $postByTag[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
        }         
        return $postByTag;
    }
    public static function get_lasinset() {
        return Model::lastInsertId();
    }
    public static function get_total() {
       $query= self::execute("select *from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL",array());
       return  $query->fetchAll();
    }
    public static function get_AllPost_byTa($nbpage,$offset) {
        $query = self::execute(("SELECT * FROM posttag LIMIT $nbpage OFFSET $offset"), array());
        $data = $query->fetchAll();
        $postByTag = [];
        foreach ($data as $value) {
            $query1 = self::execute(("select * from post where Body IS NOT NULL and Title IS NOT NULL and ParentId IS NULL and PostId=:PostId group by PostId ORDER BY Timestamp DESC LIMIT $nbpage OFFSET $offset "),
                                                 array("PostId"=>$value["PostId"]));
            $data1 =$query1->fetchAll();
            foreach ($data1 as $row) {
                $postByTag[] = new Post($row["AuthorId"], $row["Title"], $row["Body"], $row["Timestamp"], $row["AcceptedAnswerId"], $row["ParentId"], $row["PostId"]);
            }         
        }
        return $postByTag;
    }
    
    public  function nbr_tag_bypost($PostId) {
        $query = self::execute(("SELECT count(TagId) as nbpost FROM posttag  where PostId=:PostId"), array("PostId" =>$PostId));
        $nbr = $query->fetch();
        if ($nbr["nbpost"] == 0) {
            return 0;
        } else {
            return $nbr["nbpost"];
        }
    }
}
