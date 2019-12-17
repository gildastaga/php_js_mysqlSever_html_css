<?php

require_once 'model/User.php';
require_once 'model/Question.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser  extends Controller{
     public function index() {
        (new View("index"))->show();
    }
    
//    public function index() {
//        $this->profile();
//    }
    public function signup(){
        $UserName= '';
        $Password= '';
        $Password_confirm = '';
        $FullName= '';
        $Email= '';
        $errors = []; 
        
        if(isset($_POST['UserName']) && isset($_POST['Password']) && isset($_POST['Password_confirm'])&&
                                                    isset($_POST['FullName'])&& isset($_POST['$Email'])){
            $UserName =trim($_POST['UserName']);
            $Password = $_POST['Password'];
             $Password_confirm = $_POST['Password_confirm'];
            $FullName = trim($_POST['FullName']);
            $Email    =$_POST['Email'];
            
           $user = new User($UserName, Tools:: my_hash($Password),$FullName,$Email);
            $errors = User::validate_unicity($UserName);
            $errors = array_merge($errors, $user->validate());
            $errors = array_merge($errors, User::validate_passwords($Password, $Password_confirm));

            if (count($errors) == 0) { 
                $user->update(); 
                $this->log_user($user);
            }
        }
        (new View("signup"))->show(array("UserName" => $UserName,"FullName"=>$FullName ,"Password" => $Password,
            "Password_confirm"=>$Password_confirm,"Email"=>$Email, "errors" => $errors));
    }   
    public function profile() {
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $user = User::get_member_by_username($_GET["param1"]);
        }
        (new View("profile"))->show(array("user" => $user));
    }
}
