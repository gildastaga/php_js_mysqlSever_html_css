<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerPost extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $posts = Post::affichepost();
        $errors = [];
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }

//controller Question: post/question
    public function questions() {
        $user = $this->get_user_or_false();
        $posts = Post::affichepost();
        $errors = [];
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }

//controller ask a quetion :post/ask a question
    public function Ak_a_question() {
        $user = $this->get_user_or_redirect();
        $Title = '';
        $Body = '';
        $errors = [];
        if (isset($_POST['Title']) && isset($_POST['Body'])) {
            $Title = $_POST['Title'];
            $Body = $_POST['Body'];
            $AuthorId = $user->UserId; 
            $Timestamp = date('Y-m-d H:i:s');
            $question = new Post($AuthorId, $Title, $Body, $Timestamp, NULL, NULL);
            $errors = $question->validate();
            if (count($errors) == 0) {
                $user->write_post($question);
            }
        }
        (new View("ask_a_question"))->show(array("user" => $user, "Body" => $Body, "Title" => $Title, "errors" => $errors));
    }

    //controller answer :post/anwer
    public function addanswer() {
        $user = $this->get_user_or_redirect();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $listanswer = Post::get_All_Answer_by_postid($PostId);
        $errors = [];
        if (isset($_POST['Body'])) {
            $Body = $_POST['Body'];
            $answered = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $posts->PostId);
            $errors = $answered->validates();
            if (count($errors) == 0) {
                $user->write_post($answered);
            }
        }
        (new View("show"))->show(array("user" => $user, "posts" => $posts, "listanswer" => $listanswer, "errors" => $errors));
    }

    //controller neswest :post/neswet
    public function newest() {
        $user = $this->get_user_or_false();
        $posts = Post::newest();
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
    }

    //detallePost
    //controller detallePost post/show/postid$posts
    public function show() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);        
        $listanswer = Post::get_All_Answer_by_postid($PostId);
        $errors = [];
        if (isset($_POST['body'])) {
            $body = $_POST['body'];
            $errors = $body->validate();
        }
        (new View("show"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors, "listanswer" => $listanswer));
    }

    // controller delete
    public function delete_confirm() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $errors = [];
        $posts = Post::get_quetion($PostId);   
        (new View("delete"))->show(array("user" => $user,"errors" => $errors, "posts" => $posts));
    }

    public function am_ok_delete() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $errors = []; 
        if ($posts->AuthorId==$user->UserId) { 
            $posts->delete();
            $this->redirect("post","index");
        } else {
            $errors [] = "you had to be a member";
        }    
            (new View("delete"))->show(array("user" => $user,"errors" => $errors,"posts"=>$posts));
    }



    //control edit 
    public function edit() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $errors = [];
        (new View("edit"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors ));
    }

    public function postupdate() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $Body = '';
        $errors = [];
        if ($user) {
            if (isset($_POST['Body'])) {
                $Body = $_POST['Body'];
                if ($posts->AuthorId == $user->UserId) {
                    $AuthorId = $user->UserId; // ok
                    $Timestamp = date('Y-m-d H:i:s');
 //                   $ParentId = $posts->PostId;
                    $PostId = $posts->PostId;
                    $question = new Post($AuthorId, NULL, $Body, $Timestamp, NULL, NULL, $PostId);
                    $errors = $question->validates();
                    if (count($errors) == 0) {   
                       /// $question->update();
                        $user->write_post($question);
                        $this->redirect("post","index");
                    }
                } else {
                    $errors [] = "you must have been the author of the question";
                }
            }
        } else {
            $errors [] = "you had to be a member";
        }
        (new View("edit"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }

    public function unanswered() {
        $user = $this->get_user_or_false();
        $posts = Post::unanswere();
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
    }
    
    public function accept_answer() {
        $user = $this->get_user_or_redirect();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $Body = $posts->Body;
        $errors = [];
        $answered = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), $user->UserId, $posts->PostId,$posts->PostId);
        if($posts->AuthorId==$user->UserId){ 
            $user->write_post($answered);
        } else {
            $errors [] ="you had to be a member of the question to confirm an answer !";
        }
        (new View("index"))->show(array("user" => $user,"errors" => $errors, "posts" => $posts));
    }
}
