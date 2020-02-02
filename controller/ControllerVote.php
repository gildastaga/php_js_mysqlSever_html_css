<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/Post.php';


class ControllerVote extends Controller {
  
    public function index() {
        $user = $this->get_user_or_redirect();
//        $votes = Vote::votes() ;
//        $errors=[];
//        (new View("index"))->show(array("user" => $user,"errors"=>$errors));
    }
     public function vot() {
        $user = $this->get_user_or_false();        var_dump($user);
        $posts = Vote::post();
        var_dump($posts);
        $errors=[];
        (new View("index"))->show(Array("posts" => $posts,"user" => $user,"errors"=>$errors));
        
    }

}

