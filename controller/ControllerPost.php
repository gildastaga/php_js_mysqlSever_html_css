<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerPost extends Controller {
    public function index() {
        $this->questions();
    }
    public function questions(){
        
        $user = $this->get_user_or_redirect();
        $recipient = $this->get_recipient($user);
        $errors = [];
        
        if (isset($_POST['body'])) {
            $errors = $this->post($user, $recipient);
        }
        $question = $recipient->get_question();
//        $message =Message::get_messages($user);
        (new View("questions"))->show(array("recipient" => $recipient, "user" => $user,
            "question" => $question, "errors" => $errors));    
    }
    
   
    private function get_recipient($user){
        if(!isset($_GET["param1"]) || $_GET["param1"]==""){
            return $user;
        } else{
            return User::get_member_by_username($_GET["param1"]);
        }
    }
    public function post($user, $recipient) {
        $errors = [];
        if (isset($_POST['body'])) {
            $body = $_POST['body'];
            $private = isset($_POST['private']) ? TRUE : FALSE;
            $question = new Question($user, $recipient, $body, $private);
            $errors = $question->validate();
            if(empty($errors)){
                $user->write_question($question);                
            }
        }
        return $errors;
        
    }
//    public function index() {
//        $user = $this->get_user_or_redirect();
//        $recipient = $this->get_recipient($user);
//        $errors = [];
//        //le code suivant est exécuté si javascript n'est pas activé.
//        //(le formulaire en question ne POST rien si JS est activé)
//        if (isset($_POST['body'])) {
//            $errors = $this->post($user, $recipient);
//        }
//
//        $messages = $recipient->get_messages();
//        (new View("questions"))->show(array("recipient" => $recipient, "user" => $user, "messages" => $messages, "errors" => $errors));
//    }
    
 

}
