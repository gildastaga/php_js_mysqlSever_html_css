<?php

require_once 'model/User.php';
require_once 'model/Post.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser extends Controller {

    public function index() {
        $this->redirect($controller="post", $action="index");
    }
    
    public function log_out() {
        parent::logout();
    }

    public function login() {
        $UserName = '';
        $Password = '';
        $errors = [];
        if (isset($_POST['UserName']) && isset($_POST['Password'])) {
            $UserName = $_POST['UserName'];
            $Password = $_POST['Password'];
            $errors = User::validate_login($UserName, $Password);
            if (empty($errors)) {
                $this->log_user(User::get_member_by_username($UserName));
            }
        }
        (new View("login"))->show(array("UserName" => $UserName, "Password" => $Password, "errors" => $errors));
    }

    public function signup() {

        $UserName = '';
        $Password = '';
        $Password_confirm = '';
        $FullName = '';
        $Email = '';
        $errors = [];
        if (isset($_POST['UserName']) && isset($_POST['Password']) && isset($_POST['Password_confirm']) && isset($_POST['FullName']) && isset($_POST['Email'])) {
            $UserName = trim($_POST['UserName']);
            $Password = $_POST['Password'];
            $Password_confirm = $_POST['Password_confirm'];
            $FullName = trim($_POST['FullName']);
            $Email = $_POST['Email'];
            $user = new User($UserName, Tools:: my_hash($Password), $FullName, $Email);
            $errors = User::validate_unicity($UserName);
            $errors = User::validate_unicityEmail($Email);            
            $errors = array_merge($errors, $user->validate());
            $errors = array_merge($errors, User::validate_passwords($Password, $Password_confirm));
            if (count($errors) == 0) {
                $user->update();
                $use= User::get_member_by_username($user->UserName);
                $this->log_user($use);
            }
        }
        (new View("signup"))->show(array( "UserName" => $UserName, "FullName" => $FullName, "Password" => $Password,
            "Password_confirm" => $Password_confirm, "Email" => $Email, "errors" => $errors));
    }
    
    public function UserName_available_service(){
        $res = "true";
        if(isset($_POST["UserName"]) && $_POST["UserName"] !== ""){
            $user = User::get_member_by_UserName($_POST["UserName"]);
            if($user){
                $res = "false";
            }
        }
        echo $res;
    }
    public function Email_available_service(){
        $res = "true";
        if(isset($_POST["Email"]) && $_POST["Email"] !== ""){
            $user = User::get_email($_POST["Email"]);
            if($user){
                $res = "false";
            }
        }
        echo $res;
    }
    public function start() {
        
        (new View("start"))->show(array());
    }
    
}
