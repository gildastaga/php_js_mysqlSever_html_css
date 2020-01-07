<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerPost extends Controller {
    public function index() {
        $user = $this->get_user_or_false();      
        if ($this->user_logged()) {
            $this->redirect("post","index");
        } else {
            (new View("index"))->show(array("user"=>$user));   
        }
    }
   
    public function posts(){
        $user = $this->get_user_or_false();
        $post= Post::affichepost();
        var_dump($post);
        (new View("index"))->show(array( "user" => $user, "post" => $post));
    }
   

//    public function posts(){   
//        $user = $this->get_user_or_false();
//        $Title = $this->get_title($user);
//        $errors = [];
//        
//        if (isset($_POST['Body'])) {
//            $errors = $this->post($user, $Title);
//        }
//        $post = $Title->get_post();
//        
//       $post =Post::affichepost($user);
//        (new View("index"))->show(array("Title" => $Title, "user" => $user,
//            "post" => $post, "errors" => $errors));    
//    }
    
   
//    private function get_title($user){
//        if(!isset($_GET["param1"]) || $_GET["param1"]==""){
//            return $user;
//        } else{
//            return User::get_member_by_username($_GET["param1"]);
//        }
//    }
//    public function post($user, $Title) {
//        $errors = [];
//        if (isset($_POST['Body'])) {
//            $body = $_POST['Body'];
//            $private = isset($_POST['private']) ? TRUE : FALSE;
//            $post = new Post($user, $Title, $body, $private);
//            $errors = $post->validate();
//            if(empty($errors)){
//                $user->write_post($post);                
//            }
//        }
//        return $errors;
//        
//    }    
}
