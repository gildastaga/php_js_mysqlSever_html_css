<?php
require_once "framework/Controller.php";
require_once "framework/Configuration.php";
require_once "framework/Tools.php";
class ControllerUser  extends Controller{
    //put your code here
    public function index() {
        (new View("index"))->show();
    }

}
