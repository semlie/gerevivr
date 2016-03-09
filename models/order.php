<?php

require_once __DIR__ . '/../services/data_service.php';
require_once __DIR__ . "/sql_model.php";

class order extends DataService implements sqlModel {

    public $Id, $CallerItemId, $Date, $Is_Delivered, $Is_Paid,$TotalQuantity,$TotalPrice;

    function __construct(contects $contect) {

        parent::__construct($contect, "orders");
    }

    public function Add() {
        $result = parent::Add();
        $this->Id = $result;
    }

    public function GetInsertString() {
        $sql = "INSERT INTO `ivr_orders`.`orders` (`Id`, `CallerItemId`, `Date`, `Is_Delivered`, `Is_Paid`, `TotalQuantity`, `TotalPrice`) VALUES "
                . "(NULL, '".$this->CallerItemId."', CURRENT_TIMESTAMP, '".$this->Is_Delivered."', '".$this->Is_Paid."', '".$this->TotalQuantity."', '".$this->TotalPrice."');";
        return $sql;
    }

    public function GetUpdateString() {
        $sql = "UPDATE `orders` SET "
                . "`CallerItemId`='".$this->CallerItemId."',"
                . "`Is_Delivered`='".$this->Is_Delivered."',"
                . "`Is_Paid`='".$this->Is_Paid."',"
                . "`TotalQuantity`='".$this->TotalQuantity."',"
                . "`TotalPrice`='".$this->TotalPrice."' "
                . "WHERE `Id` = '".$this->Id."'";
        return $sql;
    }

}
