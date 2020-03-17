<?php

require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/Post.php';
require_once 'model/Vote.php';
require_once 'model/User.php';
require_once 'model/Tag.php';

class ControllerVote extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $posts = Post::getvotes();
        $errors = [];
        (new View("index"))->show(array("posts" => $posts, "user" => $user, "errors" => $errors));
    }
    public function add_vote() {
        $user = $this->get_user_or_false();        
        $post= Post::get_post_PostId($PostId);
       
        if (isset($_GET['param2']) && $_GET['param2'] == 1) {
            $UpDown = "1";
            $vote = new Vote($user->UserId, $PostId, $UpDown);                  
        } else {
            $UpDown = "-1";
            $vote = new Vote($user->UserId, $PostId, $UpDown);            
        }
        if (!Vote::get_vote($vote->PostId, $vote->UserId)) {
            $vote->update();
            if($post->ParentId==NULL){
               $this->redirect("post", "show", $post->PostId ); 
            }else{
                $this->redirect("post", "show", $post->ParentId ); 
            }
        } else {
            $vote->delete();
            if($post->ParentId==NULL){
               $this->redirect("post", "show", $post->PostId ); 
            } else {
                $this->redirect("post", "show", $post->ParentId);
            }           
        }
    }

}
