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

//controller post: post/question
    public function questions() {
        $user = $this->get_user_or_false();
        $posts = Post::get_all_post();
        $errors = [];
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }
    
    //controller post :post/unanswered
    public function unanswered() {
        $user = $this->get_user_or_false();
        $posts = Post::get_unanswere();
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
    }
    
     //controller neswest :post/neswet
    public function newest() {
        $user = $this->get_user_or_false();
        $posts = Post::get_newest();
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
    }
    
    //controller post :post/ask a question
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
    
    //detallePost
    //controller detallePost post/show/postid$posts
    public function show() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);        
        $author= User::get_user_by_UserId($posts->AuthorId);
        $listanswer = Post::get_All_Answer_by_postid($PostId);
        $errors = [];
        if (isset($_POST['Body'])) {
            $Body = $_POST['Body'];
            $answered = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $PostId);
            $errors = $answered->validates();
            if (count($errors) == 0) {
                $user->write_post($answered);
                $this->redirect("post", "show", $PostId);
            }
        }
        (new View("show"))->show(array("user" => $user,"author"=>$author, "posts" => $posts, "errors" => $errors, "listanswer" => $listanswer));
    }

    //controller post :post/anwer
    public function addanswer() {
        $user = $this->get_user_or_redirect();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId); 
        $listanswer = Post::get_All_Answer_by_postid($PostId);
        $errors = [];
        if (isset($_POST['Body'])) {
            $Body = $_POST['Body'];
            $answered = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $PostId);
            $errors = $answered->validates();
            if (count($errors) == 0) {
                $user->write_post($answered);
                $this->redirect("post", "show", $PostId);
            }
        }
        (new View("show"))->show(array("user" => $user, "posts" => $posts, "listanswer" => $listanswer, "errors" => $errors));
    }
    
    public function postupdate() {
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $posts = Post::get_quetion($PostId);
        $errors = [];
        
            if (isset($_POST['Title']) && isset($_POST['Body'])) {
                $Title = $_POST['Title'];
                $Body = $_POST['Body'];               
                $question = new Post($user->UserId, $Title, $Body,  date('Y-m-d H:i:s'), $posts->AcceptedAnswerId, $posts->ParentId ,$posts->PostId);
                $errors = $question->validate();
            }
            elseif (isset($_POST['Body'])) {
                $Body = $_POST['Body'];
                $question = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $posts->ParentId, $posts->PostId);
                $errors = $question->validates();
            }
            if ($posts->AuthorId == $user->UserId ) {
                if (count($errors) == 0) {   
                       /// $question->update();
                    $user->write_post($question);
                    if($posts->ParentId!=NULL){
                         $this->redirect("post","show",$posts->ParentId);
                    }else {
                        $this->redirect("post","index");
                    }
                }
            } else {
                $errors [] = "you must have been the author of the question";
            } 
        (new View("edit"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }

    // controller delete
    public function delete_confirm() {    
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        $errors = [];
        $posts = Post::get_quetion($PostId);
        if(isset($_GET['param2'])){
            if ($posts->AuthorId==$user->UserId) { 
                if($posts->AcceptedAnswerId!=NULL ){
                    $parent= Post::get_post_PostId($posts->ParentId);
                    $answered = new Post($parent->AuthorId, $parent->Title, $parent->Body, date('Y-m-d H:i:s'), NULL, $parent->ParentId,$parent->PostId);
                    var_dump($answered);              $posts=$user->write_post($answered);      
                }
                $post = $posts->delete();
                if($post->ParentId == NULL){
                    $this->redirect("post","index");
                }else {
                    $this->redirect("post", "show", $post->ParentId);
                }
                     
            }else{
                $errors [] = "you had to be a author of post";
            }
        }
        (new View("delete"))->show(array("user" => $user,"errors" => $errors, "posts" => $posts));
    }

    //control edit 
    public function edit() {
        $user = $this->get_user_or_false();
            $PostId = $_GET['param1'];
            $posts = Post::get_quetion($PostId);
            $errors = [];
            
        (new View("edit"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors ));
    }

    public function accept_and_refuse_answer() {
        $user = $this->get_user_or_redirect();
        $PostId = Tools::sanitize($_GET['param1']);
        $post = Post::get_quetion($PostId);//ligne de la reponse
        $question= Post::get_quetion($post->ParentId);// ligne de la question
        if(isset($_GET['param2'])&& $_GET['param2']==1){  
            $id= $_GET['param3'];
            
            $answered = new Post($user->UserId, $question->Title, $question->Body, date('Y-m-d H:i:s'), $post->PostId, $question->ParentId,$question->PostId);
        }elseif($_GET['param2']!=1){
            $id= $_GET['param2'];
            $answered = new Post($user->UserId, $question->Title, $question->Body, date('Y-m-d H:i:s'), NULL, $question->ParentId,$question->PostId);
        }
        if($question->AuthorId==$user->UserId ){ 
            $user->write_post($answered);
            $this->redirect("post","show",$id);
        } else {
            $errors  ="you had to be a member of the question to confirm an answer !";
            (new View("error"))->show(Array( "error" => $errors));
        }
    }
    
    public function search() {
        $user= $this->get_user_or_redirect();
        if(isset($_GET["param1"])){
            $filter=Utils::url_safe_decode($_GET["param1"]);
            if(!$filter){
                Tools::abort("mauvais parametre");
            }
            $posts= Post::filter($filter);  $errors=[]; 
            (new View("index"))->show(array( "posts" => $posts,"user"=>$user,"errors" => $errors));
        }
    }
    
    public function post_search() {
        if(isset($_POST["search"])){
            $filter= $_POST['search'];
            $this->redirect("post", "search", Utils::url_safe_encode($filter));
        }
    }
   
}
