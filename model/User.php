<?php
require_once "framework/Model.php";
require_once "model/Post.php";
class User extends Model {
    public $UserName;
    public $hashed_password;
    public $FullName;
    public $Email;
    public $UserId;
    public $profile;
    

    public function __construct($UserName, $hashed_password,$FullName,$Email,$UserId=0) {
        $this->UserName = $UserName;
        $this->hashed_password = $hashed_password;
        $this->FullName = $FullName;
        $this->Email = $Email;
        $this->UserId = $UserId;
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
   /* public static function get_member_by_username($UserName) {
        $query = self::execute("SELECT * FROM User where UserName = :UserName", array("UserName"=>$UserName));
        $data = $query->fetch(); 
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["UserName"], $data["password"], $data["FullName"], $data["Email"], $data["UserId"]);
        }
    }*/
    
    public function validate(){
        $errors = array();
        if (!(isset($this->UserName) && is_string($this->UserName) && strlen($this->UserName) > 0)) {
            $errors[] = "Pseudo is required.";
        } if (!(isset($this->UserName) && is_string($this->UserName) && strlen($this->UserName) >= 3 && strlen($this->UserName) <= 16)) {
            $errors[] = "Pseudo length must be between 3 and 16.";
        } if (!(isset($this->UserName) && is_string($this->UserName) && preg_match("/^[a-zA-Z][a-zA-Z0-9]*$/", $this->UserName))) {
            $errors[] = "Pseudo must start by a letter and must contain only letters and numbers.";
        }
        return $errors;
    }
    
    private static function validate_password($Password){
        $errors = [];
        if (strlen($Password) < 8 || strlen($Password) > 16) {
            $errors[] = "Password length must be between 8 and 16.";
        } if (!((preg_match("/[A-Z]/", $Password)) && preg_match("/\d/", $Password) && preg_match("/['\";:,.\/?\\-]/", $Password))) {
            $errors[] = "Password must contain one uppercase letter, one number and one punctuation mark.";
        }
        return $errors;
    }
    
    public static function validate_passwords($Password, $Password_confirm){
        $errors = User::validate_password($Password);
        if ($Password != $Password_confirm) {
            $errors[] = "You have to enter twice the same password.";
        }
        return $errors;
    }
    
    public static function validate_unicity($UserName){
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
            self::execute("UPDATE User SET  Password=:Password, FullName=:FullName, Email=:Email  WHERE UserName=:UserName ",
                    array( "UserName" => $this->UserName, "Password" => $this->hashed_password,"FullName" => $this->FullName, "Email" => $this->Email));
        } else {
            self::execute("INSERT INTO User(UserName,Password,FullName,Email) VALUES(:UserName,:Password,:FullName,:Email)", 
                    array( "UserName" => $this->UserName, "Password" => $this->hashed_password,"FullName" => $this->FullName, "Email" => $this->Email));
        }
        return $this;
    }
    public static function get_member_by_username($UserName) {
        $query = self::execute("SELECT * FROM user where UserName = :UserName", array("UserName"=>$UserName));
        $data = $query->fetch(); // un seul résultat au maximum
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return new User($data["UserName"], $data["Password"], $data["FullName"], $data["Email"]);
        }
    }
    

    public function write_post($post) {
        return $post->update();
    }

    public function delete_post($question) {
        return $question->delete($this);
    }

    public function get_post() {
        return Post::get_post($this);
    }
    
    
}
