<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerPost extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $posts = $this->posts();
        (new View("index"))->show(array("user" => $user, "posts" => $posts));
    }

    public function posts() {
        $user = $this->get_user_or_false();
        $posts = Post::post();
        (new View("index"))->show(array("posts" => $posts, "user" => $user,));
    }

    public function question() {
        $user = $this->get_user_or_false();
        $posts = Post::affichepost();
        (new View("index"))->show(array("user" => $user, "posts" => $posts));
    }

    public function Ak_a_question() {
        $user = $this->get_user_or_false();
        $Ak_a = Post::Ak_a_question();
        (new View("index"))->show(array("user" => $user, "Ak_a_question" => $Ak_a));
    }
    public function newest(){
        $newest = Post::newest();
        (new View("index"))->show(Array("newest" => $newest));
        
    }

}
