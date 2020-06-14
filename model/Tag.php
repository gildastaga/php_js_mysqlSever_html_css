<?php
require_once "framework/Model.php";
require_once "model/Post.php";
require_once "model/User.php";

class Tag extends Model {
    public $TagId;
    public $TagName;
    
    public function __construct($TagName,$TagId=-1) {
        $this->TagId = $TagId;
        $this->TagName = $TagName;
    }
    public function markdown() {
        $Parsedown = new Parsedown();
        $Parsedown->setSafeMode(true);
        $html = $Parsedown->text($this->Body);
        return $html;
    }
      
    public static function get_all_tag() {
        $query = self::execute("select * from tag ORDER BY TagName ", array());
        $array = $query->fetchAll();
        $resul = [];
        foreach ($array as $row) {
            $resul[] = new Tag($row["TagName"], $row["TagId"]);
        }
        return $resul;
    }
    
    public function valitag() {
        $errors = array();
        if (!($this->TagName) ||$this->TagName==" " ) {
            $errors[] = "TagName must be filled";
        }
        return $errors;
    }
    //return le tag selon son tagid ou flase 
    public static function get_tag($TagId) {
        $query = self::execute("SELECT * FROM tag where TagId =:TagId", array("TagId" => $TagId));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Tag($row["TagName"], $row["TagId"]);       
        }
    }
    //return le tag selon son nom ou flase 
    public static function get_tagbytagname($TagName) {
        $query = self::execute("SELECT * FROM tag where TagName =:TagName", array("TagName" => $TagName));
        if ($query->rowCount() == 0) {
            return false;
        } else {
            $row = $query->fetch();
            return new Tag($row["TagName"], $row["TagId"]);       
        }
    }
    
    //ajoute un tag ou update un tag
    public function update() {
        if ($this->TagId == -1) {
            self::execute("INSERT INTO tag(TagName)VALUES(:TagName)",array("TagName" => $this->TagName));
            return $this;
        } 
        else {
            self::execute("UPDATE tag SET  TagName=:TagName WHERE TagId=:TagId ",
                    array("TagName" =>$this->TagName, "TagId" => $this->TagId));
            return $this;
        }
    }
    public static function get_tag_Post($PostId,$TagId) {        
        $query = self::execute(("select * from  posttag  where PostId=:PostId and TagId=:TagId"),array("PostId"=>$PostId,"TagId" =>$TagId));
         $query->fetch();
        if ($query->rowCount() == 0) {
            return false;
        } else {
            return TRUE;
        }
    }
    
    public static function get_nbr_tag_Post($PostId) {        
        $query = self::execute(("select count(*) as nbpost from  posttag  where PostId=:PostId "),array("PostId"=>$PostId));
         $nbr = $query->fetch();
        if ($nbr["nbpost"] == 0) {
            return 0;
        } else {
            return $nbr["nbpost"];
        }
    }
    public static function nbr_post_bytag($TagId) {
        $query = self::execute(("SELECT count(PostId) as nbpost FROM posttag  where TagId=:TagId"), array("TagId" =>$TagId));
        $nbr = $query->fetch();
        if ($nbr["nbpost"] == 0) {
            return 0;
        } else {
            return $nbr["nbpost"];
        }
    }
    public static function get_tag_bypostId($PostId) {        
        $query = self::execute(("select * from  posttag  where PostId=:PostId"),array("PostId"=>$PostId));
        $data = $query->fetchAll();
        $TagbyPosid = [];
        foreach ($data as $value) {
            $query1= self::execute(("select * from tag where TagId =:TagId "),array("TagId"=>$value["TagId"]));
            $data1 = $query1->fetch();
            $TagbyPosid[] = new Tag($data1["TagName"], $data1["TagId"]);
        }
        return $TagbyPosid;
    }
    public function delete() {
        $query=self::execute(("select * FROM posttag WHERE TagId =:TagId"), array("TagId" => $this->TagId));
        $data = $query->fetchAll();
        foreach ($data as $value) {
            if($value["TagId"]== $this->TagId){
                self::execute(("DELETE FROM posttag WHERE TagId =:TagId"), array("TagId" => $this->TagId));
            }
        }
        self::execute(("DELETE FROM tag WHERE TagId =:TagId"), array("TagId" => $this->TagId));
        return $this;
    }
    public function dissocier_post_tag() {
        $query=self::execute(("select * FROM posttag WHERE TagId =:TagId"), array("TagId" => $this->TagId));
        $data = $query->fetchAll();
        foreach ($data as $value) {
            if($value["TagId"]== $this->TagId){
                self::execute(("DELETE FROM posttag WHERE TagId =:TagId"), array("TagId" => $this->TagId));
            }
        }
        return $this->TagId;
    }
    
    public static function associer_post_tag($PostId,$TagId) {
       self::execute("INSERT INTO posttag(PostId,TagId)VALUES(:PostId,:TagId)",
               array("PostId" =>$PostId,"TagId"=>$TagId));
          return Model::lastInsertId();
    }
    public static function ixist_association($PostId,$TagId) {
        $query=self::execute("select * FROM posttag WHERE TagId =:TagId and PostId=:PostId ",
               array("PostId" =>$PostId,"TagId"=>$TagId));
        if ($query->rowCount() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
