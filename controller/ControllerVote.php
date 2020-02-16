<?php



require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/Post.php';
require_once 'model/Vote.php';
require_once 'model/User.php';

class ControllerVote extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $posts = Vote::votes();
        $t=FALSE;
        $errors = [];
        (new View("index"))->show(array("posts" => $posts, "user" => $user, "errors" => $errors,"t"=>$t));
    }

//    public function vot() {
//        $user = $this->get_user_or_redirect();
//        $posts = Vote::votes();
//        $errors = [];
//        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
//    }
    public function add_vote () {    
        $user = $this->get_user_or_false();
        $PostId = $_GET['param1'];
        if(isset($_GET['param2'])&& $_GET['param2']==1){  
            $id= $_GET['param3'];
            $UpDown="1";
            $vote = new Vote($user->UserId, $PostId,$UpDown);     
        }elseif($_GET['param2']!=1){
            $UpDown="-1";
            $id= $_GET['param2'];
            $vote = new Vote($user->UserId,$PostId,$UpDown);  
        }
        if(!Vote::get_vote($vote->PostId, $vote->UserId)){
            $vote->update();
            $this->redirect("post","show",$id);
        }else{      
            $vote->delete();    
             $this->redirect("post","show",$id);
        }       
    }

}
