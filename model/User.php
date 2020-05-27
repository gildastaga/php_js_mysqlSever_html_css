<?php

require_once "framework/Model.php";
require_once "model/Post.php";
require_once "model/Comment.php";

class User extends Model {

    public $UserName;
    public $hashed_password;
    public $FullName;
    public $Email;
    public $UserId;
    public $Role;

//    public $tab=[user,admin];

    public function __construct($UserName, $hashed_password, $FullName, $Email, $Role = "user", $UserId = -1) {
        $this->UserId = $UserId;
        $this->UserName = $UserName;
        $this->hashed_password = $hashed_password;
        $this->FullName = $FullName;
        $this->Email = $Email;
        $this->Role = $Role;
    }

    public static function validate_login($UserName, $Password) {
        $errors = [];
        $user = User::get_member_by_username($UserName);
        if ($user) {
            if (!self::check_password($Password, $user->hashed_password)) {
                $errors[] = "Wrong password. Please try again.";
            }
        } else {
            $errors[] = "Can't find a member with the username '$UserName'. Please sign up.";
        }
        return $errors;
    }

    // renvoie le UserId d'un user
    public function getUserId($UserName) {
        $query = self::execute("SELECT * FROM user where UserName = :UserName ", array("UserName" => $UserName));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return $data[0];
        }
    }

    public function validate() {
        $errors = array();
        if (!(isset($this->UserName) && is_string($this->UserName) && strlen($this->UserName) > 0)) {
            $errors[] = "UserName is required.";
        } if (!(isset($this->UserName) && is_string($this->UserName) && strlen($this->UserName) >= 3 && strlen($this->UserName) <= 16)) {
            $errors[] = "UserName length must be between 3 and 16.";
        } if (!(isset($this->UserName) && is_string($this->UserName) && preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->UserName))) {
            $errors[] = "UserName must start by a letter and must contain only letters and numbers.";
        }
        return $errors;
    }

    private static function validate_password($Password) {
        $errors = [];
        if (strlen($Password) < 8 || strlen($Password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $Password)) && preg_match("/\d/", $Password) && preg_match("/['\";:,.\/?\\-]/", $Password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }

    public static function validate_passwords($Password, $Password_confirm) {
        $errors = User::validate_password($Password);
        if ($Password != $Password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }

    public static function validate_unicityEmail($Email) {
        $errors = [];
        $user = self::get_email($Email);
        if ($user) {
            $errors[] = "This Email already exists.";
        }
        return $errors;
    }

    public static function validate_unicity($UserName) {
        $errors = [];
        $user = self::get_member_by_username($UserName);
        if ($user) {
            $errors[] = "This user already exists.";
        }
        return $errors;
    }

    //indique si un mot de passe correspond à son hash
    private static function check_password($clear_password, $hash) {
        return $hash === Tools::my_hash($clear_password);
    }

    public function update() {
        if (self::get_member_by_username($this->UserName)) {
            self::execute("UPDATE User SET  UserName=:UserName,Password=:Password, FullName=:FullName, Email=:Email,Role=:Role  WHERE UserId:UserId ", array("UserName" => $this->UserName, "Password" => $this->hashed_password, "FullName" => $this->FullName, "Email" => $this->Email, "role" => $this->role));
        } else {
            self::execute("INSERT INTO User(UserName,Password,FullName,Email,Role) VALUES(:UserName,:Password,:FullName,:Email,:Role)", array("UserName" => $this->UserName, "Password" => $this->hashed_password, "FullName" => $this->FullName, "Email" => $this->Email, "Role" => $this->Role));
        }
        return $this;
    }

    public static function get_member_by_username($UserName) {
        $query = self::execute("SELECT * FROM user where UserName = :UserName", array("UserName" => $UserName));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["UserName"], $data["Password"], $data["FullName"], $data["Email"], $data["Role"], $data["UserId"]);
        }
    }

    public static function get_email($Email) {
        $query = self::execute("SELECT * FROM user where Email = :Email", array("Email" => $Email));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["UserName"], $data["Password"], $data["FullName"], $data["Email"], $data["Role"], $data["UserId"]);
        }
    }

    public static function get_user_by_UserId($UserId) {
        $query = self::execute("SELECT * FROM user where UserId = :UserId", array("UserId" => $UserId));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["UserName"], $data["Password"], $data["FullName"], $data["Email"], $data["Role"], $data["UserId"]);
        }
    }

    public function write_post($question) {
        return $question->update();
    }

    public function delete_post($question) {
        return $question->delete();
    }

    public function get_post() {
        return Post::get_post($this);
    }

    Public static function getActivity($time) {
        $query = self::execute("select UserName, SUM(activity) as activity from ((SELECT UserName,count(*) as activity ,UserId ,Timestamp from user join post on UserId = AuthorId where post.Timestamp >=:Time GROUP by UserName order by Timestamp DESC)
                                UNION
                                (SELECT UserName,count(*) as activity ,user.UserId ,Timestamp from user join comment on user.UserId= comment.UserId WHERE comment.Timestamp >=:Time  GROUP by UserName order by Timestamp DESC)) t
                                            GROUP BY UserName
                                            ORDER BY t.activity DESC ", array("Time" => $time));
        $resul = $query->fetchAll();
        return $resul;
    }

    public function activityByuser($time) {
        $query = self::execute("SELECT * FROM post WHERE post.AuthorId = :UserId AND post.TimeStamp>:time order by Timestamp DESC", array("UserId" => $this->UserId, "time" => $time));
        $data  = $query->fetchAll();
        $result = [];
        foreach ($data as $value) {
            if ($value["Title"] !== NULL) {
                $post = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
                $post->type = ["create/update post"];
                $post->moment = [$post->temp_ago()[0]];
                $result[] = $post;
            } else if ($value["Title"] == NULL) {
                $answer = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
                $post2 = Post::get_post_PostId($answer->PostId);
                $post2->type = ["create/update answer"];
                $post2->moment = [$answer->temp_ago()[0]];
                $result[] = $post2;
            }
        }
        $query1 = self::execute("SELECT * FROM comment  where comment.UserId = :UserId AND comment.TimeStamp>:time order by Timestamp DESC", array("UserId" => $this->UserId, "time" => $time));
        $data1 = $query1->fetchAll();
        foreach ($data1 as $row) {
            $c = new Comment($row["UserId"], $row["PostId"], $row["Body"], $row["Timestamp"], $row["CommentId"]);
            $post1 = Post::get_post_PostId($c->PostId);
            $post1->type = ["create/update comment"];
            $post1->moment = [$c->temp_ago()[0]];
            $result[] = $post1;
        }
        return $result;
    }

    public function functionName($time) {
        $query = self::execute("select * 
    from
        ((select post.Timestamp as timestamp, 'create/update question' as type, post.Title as question
        from  post WHERE post.ParentId !=NULL AND post.AuthorId = :UserId AND post.TimeStamp>:time )
        UNION ALL
        (select post.Timestamp as timestamp, 'create/update response' as type, question.Title as question
        from ( select * from post WHERE post.PostId =post.ParentId in(select * from post where post.ParentId != NULL AND post.AuthorId = :UserId AND post.TimeStamp>:time))
        UNION ALL
        (select comment.Timestamp as timestamp, 'create/update comment' as type, post.Title as question
        from comment  where  comment.UserId = :UserId AND comment.TimeStamp>:time )
        ) as tbl
     order by Timestamp DESC", array("UserId" => $this->UserId,"time" => $time));
        var_dump("je suis entre");
        $resul = $query->fetchAll();
        var_dump($resul);
        return $resul;
    }
    
    
    
    
    public function activityByuse($time) {
        $query = self::execute("SELECT * FROM post WHERE post.AuthorId = :UserId AND post.TimeStamp>:time order by Timestamp DESC", array("UserId" => $this->UserId, "time" => $time));
        $data  = $query->fetchAll();
        $result = [];
        foreach ($data as $value) {
           
            if (!$value["ParentId"]) {
                $post = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
                $post->type = ["create/update post"];
                $post->moment = [$post->temp_ago()[0]];
                $result[] = $post;
            } else {
                $post = Post::get_post_PostId($value["ParentId"]);
                $post->type = ["create/update answer"];
                $post->moment = [$post->temp_ago()[0]];
                $result[] = $post;
            }
        }

        $query1 = self::execute("SELECT * FROM comment  where comment.UserId = :UserId AND comment.TimeStamp>:time order by Timestamp DESC ", array("UserId" => $this->UserId, "time" => $time));
        $data1 = $query1->fetchAll();
        foreach ($data1 as $row) {
            $post = Post::get_post_PostId($row["PostId"]);
if (!$post->ParentId) {
                $post = new Post($value["AuthorId"], $value["Title"], $value["Body"], $value["Timestamp"], $value["AcceptedAnswerId"], $value["ParentId"], $value["PostId"]);
                $post->type = ["create/update comment"];
                $post->moment = [$post->temp_ago()[0]];
                $result[] = $post;
            } else {
                $post = Post::get_post_PostId($value["ParentId"]);
                $post->type = ["create/update comment"];
                $post->moment = [$post->temp_ago()[0]];
                $result[] = $post;
            }
        }

        return $result;
    }

    
    
    

}
