<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of order_item
 *
 * @author Admin
 */
class order_item {

    //put your code here
    public $Uid, $Name, $Address, $Phone, $Satus;

    function __construct($uid, $name, $address, $phone, $satus) {
        $this->Uid = $uid;
        $this->Name = $name;
        $this->Address = $address;
        $this->Phone = $phone;
        $this->Satus = $satus;
    }

}
