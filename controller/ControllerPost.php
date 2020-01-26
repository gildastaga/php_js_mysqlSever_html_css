<?php

require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerPost extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $posts = Post::affichepost() ;
        $errors=[];
        (new View("index"))->show(array("user" => $user, "posts" => $posts,"errors"=>$errors));
    }
//controller Question: post/question
    public function questions() {
        $user = $this->get_user_or_false();
        $posts = Post::post();
        $errors=[];
        (new View("index"))->show(array("user" => $user, "posts" => $posts,"errors"=>$errors));
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
            $AuthorId = $user->UserId;// ok
            $Timestamp=date('Y-m-d H:i:s');
            $question = new Post($AuthorId, $Title, $Body,$Timestamp, NULL, NULL);
            $errors = $question->validate();
            if (count($errors) == 0) {
                $user->write_post($question);     
            }
        }
        (new View("ask_a_question"))->show(array("user" => $user,"Body" => $Body, "Title" => $Title,"errors"=>$errors));
    }
    
    public function unanswered(){
        
    }

    //controller answer :post/anwer
    public function addanswer() {
        $user= $this->get_user_or_redirect();
        $PostId=$_GET['param1'];
        $posts = Post::get_quetion($PostId) ;
         $answerauthor= Post::getAllAnswerAndAutorIdbypost($PostId);
        $errors = [];
        if (isset($_POST['Body'])) {
            $AcceptedAnswerId = $_POST['Body'];
            if(strlen($AcceptedAnswerId)!=0){
                $answered = new Post($user->UserId,$posts->Title,$posts->Body,date('Y-m-d H:i:s'), $AcceptedAnswerId,$posts->ParentId,$posts->PostId);
                var_dump($answered);
                $errors [] = $answered->validate();
                if(count($errors)==0){
                    $user->write_post($answered);                
                }
            }else{
                $errors [] ="you cannot add an empty answer";
            }    
        }
        (new View("show"))->show(array("user" => $user, "posts" => $posts,"answerauthor"=>$answerauthor,"errors"=>$errors));
    }
    //controller neswest :post/neswet
    public function newest() {
        $user = $this->get_user_or_false();
        $posts = Post::newest();
        $errors=[];
        (new View("index"))->show(Array("posts" => $posts,"user" => $user,"errors"=>$errors));
    }
    //detallePost
    //controller detallePost post/show/postid$posts
    public function show(){
        $user = $this->get_user_or_false();
        $PostId=$_GET['param1'];
        $posts = Post::get_quetion($PostId) ;
        $answerauthor= Post::getAllAnswerAndAutorIdbypost($PostId);
        $errors=[];
        if (isset($_POST['body'])) {
            $body = $_POST['body'];
            $errors = $body->validate();
        }
         (new View("show"))->show(array("user" => $user, "posts" => $posts,"errors"=>$errors,"answerauthor"=>$answerauthor));
    }
    // controller delete
    public function delete_confirm() { 
        $user = $this->get_user_or_false();
        $PostId=$_GET['param1'];
        $posts = Post::post($PostId) ;
        (new View("delete"))->show(array("user" => $user, "posts" => $posts)); 
    }
    public function am_ok_delete(){
        $post= $this->remove_post();  
        if($post){
           $user=$post->AuthorId;
           $this->redirect("post","index",$user->UserName);
        } else {
            $errors = [];
            $errors []="vous deviers etre l'auteur du post";
            (new View("error"))->show(array("errors" => $errors));
        }
    }
    //méthode delete post.
    private function remove_post() {
        $user = $this->get_user_or_false();
        var_dump($user);
        if (isset($_GET['param1'])) {
            $PostId = $_POST['param1'];
            var_dump($PostId);
            $post = Post::get_quetion($PostId);
            if ($post) {
              return  $user->delete_post($post);
            //    return $post->delete($user);
            } 
        }
        return false;
    }
    //control edit 
    public function edit() {
        $user= $this->get_user_or_false();
        $PostId=$_GET['param1'];
        $posts = Post::get_quetion($PostId) ;
        $errors = [];
        (new View("edit"))->show(array("user" => $user,"posts" => $posts,"errors"=>$errors));
    }
    public function postedit(){
        $user= $this->get_user_or_false();
        $PostId=$_GET['param1'];
        $posts = Post::get_quetion($PostId) ;
        $Body = '';
        $errors = [];
        if($user){ 
            if (isset($_POST['Body'])){
                $Body = $_POST['Body'];
                if($posts->AuthorId === $user->UserId){
                    $Title=$posts->Title;
                    $AuthorId = $user->UserId;// ok
                    $Timestamp=date('Y-m-d H:i:s');
                    $AcceptedAnswerId=$posts->AcceptedAnswerId;
                    $ParentId=$posts->ParentId;
                    $PostId=$posts->PostId;
                    $question = new Post($AuthorId, $Title, $Body,$Timestamp, $AcceptedAnswerId, $ParentId,$PostId);
                    $errors = $question->validate();
                    if (count($errors) == 0) {
                        $user->write_post($question); 
                        $this->redirect("post","index",$user->UserName);
                    }
                } else {
                     $errors[] ="you must have been the author of the question";
                }    
            }
        }else{
            $errors[] ="you had to be a member";
        }    
        (new View("edit"))->show(array("user" => $user,"posts" => $posts,"errors"=>$errors));
    }
}
