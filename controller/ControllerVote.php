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
        $errors = [];
        (new View("index"))->show(array("posts" => $posts, "user" => $user, "errors" => $errors));
    }

    public function vot() {
        $user = $this->get_user_or_redirect();
        $posts = Vote::votes();
        $errors = [];
        (new View("index"))->show(Array("posts" => $posts, "user" => $user, "errors" => $errors));
    }

}
