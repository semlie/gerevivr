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
require_once  realpath(dirname(__FILE__)).'/ModelInfo.php';

class order_item extends ModelInfo{

    //put your code here
    //public $Uid, $Name, $Address, $Phone, $Satus;
    public  $OrderId, $ProductId, $CollerId, $Quantity;

}
