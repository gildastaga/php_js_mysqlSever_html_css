<?php
require_once 'framework/Utils.php';
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerPost extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $posts = Post::get_all_post();
        $errors = [];
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }

//controller Question: post/question
    public function questions() {
        $user = $this->get_user_or_false();
        $posts = Post::get_all_post();
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
        if($user){
            if (isset($_POST['Body'])) {
                $Body = $_POST['Body'];
                $answered = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $posts->PostId);
                $errors = $answered->validates();
                if (count($errors) == 0) {
                    $user->write_post($answered);
                }
            }
        }else {
            $this->redirect("post", "index") ;
        } 
        (new View("show"))->show(array("user" => $user, "posts" => $posts, "listanswer" => $listanswer, "errors" => $errors));
    }
        public function postupdate() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $Body = '';
        $errors = [];
        if ($user) {
            if (isset($_POST['Title']) && isset($_POST['Body'])) {
            $Title = $_POST['Title'];
            $Body = $_POST['Body'];
            $question = new Post($AuthorId, $Title, $Body,  date('Y-m-d H:i:s'), $posts->AcceptedAnswerId, $posts->ParentId,$posts->PostId);
            $errors = $question->validate();
            }
            elseif (isset($_POST['Body'])) {
                $Body = $_POST['Body'];
                 $question = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, NULL, $posts->PostId);
                    $errors = $question->validates();
                if ($posts->AuthorId == $user->UserId ) {
                   if (count($errors) == 0) {   
                       /// $question->update();
                        $user->write_post($question);
                        $this->redirect("post","index");
                    }
                } else {
                    $errors [] = "you must have been the author of the question";
                }
            }
        }else {
            $this->redirect("post", "index") ;
        } 
        (new View("edit"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }
    
    //controller neswest :post/neswet
    public function newest() {
        $user = $this->get_user_or_false();
        $posts = Post::get_newest();
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
    }

    //detallePost
    //controller detallePost post/show/postid$posts
    public function show() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId); 
        $author= User::get_user_by_UserId($posts->AuthorId);
        $listanswer = Post::get_All_Answer_by_postid($PostId);
        $errors = [];
        (new View("show"))->show(array("user" => $user,"author"=>$author, "posts" => $posts, "errors" => $errors, "listanswer" => $listanswer));
    }

    // controller delete
    public function delete_confirm() {
        if($user = $this->get_user_or_false()){
            $PostId = $_GET['param1'];
            $errors = [];
            $posts = Post::get_quetion($PostId); 
        }else {
            $this->redirect("post", "index") ;
        } 
        (new View("delete"))->show(array("user" => $user,"errors" => $errors, "posts" => $posts));
    }

    public function am_ok_delete() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $errors = []; 
        if ($posts->AuthorId==$user->UserId) { 
            if($posts->ParentId!=NULL){
                $errors[]="contact the admin";
                $errors [] = "Integrity constraint violation :ParentId!=NULL ";
            }else{    
                $posts->delete();
                $this->redirect("post","index");
            }    
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



    public function unanswered() {
        $user = $this->get_user_or_false();
        $posts = Post::get_unanswere();
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
    }
    
    public function accept_answer() {
        $user = $this->get_user_or_redirect();
        $PostId = $_GET['param1'];
        $posts= Post::get_all_post();
        $post = Post::get_quetion($PostId);//ligne de la reponse
        $question= Post::get_quetion($post->ParentId);// ligne de la question
        $errors = [];
        $answered = new Post($user->UserId, $post->Title, $post->Body, date('Y-m-d H:i:s'), $user->UserId, $post->PostId,$post->PostId);
        if($question->AuthorId==$user->UserId && $post->AcceptedAnswerId==NULL){ 
            $user->write_post($answered);
            $this->redirect("post","index");
        } else {
            $errors [] ="you had to be a member of the question to confirm an answer !";
        }
        (new View("index"))->show(array("user" => $user,"errors" => $errors, "posts" => $posts));
    }
    
    public function refuse_answer() {
        $user = $this->get_user_or_redirect();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);//ligne de la reponse
        $question= Post::get_quetion($posts->ParentId);// ligne de la question
        $errors = [];
        $answered = new Post($user->UserId, $posts->Title, $posts->Body, date('Y-m-d H:i:s'), NULL, $posts->PostId,$posts->PostId);
        if($question->AuthorId==$user->UserId && $posts->AcceptedAnswerId!=NULL){ 
            $user->write_post($answered);
            $this->redirect("post","index");
        } else {
            $errors [] ="you had to be a member of the question to confirm an answer !";
        }
        (new View("index"))->show(array("user" => $user,"errors" => $errors, "posts" => $posts));
    }
    
    public function search() {
        if(isset($_GET["param1"])){
            $filter=Utils::url_safe_decode($_GET["param1"]);
            if(!$filter){
                Tools::abort("mauvais parametre");
            }
            $posts= Post::filter($filter);            var_dump($post);
            (new View("index"))->show(array( "posts" => $posts));
        }
    }
    
    public function post_search() {
        if(isset($_POST["search"])){
            $filter= $_POST['search'];
            $this->redirect("post", "search", Utils::url_safe_encode($filter));
        }
    }
}
