<?php


class Vote extends Model{
    
    public $UserId;
    public $PostId;
    public $UpDowm;
    
    function __construct($UserId, $PostId, $UpDowm) {
        $this->UserId = $UserId;
        $this->PostId = $PostId;
        if ($UpDowm) {
            $this->UpDowm = 1;
        } else {
            $this->UpDowm = -1;
        }
    }



    
    
}
