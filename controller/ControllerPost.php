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
      return  $posts = Post::post();
    }

    public function questions() {
        $user = $this->get_user_or_false();
        $posts = Post::post();
        (new View("index"))->show(array("user" => $user, "posts" => $posts));
    }
    
    public function Ak_a_question(){
        $user = $this->get_user_or_false();
        $Ak_a = true;
        $posts = Post::post();
        $Title = '';
        $Body = '';
        $errors = [];
        if (isset($_POST['Title']) && isset($_POST['Body'])) {
            $Title = $_POST['Title'];
            $Body = $_POST['Body'];
            $AuthorId=User::get_member_by_username($this->PostId->UserName);
            $question = new Post($AuthorId, $Title, $Body);
            $errors=$this->validate();
           // $question = new Pos($user, $Title, $Body);

        }
        (new View("index"))->show(array("user" => $user, "Ak_a" => $Ak_a,"posts" => $posts
                ,"Body"=>$Body,"Title"=>$Title));
    }

}
