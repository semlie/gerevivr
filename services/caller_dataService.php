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
require_once __DIR__ . '/data_service.php';
require_once __DIR__ . '/../models/caller_item.php';
require_once __DIR__ . '/../models/sql_model.php';
require_once __DIR__ . '/../config.php';

class orderItem_dataService extends DataService implements sqlModel {

//    public function GetAll() {
//        $result = parent::GetAll();
//        $modelResult = array();
//         while ($row = mysqli_fetch_assoc($result)) {
//            // var_dump($row);
//            $modelResult[] = $this->mapToModel($row);
//        }
//        return $modelResult;
//    }

    public function __construct() {
        parent::__construct(Config::getConttext(), "OrderItems");
    }

    public function Add(caller_item $callerItem) {

        $result = parent::Add($callerItem);
        $callerItem->Id = $result;
    }

    public function mapToModel($row) {
        $result = new caller_item;
        $result->Id = $row['Id'];
        $result->CallId = $row['CallId'];
        $result->TimeStamp = $row['TimeStamp'];

        return $result;
    }

    public function GetInsertString($callerItem) {
        $sql = "insert into `OrderItems` (`Id`, `OrderId`, `ProductId`, `CollerId`, `Quantity`, `TimeStamp`) "
                . "VALUES (NULL, '" . $callerItem->OrderId . "', '" . $callerItem->ProductId . "', '" . $callerItem->CollerId . "', '" . $callerItem->Quantity . "', CURRENT_TIMESTAMP);  ";
        return $sql;
    }

    public function GetUpdateString($callerItem) {
        $sql = "update `OrderItems` set `OrderId` = '" . $callerItem->OrderId . "', `ProductId`='" . $callerItem->ProductId . "', `CollerId` = '" . $callerItem->CollerId . "', `Quantity` ='" . $callerItem->Quantity . "' WHERE `Id` = '" . $callerItem->Id . "'";
        return $sql;
    }

}
