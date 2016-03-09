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
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../services/orderItem_dataService.php';

class order_item extends ModelInfo{

    //put your code here
    //public $Uid, $Name, $Address, $Phone, $Satus;
    public  $OrderId, $ProductId, $CollerId, $Quantity;

    public function Add() {
        $result = $this->dataService->Add($this);
        $this->Id = $result;
    }

    public function Update() {
        $result = $this->dataService->Update($this);
        $this->Id = $result;
    }

    public function GetInsertString() {
        $sql = "insert into `OrderItems` (`Id`, `OrderId`, `ProductId`, `CollerId`, `Quantity`, `TimeStamp`) "
                . "VALUES (NULL, '" . $this->OrderId . "', '" . $this->ProductId . "', '" . $this->CollerId . "', '" . $this->Quantity . "', CURRENT_TIMESTAMP);  ";
        return $sql;
    }

    public function GetUpdateString() {
        $sql = "update `OrderItems` set `OrderId` = '" . $this->OrderId . "', `ProductId`='" . $this->ProductId . "', `CollerId` = '" . $this->CollerId . "', `Quantity` ='" . $this->Quantity . "' WHERE `Id` = '" . $this->Id . "'";
        return $sql;
    }

}
