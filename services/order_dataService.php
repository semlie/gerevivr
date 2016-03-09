<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of order_dataService
 *
 * @author Admin
 */
require_once __DIR__. '/data_service.php';
require_once __DIR__. '/../models/order.php';

class order_dataService  extends DataService implements sqlModel{
    //put your code here
        public function Add(order $order) {
        $result = parent::Add($order);
        $this->Id = $result;
    }

    public function GetInsertString($order) {
        $sql = "INSERT INTO `ivr_orders`.`orders` (`Id`, `CallerItemId`, `Date`, `Is_Delivered`, `Is_Paid`, `TotalQuantity`, `TotalPrice`) VALUES "
                . "(NULL, '".$order->CallerItemId."', CURRENT_TIMESTAMP, '".$order->Is_Delivered."', '".$order->Is_Paid."', '".$order->TotalQuantity."', '".$order->TotalPrice."');";
        return $sql;
    }

    public function GetUpdateString($order) {
        $sql = "UPDATE `orders` SET "
                . "`CallerItemId`='".$order->CallerItemId."',"
                . "`Is_Delivered`='".$order->Is_Delivered."',"
                . "`Is_Paid`='".$order->Is_Paid."',"
                . "`TotalQuantity`='".$order->TotalQuantity."',"
                . "`TotalPrice`='".$order->TotalPrice."' "
                . "WHERE `Id` = '".$order->Id."'";
        return $sql;
    }

}
