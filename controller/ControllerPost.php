<?php

require_once 'framework/Utils.php';
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/Vote.php';
require_once 'model/Comment.php';
require_once 'model/Tag.php';

class ControllerPost extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param1'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage);
        $posts = Post::get_all_post($nbpage, $offset);
        (new View("index"))->show(array("user" => $user, "posts" => $posts,"currentPage" => $currentPage, "nbr" => $nbr, "action" => "index"));
    }

//controller post: post/question
    public function questions() {
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param1'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage);
        $posts = Post::get_all_post($nbpage, $offset);
        (new View("index"))->show(array("user" => $user, "posts" => $posts,  "currentPage" => $currentPage, "nbr" => $nbr, "action" => "questions"));
    }

    //controler post/active
    public function active() {
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param1'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage);
        $posts = Post::getactive($nbpage, $offset);
        $t = FALSE;
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "currentPage" => $currentPage, "nbr" => $nbr, "action" => "active"));
    }

    //controller post :post/unanswered
    public function unanswered() {
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param1'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage);
        $posts = Post::get_unanswere($nbpage, $offset);
        (new View("index"))->show(Array("posts" => $posts, "user" => $user,  "currentPage" => $currentPage, "nbr" => $nbr, "action" => "unanswered"));
    }

    //controller neswest :post/neswet
    public function newest() {
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param1'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage);
        $posts = Post::get_newest($nbpage, $offset);
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "currentPage" => $currentPage, "nbr" => $nbr, "action" => "newest"));
    }

    //controller post :post/ask a question
    public function Ak_a_question() {
        $user = $this->get_user_or_redirect(); 
        $Title = '';
        $Body = '';
        $errors = [];
        $tag = Tag::get_all_tag();
        if (isset($_POST['Title']) && $_POST["Title"] !== ""&& isset($_POST['Body'])&& $_POST["Body"] !== "") {
            $Title = Tools::sanitize($_POST['Title']);
            $Body = Tools::sanitize($_POST['Body']);
            $Timestamp = date('Y-m-d H:i:s');
            $post = new Post($user->UserId, $Title, $Body, $Timestamp, NULL, NULL);
            $errors = $post->validate();
            
            if (count($errors) == 0) {  
                if (!isset($_POST['TagName'])) {
                    $errors [] = "you had to add minimum 1 tag by post";
//                    $post->update();
//                    $this->redirect("post", "index");
                } else {
                    $taglis = ($_POST['TagName']);                    
                    if( count($taglis)<=Configuration ::get("max_tags")){
                        $post->update();
                        $lasinset = Post::get_lasinset();                        
                        foreach ($taglis as $value) {
                            $tagassocie = Tag::get_tagbytagname($value);
                            Tag::associer_post_tag($lasinset, $tagassocie->TagId);
                        }
                        $this->redirect("post", "index");
                    } else {                        
                        $max_tag=Configuration ::get("max_tags");                       
                        $errors [] = "you had to add maximum : ". $max_tag." tag by post";
                    }    
                }
            }
        }
        (new View("ask_a_question"))->show(array("user" => $user, "Body" => $Body, "Title" => $Title, "errors" => $errors, "tag" => $tag));
    }

    //detallePost
    //controller detallePost post/show/postid$posts
    public function show() {
        $user = $this->get_user_or_false();
        $PostId = Tools::sanitize($_GET['param1']);
        $posts = Post::get_post_PostId($PostId);
        $author = User::get_user_by_UserId($posts->AuthorId); //post parent 
        $listanswer = $posts->get_All_Answer_by_postid(); // post fille
        $tag = Tag::get_tag_bypostId($PostId);
        $tags = Tag::get_all_tag();
        $comment = Comment::get_all_comment($PostId);
        $errors = [];
        if (isset($_POST['Body'])&& $_POST["Body"] !== "") {
            $Body = Tools::sanitize($_POST['Body']);
            $answered = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $posts->PostId);
            $errors = $answered->validates();
            if (count($errors) == 0) {
                $user->write_post($answered);
                $this->redirect("post", "show", $PostId);
            }
        }

        (new View("show"))->show(array("user" => $user, "author" => $author, "posts" => $posts,
            "errors" => $errors, "listanswer" => $listanswer, "tag" => $tag, "tags" => $tags, "comment" => $comment));
    }

    public function postupdate() {
        $user = $this->get_user_or_false();
        $PostId = Tools::sanitize($_GET['param1']);
        $posts = Post::get_post_PostId($PostId);
        $errors = [];
        if (isset($_POST['Title']) && $_POST["Title"] !== "" && isset($_POST['Body'])&& $_POST["Body"] !== " ") {
            $Title = Tools::sanitize($_POST['Title']);
            $Body = Tools::sanitize($_POST['Body']);
            $question = new Post($user->UserId, $Title, $Body, date('Y-m-d H:i:s'), $posts->AcceptedAnswerId, $posts->ParentId, $posts->PostId);
            $errors = $question->validate();
        } elseif (isset($_POST['Body']) && $_POST["Body"] !== " ") {
            $Body = Tools::sanitize($_POST['Body']);
            $question = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $posts->ParentId, $posts->PostId);
            $errors = $question->validates();
        }
        if ($posts->AuthorId == $user->UserId|| $user->Role == "admin") {
            if (count($errors) == 0) {
                /// $question->update();
                $user->write_post($question);
                if ($posts->ParentId != NULL) {
                    $this->redirect("post", "show", $posts->ParentId);
                } else {
                    $this->redirect("post", "index");
                }
            }
        } else {
            $errors [] = "you must have been the author of the post";
        }
        (new View("edit"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }

    // controller delete
    public function delete_confirm() {
        $user = $this->get_user_or_false();
        $PostId = Tools::sanitize($_GET['param1']);
        $errors = [];
        $posts = Post::get_post_PostId($PostId);
        $comment = NULL;
        $tagdelete = NULL;
        if (isset($_GET['param2'])) {
            if ($posts->AuthorId == $user->UserId ||$user->Role == "admin") {
                if($posts->ParentId!=null){
                    $parent = Post::get_post_PostId($posts->ParentId);
                }else{
                    $parent=$posts;
                }
                
//                if ($posts->AcceptedAnswerId != NULL) {
//                    $answered = new Post($parent->AuthorId, $parent->Title, $parent->Body, date('Y-m-d H:i:s'), NULL, $parent->ParentId, $parent->PostId);
//                    $posts = $user->write_post($answered);
//                }
                if ($parent->AcceptedAnswerId!=NULL) {
                    Tools::abort("!!!Cannot delete a post accepte");
                } else {
                    if (Post::nbr_vote($posts->PostId) != 0) {
                        Vote::deletes($posts->PostId);
                    }
                    $postdelet = Post::get_post_PostId($posts->PostId);
                    $post = $postdelet->delete();
                    if ($post->ParentId == NULL) {
                        $this->redirect("post", "index");
                    } else {
                        $this->redirect("post", "show", $post->ParentId);
                    }
                }
            } else {
                $errors [] = "you had to be a author of post";
            }
        }
        (new View("delete"))->show(array("user" => $user, "errors" => $errors, "tagdelete" => $tagdelete, "posts" => $posts, "comment" => $comment));
    }

    //control edit 
    public function edit() {
        $user = $this->get_user_or_false();
        $PostId = Tools::sanitize($_GET['param1']);
        $posts = Post::get_post_PostId($PostId);
        $errors = [];
        (new View("edit"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors));
    }

    public function accept_and_refuse_answer() {
        $user = $this->get_user_or_redirect();
        $PostId = Tools::sanitize($_GET['param1']);
        $post = Post::get_post_PostId($PostId); //ligne de la reponse (fille)
        $question = Post::get_post_PostId($post->ParentId); // post parent 
        if (isset($_GET['param2']) && $_GET['param2'] == 1) {
            $answered = new Post($user->UserId, $question->Title, $question->Body, date('Y-m-d H:i:s'), $post->PostId, $question->ParentId, $question->PostId);
        } else if (!isset($_GET['param2'])) {
            $answered = new Post($user->UserId, $question->Title, $question->Body, date('Y-m-d H:i:s'), NULL, $question->ParentId, $question->PostId);
        }
        $reponsethis = new Post($post->AuthorId, $post->Title, $post->Body, date('Y-m-d H:i:s'), $post->AcceptedAnswerId, $post->ParentId, $post->PostId);
        if ($question->AuthorId == $user->UserId ||$user->Role == "admin") {
            $user->write_post($answered);
            $user->write_post($reponsethis);
            $this->redirect("post", "show", $post->ParentId);
        } else {
            Tools::abort("you had to be a member of the post to confirm or refuse answer !");
        }
        (new View("show"))->show(array("user" => $user, "posts" => $post,));
    }
    
    
    public function fiter() {
        if (isset($_POST["search"])) {
            $this->redirect("post", "post_search", Utils::url_safe_encode($_POST["search"]));
        }
    }

    public function post_search() {        
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param2'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage); 
        
        if (isset($_GET["param1"])) {
            $filter = Utils::url_safe_decode($_GET["param1"]);
            $posts = Post::get_filter($filter,$nbpage,$offset);
        }
        
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "currentPage" => $currentPage, "nbr" => $nbr, "action" => "post_search"));
    }

    public function by_tag() {
        $user = $this->get_user_or_false();
        $nbpage = 5;
        $currentPage = (int) ($_GET['param1'] ?? 1);
        $offset = $nbpage * ($currentPage - 1);
        $nbr = ceil(count(Post::get_total()) / $nbpage);
        $posts = "";
        $tag = "";
        $errors = [];
        if (isset($_GET['param1'])) {
            $TagId = Tools::sanitize($_GET['param1']);
            $tag = Tag::get_tag($TagId);
            $posts = Post::get_AllPost_byTag($tag->TagId,$nbpage,$offset);            
        } else {
            $posts = Post::get_AllPost_byTa($nbpage,$offset);
        }
            (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors, "currentPage" => $currentPage, "nbr" => $nbr, "action" => "by_tag"));
    }
    
    public function TagName_available_service(){
        $res = "true";
        if(isset($_POST["TagName"]) && $_POST["TagName"] !== ""){
            $TagName = Tools::sanitize($_POST['TagName']);
            $max_tag=Configuration ::get("max_tags");
            if(count($TagName)>$max_tag){
                $res = $max_tag;
            }
        }
        echo $res;
    }
    
    public function rech() {
        if(isset($_GET['motclef'])){ 
            $motclef=$_GET['motclef'];
            $result= Post::rechech($motclef); var_dump($result);
            return $result;
        }
    }
}
