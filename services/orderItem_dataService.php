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

require_once __DIR__. '/data_service.php';
require_once __DIR__. '/../models/order_item.php';
require_once __DIR__. '/../models/sql_model.php';
require_once __DIR__ . '/../config.php';

class orderItem_dataService extends DataService implements sqlModel{
        
    public function __construct() {
        parent::__construct(Config::getConttext(), "OrderItems");
    }
    
    public function Add(order_item $orderItem) {
        
            $result = parent::Add($orderItem);
            $orderItem->Id = $result;
    }

    public function GetInsertString($orderItem) {
      $sql = "insert into `OrderItems` (`Id`, `OrderId`, `ProductId`, `CollerId`, `Quantity`, `TimeStamp`) "
                . "VALUES (NULL, '" . $orderItem->OrderId . "', '" . $orderItem->ProductId . "', '" . $orderItem->CollerId . "', '" . $orderItem->Quantity . "', CURRENT_TIMESTAMP);  ";
        return $sql;
    }

    public function GetUpdateString($orderItem) {
       $sql = "update `OrderItems` set `OrderId` = '" . $orderItem->OrderId . "', `ProductId`='" . $orderItem->ProductId . "', `CollerId` = '" . $orderItem->CollerId . "', `Quantity` ='" . $orderItem->Quantity . "' WHERE `Id` = '" . $orderItem->Id . "'";

    }

    
}

