<?php


class ControllerPost extends Controller {
    public function index() {
        (new View("index"))->show();
    }
    
    public function signup(){
       
        $UserName= '';
        $Password= '';
        $Password_confirm = '';
        $FullName= '';
        $Email= '';
        $errors = [];
        
        if(isset($_POST['UserName']) && isset($_POST['Password']) && isset($_POST['Password_confirm'])&&
                isset($_POST['FullName'])&& isset($_POST['$Email'])){
            $UserName =trim($_POST['UserName']);
            $Password = $_POST['Password'];
             $Password_confirm = $_POST['Password_confirm'];
            $FullName = $_POST['FullName'];
            $Email    =$_POST['Email'];
            
           $user = new User($UserName, Tools:: my_hash($Password),$FullName,$Email);
           echo $user;
            $errors = User::validate_unicity($UserName);
            $errors = array_merge($errors, $user->validate());
            $errors = array_merge($errors, User::validate_passwords($Password, $Password_confirm));

            if (count($errors) == 0) { 
                $user->update(); 
                $this->log_user($user);
            }
        }
        (new View("signup"))->show(array("UserName" => $UserName,"FullName"=>$FullName ,"Password" => $Password,
            "Password_confirm"=>$Password_confirm,"Email"=>$Email, "errors" => $errors));
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
