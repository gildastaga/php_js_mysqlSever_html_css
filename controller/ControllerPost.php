<?php

require_once 'framework/Utils.php';
require_once 'model/User.php';
require_once 'framework/View.php';
require_once 'framework/Controller.php';
require_once 'framework/Tools.php';
require_once 'model/Vote.php';

class ControllerPost extends Controller {

    public function index() {
        $user = $this->get_user_or_false();
        $posts = Post::get_all_post();
        $errors = [];
        $t = FALSE;
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors, "t" => $t));
    }

//controller post: post/question
    public function questions() {
        $user = $this->get_user_or_false();
        $posts = Post::get_all_post();
        $errors = [];
        $t = FALSE;
        (new View("index"))->show(array("user" => $user, "posts" => $posts, "errors" => $errors, "t" => $t));
    }

    //controller post :post/unanswered
    public function unanswered() {
        $user = $this->get_user_or_false();
        $posts = Post::get_unanswere();
        $errors = [];
        $t = FALSE;
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors, "t" => $t));
    }

    //controller neswest :post/neswet
    public function newest() {
        $user = $this->get_user_or_false();
        $posts = Post::get_newest();
        $t = FALSE;
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors, "t" => $t));
    }

    //controller post :post/ask a question
    public function Ak_a_question() {
        $user = $this->get_user_or_redirect();
        $Title = '';
        $Body = '';
        $errors = [];
        if (isset($_POST['Title']) && isset($_POST['Body'])) {
            $Title = Tools::sanitize($_POST['Title']);
            $Body = Tools::sanitize($_POST['Body']);
            $Timestamp = date('Y-m-d H:i:s');
            $question = new Post($user->UserId, $Title, $Body, $Timestamp, NULL, NULL);
            $errors = $question->validate();
            if (count($errors) == 0) {
                $user->write_post($question);
                $this->redirect("post", "index");
            }
        }
        (new View("ask_a_question"))->show(array("user" => $user, "Body" => $Body, "Title" => $Title, "errors" => $errors));
    }

    //detallePost
    //controller detallePost post/show/postid$posts
    public function show() {
        $user = $this->get_user_or_false();
        $PostId = Tools::sanitize($_GET['param1']);
        $posts = Post::get_post_PostId($PostId);
        $author = User::get_user_by_UserId($posts->AuthorId); //post parent 
        $listanswer = $posts->get_All_Answer_by_postid(); // post fille
        $errors = [];
        if (isset($_POST['Body'])) {
            $Body = Tools::sanitize($_POST['Body']);
            $answered = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $PostId);
            $errors = $answered->validates();
            if (count($errors) == 0) {
                $user->write_post($answered);
                $this->redirect("post", "show", $PostId);
            }
        }

        (new View("show"))->show(array("user" => $user, "author" => $author, "posts" => $posts, "errors" => $errors, "listanswer" => $listanswer));
    }

    public function postupdate() {
        $user = $this->get_user_or_false();
        $PostId = Tools::sanitize($_GET['param1']);
        $posts = Post::get_post_PostId($PostId);
        $errors = [];
        if (isset($_POST['Title']) && isset($_POST['Body'])) {
            $Title = Tools::sanitize($_POST['Title']);
            $Body = Tools::sanitize($_POST['Body']);
            $question = new Post($user->UserId, $Title, $Body, date('Y-m-d H:i:s'), $posts->AcceptedAnswerId, $posts->ParentId, $posts->PostId);
            $errors = $question->validate();
        } elseif (isset($_POST['Body'])) {
            $Body = Tools::sanitize($_POST['Body']);
            $question = new Post($user->UserId, NULL, $Body, date('Y-m-d H:i:s'), NULL, $posts->ParentId, $posts->PostId);
            $errors = $question->validates();
        }
        if ($posts->AuthorId == $user->UserId) {
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
        if (isset($_GET['param2'])) {
            if ($posts->AuthorId == $user->UserId) {
                if ($posts->AcceptedAnswerId != NULL) {
                    $parent = Post::get_post_PostId($posts->ParentId);
                    $answered = new Post($parent->AuthorId, $parent->Title, $parent->Body, date('Y-m-d H:i:s'), NULL, $parent->ParentId, $parent->PostId);
                    $posts = $user->write_post($answered);
                }
                if ($posts->nbr_vote() != 0) {
                    Vote::deletes($posts->PostId);
                }
                if ($posts->get_All_Answer_by_postid() != NULL) {
                    Tools::abort("!!!Cannot delete or update a parent row: a foreign key constraint fails");
                }
                $post = $posts->delete();
                if ($post->ParentId == NULL) {
                    $this->redirect("post", "index");
                } else {
                    $this->redirect("post", "show", $post->ParentId);
                }
            } else {
                $errors [] = "you had to be a author of post";
            }
        }
        (new View("delete"))->show(array("user" => $user, "errors" => $errors, "posts" => $posts));
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
            $id = Tools::sanitize($_GET['param3']);
            $answered = new Post($user->UserId, $question->Title, $question->Body, date('Y-m-d H:i:s'), $post->PostId, $question->ParentId, $question->PostId);
        } elseif ($_GET['param2'] != 1) {
            $id = Tools::sanitize($_GET['param2']);
            $answered = new Post($user->UserId, $question->Title, $question->Body, date('Y-m-d H:i:s'), NULL, $question->ParentId, $question->PostId);
        }
        if ($question->AuthorId == $user->UserId) {
            $user->write_post($answered);
            $this->redirect("post", "show", $id);
        } else {
            Tools::abort("you had to be a member of the post to confirm or refuse answer !");
        }
    }

    public function post_search() {
        $user = $this->get_user_or_false();
        $t = true;
        if (isset($_POST["search"])) {
            $param = Tools::sanitize($_POST['search']);
            $filter = '';
            if (User::get_member_by_username($param)) {
                $user = User::get_member_by_username($param);
                $filter = $user->UserId;
            } else {
                $filter = $param;
            }
            $mot = Utils::url_safe_encode($filter);
            $filters = Utils::url_safe_decode($mot);
            if (!$filters) {
                $this->redirect($_GET[0], $_GET[1], $_GET[2], $_GET[3], $_GET[4]);
                // Tools::abort("the parameter does not exist");
            }
            $posts = Post::get_filter($filters);
            $errors = [];
            (new View("index"))->show(array("posts" => $posts, "user" => $user, "errors" => $errors, "t" => $t));
        }
    }

}
