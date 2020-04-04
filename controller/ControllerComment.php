<?php

require_once 'model/User.php';
require_once 'model/Post.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Comment.php';

class ControllerComment extends Controller {

    public function index() {
        
    }

    public function add_comment() {        
        $user = $this->get_user_or_false();
        $Id = Tools::sanitize($_GET['param1']);
        $parent= Post::get_post_PostId($Id);
        $errors = [];
        $oldcomment = '';
        if (!isset($_GET['param2'])) {
            $Body = Tools::sanitize($_POST['Body']);
            $comment = new Comment($user->UserId, $Id, $Body, date('Y-m-d H:i:s'));
        } else {
            $oldcomment = Comment::get_comment($Id);
            $Body = Tools::sanitize($_POST['Body']);
            $comment = new Comment($user->UserId, $oldcomment->PostId, $Body, date('Y-m-d H:i:s'), $oldcomment->CommentId);
        }
        $errors = $comment->valicomment();
        if (count($errors) == 0) {
            $comment->update();
            if ($oldcomment == '' && $parent->Title!=NULL) {
                $this->redirect("post", "show", $Id);
            } elseif ($oldcomment == '' && $parent->Title==NULL) {
                $this->redirect("post", "show", $parent->ParentId);
            } elseif($oldcomment != '' ){
                $parent2= Post::get_post_PostId($oldcomment->PostId);
                if($parent2->Title==NULL){
                    $this->redirect("post", "show", $parent2->ParentId);
                } else {
                    $this->redirect("post", "show", $oldcomment->PostId);
                }
            }
        }    
        (new View("comment"))->show(array("user" => $user, "comment" => $comment, "errors" => $errors));
    }

    public function edit_comment() {
        $user = $this->get_user_or_false();
        $CommentId = Tools::sanitize($_GET['param1']);
        $comment = Comment::get_comment($CommentId);   
        $errors = [];
        (new View("comment"))->show(array("user" => $user, "comment" => $comment, "errors" => $errors));
    }

    public function delete_comment() {
        $user= $this->get_user_or_false();
        $CommentId = Tools::sanitize($_GET['param1']);
        $comment = Comment::get_comment($CommentId);
        $errors = [];
        $posts=NULL;
        $tagdelete=NULL;
        if (isset($_GET['param2'])) {
            if ($comment->UserId == $user->UserId || $user->Role=="Admin"){
                $oldcomment =$comment->delete(); 
                $parentcomment= Post::get_post_PostId($oldcomment->PostId);
                if($parentcomment->Title==NULL){
                    $this->redirect("post", "show", $parentcomment->ParentId);
                }else{
                    $this->redirect("post", "show", $oldcomment->PostId);
                }
            } 
        }    
        (new View("delete"))->show(array("user" => $user, "errors" => $errors, "posts" => $posts,"comment"=>$comment,"tagdelete"=>$tagdelete));
    }

}
