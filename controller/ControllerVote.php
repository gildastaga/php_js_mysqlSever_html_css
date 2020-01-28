<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/Post.php';


class ControllerVote extends Controller {
  
    public function index() {
         $user = $this->get_user_or_false();
        $votes = Vote::votes() ;
        $errors=[];
        (new View("index"))->show(array("user" => $user, "votes" => $votes,"errors"=>$errors));
    }
     public function votes() {
        $user = $this->get_user_or_false();
        $votes = Vote::votes();
        $errors=[];
        (new View("index"))->show(Array("votes" => $votes,"user" => $user,"errors"=>$errors));
        
    }

}

