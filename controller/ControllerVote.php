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
        $nbpage=5;
        $currentPage=(int)($_GET['param1']??1);
        $offset=$nbpage*($currentPage -1);
        $nbr=ceil(count(Post::get_total())/$nbpage);
        $posts = Post::getvotes($nbpage,$offset);
        $errors = [];
        (new View("index"))->show(array("posts" => $posts, "user" => $user, "errors" => $errors,"currentPage"=>$currentPage,"nbr"=>$nbr,"action"=>"index"));
    }
    public function indexJson() {
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param1'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage);
        $posts = Post::getvotes($nbpage, $offset);
        foreach($posts as $post) {
            $post->markdown = $post->markdown();
            $post->temp = $post->temp_ago()[0];
            $post->name = $post->name();
            $post->tags = Tag::get_tag_bypostId($post->PostId);
            $post->nbr_vote = Post::nbr_vote($post->PostId);
            $post->count_Answer = $post->count_Answer();
        }
        
        $data = [];
        $data["user"] = $user;
        $data["posts"] = $posts;
        $data["currentPage"] = $currentPage;
        $data["nbr"] = $nbr;
        echo json_encode($data);
    }
    public function add_vote() {
        $user = $this->get_user_or_false(); 
        $PostId= Tools::sanitize($_GET['param1']);;
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
