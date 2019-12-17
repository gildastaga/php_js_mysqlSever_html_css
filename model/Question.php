<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Question
 *
 * @author Alain
 */
class Question {
    
    
    public function validate(){
        $errors = array();
        if(!(isset($this->author) && is_a($this->author,"Member") && Member::get_member_by_pseudo($this->author->pseudo))){
            $errors[] = "Incorrect author";
        }
        if(!(isset($this->recipient) && is_a($this->recipient,"Member") && Member::get_member_by_pseudo($this->recipient->pseudo))){
            $errors[] = "Incorrect recipient";
        }
        if(!(isset($this->body) && is_string($this->body) && strlen($this->body) > 0)){
            $errors[] = "Body must be filled";
        }
        if(!(isset($this->private) && is_bool($this->private))){
            $errors[] = "Private status must be boolean";
        }
        return $errors;
    }
    
    public static function get_qestion() {
        $query = self::execute("SELECT * FROM user", array());
        $data = $query->fetchAll();
        $results = [];
        foreach ($data as $row) {
            $results[] = new User($row["UserName"], $row["Password"], $row["FullName"], $row["Email"]);
        }
        return $results;
    } 
}
