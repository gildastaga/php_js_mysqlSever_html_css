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
        $posts= Post::post();
        (new View("index"))->show(array("posts"=>$posts,"user"=>$user,));
    }
    public function question(){
        $user = $this->get_user_or_false();
        $posts= Post::affichepost();
        (new View("index"))->show(array("user"=>$user,"posts"=>$posts));
    }
   
}
