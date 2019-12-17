<?php

require_once 'model/User.php';
require_once 'model/Question.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';

class ControllerUser  extends Controller{
    
    public function index() {
        $this->profile();
    }
    public function profile() {
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "") {
            $user = User::get_member_by_username($_GET["param1"]);
        }
        (new View("profile"))->show(array("user" => $user));
    }
    public function members(){
        $user = $this->get_user_or_redirect();
        $users = User::get_members();
      //  $view = ;
       (new View("users"))->show(array("users" => $users));
    }
}
