<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of caller_item
 *
 * @author Admin
 */
class caller_item {
    //put your code here
    
    public $id, $CallId, $Date;

    function __construct($id, $callId, $date) {
        $this->id = $id;
        $this->CallId = $callId;
        $this->Date = $date;
    }

}
