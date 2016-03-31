<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of orderInfo_dataService
 *
 * @author Admin
 */
require_once  realpath(dirname(__FILE__)) . '/data_service.php';
require_once  realpath(dirname(__FILE__)) . '/../models/order_item.php';
require_once  realpath(dirname(__FILE__)) . '/../models/sql_model.php';
require_once  realpath(dirname(__FILE__)) . '/../config.php';

class orderItem_dataService extends DataService implements sqlModel {

    public function __construct() {
        parent::__construct(Config::getConttext(), "orderitems");
    }

    public function Add(order_item $orderItem) {

        $result = parent::Add($orderItem);
        $orderItem->Id = $result;
    }

    public function mapToModel($row) {
        $result = new order_item;
        $result->CollerId = $row['CollerId'];
        $result->Id = $row['Id'];
        $result->OrderId = $row['OrderId'];
        $result->ProductId = $row['ProductId'];
        $result->Quantity = $row['Quantity'];
        $result->TimeStamp = $row['TimeStamp'];

        return $result;
    }

    public function GetInsertString($orderItem) {
        $sql = "insert into `OrderItems` (`Id`, `OrderId`, `ProductId`, `CollerId`, `Quantity`, `TimeStamp`) "
                . "VALUES (NULL, '" . $orderItem->OrderId . "', '" . $orderItem->ProductId . "', '" . $orderItem->CollerId . "', '" . $orderItem->Quantity . "', CURRENT_TIMESTAMP);  ";
        return $sql;
    }

    public function GetUpdateString($orderItem) {
        $sql = "update `OrderItems` set `OrderId` = '" . $orderItem->OrderId . "', `ProductId`='" . $orderItem->ProductId . "', `CollerId` = '" . $orderItem->CollerId . "', `Quantity` ='" . $orderItem->Quantity . "' WHERE `Id` = '" . $orderItem->Id . "'";
        return $sql;
    }
    
    public function GetAllItemsOfOrder($orderId){
        $sql = " SELECT * FROM `ivr_orders`.`OrderItems` WHERE `OrderItems`.`OrderId` = '".$orderId."'";

        $result = $this->selectQuery($sql);
        $modelResult = array();
         if ($result != FALSE) {
            while ($row = mysqli_fetch_assoc($result)) {
                // var_dump($row);
                $modelResult[] = $this->mapToModel($row);
            }
        }
        return $modelResult;

    }
}
