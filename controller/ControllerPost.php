<?php
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';

class ControllerPost extends Controller {
    public function index() {
        (new View("index"))->show();
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
