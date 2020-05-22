<?php

require_once 'model/User.php';
require_once 'model/Post.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'model/Tag.php';
require_once 'model/Vote.php';
class ControllerTag extends Controller{
  
    public function index() {
        $user = $this->get_user_or_false();
        $tag = Tag::get_all_tag();
         $errors = [];
        (new View("tag"))->show(array("tag" => $tag, "user" => $user,"errors" => $errors));
    }
    public function by_tag() {
        $user = $this->get_user_or_false();
        $posts = Post::get_post_bytag($TagId);
        $errors = [];
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }
    public function add_tag() {
        $user = $this->get_user_or_redirect();
        $tag= Tag::get_all_tag();
        $errors = [];
        $tagedit='';
        if(isset($_GET['param1'])){
            $TagId= Tools::sanitize($_GET['param1']);
            $tagedit= Tag::get_tag($TagId);
        } 
        if (isset($_POST['TagName'])&& $_POST["TagName"] !== " " && isset($_GET['param1']) ) {
            $TagName = Tools::sanitize($_POST['TagName']); 
            $tags = new Tag($TagName,$tagedit->TagId);
        }
        if (isset($_POST['TagName']) && $_POST["TagName"] !== " " && !isset($_GET['param1'] )) {           
            $TagName = Tools::sanitize($_POST['TagName']); 
            $tags = new Tag($TagName);
        }
        $tex=Tools::sanitize(" new tag name ");       
        if($TagName!=$tex){            
            if(Tag::get_tagbytagname($tags->TagName)){
                    $errors [] ="this tag already exist";
            }else{
                $errors = $tags->valitag();
                if(count($errors) === 0){ 
                    $user->write_post($tags);
                    $this->redirect("tag");
                }
            }
        }else{
            $errors [] ="put an acceptable TagName"; 
        }
        (new View("tag"))->show(array("user" => $user , "errors" => $errors,"tag"=>$tag));
    }
    
    public function delete_tag() {
        $user = $this->get_user_or_redirect();
        $TagId= Tools::sanitize($_GET['param1']);
        $posts=NULL;
        $comment=NULL;
        $errors = [];
        $tagdelete= Tag::get_tag($TagId);
        if(isset($_GET['param2']) && ($_GET['param2'])==1 ){ 
            $user->delete_post($tagdelete);
            $this->redirect("tag", "index");
        } 
        if(isset($_GET['param2']) && ($_GET['param2'])!=1){
            $tagdelete->dissocier_post_tag();
            $param2=Tools::sanitize($_GET['param2']);
            $this->redirect("post","show", $param2);
        }    
        (new View("delete"))->show(array("user" => $user, "errors" => $errors, "posts" => $posts,"comment"=>$comment,"tagdelete"=>$tagdelete));
    }
    
    public function asso_tag_post() {
        $PostId = Tools::sanitize($_GET['param1']);
        $posts = Post::get_post_PostId($PostId);// post fille
        if (isset($_POST['TagId'])) {
            $value= Tools::sanitize($_POST['TagId']); 
            $tagassocie= Tag::get_tag($value);
            if(Tag::ixist_association($posts->PostId, $tagassocie->TagId)){
                $this->redirect("post","show", $PostId);
            } else {  
                if($posts->nbr_tag_bypost($posts->PostId)<Configuration ::get("max_tags")){
                    Tag::associer_post_tag($posts->PostId, $tagassocie->TagId);  
                    $this->redirect("post","show", $PostId);
                } else {
                    $this->redirect("post","show", $PostId);
                }
            }
        }
    }
    public function TagName_available_service(){
        $res = "true";
        if(isset($_POST["TagName"]) &&( $_POST["TagName"] !== ""|| $_POST["TagName"] !== " ")){
            $TagName = Tag::get_tagbytagname($_POST["TagName"]);
            if($TagName){
                $res = "false";
            }
        }
        echo $res;
    }
    
}
