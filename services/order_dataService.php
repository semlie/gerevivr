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
require_once  realpath(dirname(__FILE__)). '/data_service.php';
require_once  realpath(dirname(__FILE__)). '/../models/order.php';
require_once  realpath(dirname(__FILE__)). '/../models/sql_model.php';


class order_dataService  extends DataService implements sqlModel{

        public function __construct() {
        parent::__construct(Config::getConttext(), "orders");
    }

    public function Add(order $order) {
        $result = parent::Add($order);
        $order->Id = $result;
    }
    
    public function GetInsertString($order) {
        $sql = "INSERT INTO `orders` (`Id`, `CallerItemId`, `TimeStamp`, `Is_Delivered`, `Is_Paid`, `TotalQuantity`, `TotalPrice`,`TotalItems`) VALUES "
                . "(NULL, '".$order->CallerItemId."', CURRENT_TIMESTAMP, '".$order->Is_Delivered."', '".$order->Is_Paid."', '".$order->TotalQuantity."', '".$order->TotalPrice."', '".$order->TotalItems."');";
        return $sql;
    }

    public function GetUpdateString($order) {
        $sql = "UPDATE `orders` SET "
                . "`CallerItemId`='".$order->CallerItemId."',"
                . "`Is_Delivered`='".$order->Is_Delivered."',"
                . "`Is_Paid`='".$order->Is_Paid."',"
                . "`TotalQuantity`='".$order->TotalQuantity."',"
                . "`TotalItems`='".$order->TotalItems."',"
                . "`TotalPrice`='".$order->TotalPrice."' "
                . "WHERE `Id` = '".$order->Id."'";
        return $sql;
    }

    public function mapToModel($row) {
        $model = new order;
        $model->Id = $row['Id'];
        $model->CallerItemId = $row['CallerItemId'];
        $model->TimeStamp = $row['TimeStamp'];
        $model->Is_Delivered = $row['Is_Delivered'];
        $model->Is_Paid = $row['Is_Paid'];
        $model->TotalPrice = $row['TotalPrice'];
        $model->TotalQuantity = $row['TotalQuantity'];
        $model->TotalItems = $row['TotalItems'];
        
        return $model;
    }


}
