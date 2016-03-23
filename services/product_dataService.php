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
require_once  realpath(dirname(__FILE__)) . '/../models/product.php';
require_once  realpath(dirname(__FILE__)) . '/../models/sql_model.php';
require_once  realpath(dirname(__FILE__)) . '/../config.php';

class product_dataService extends DataService implements sqlModel {



    public function __construct() {
        parent::__construct(Config::getConttext(), "products");
    }

    public function Add(product $product) {

        $result = parent::Add($product);
        $product->Id = $result;
    }

    public function mapToModel($row) {
        $result = new product;
        $result->Id = $row['Id'];
        $result->Name = $row['Name']; 
        $result->Description = $row['Description'];
        $result->CatalogNumber = $row['CatalogNumber'];
        $result->FirstCategory = $row['FirstCategory'];
        $result->SecondaryCategory = $row['SecondaryCategory'];
        $result->Material = $row['Material'];
        $result->Manufacturer = $row['Manufacturer'];
        $result->Size = $row['Size'];
        $result->Strength = $row['Strength'];
        $result->Price = $row['Price'];
        $result->Thickness = $row['Thickness'];
        $result->Brand = $row['Brand'];
        $result->Color = $row['Color'];
        $result->Example = $row['Example'];
        $result->TimeStamp = $row['TimeStamp'];

        return $result;
    }
    
    function GetProductByCatalogNumber($catalogNumber) {
        $sql = " SELECT * FROM `ivr_orders`.`products` WHERE `products`.`CatalogNumber` = '".$catalogNumber."'";

        $result = $this->selectQuery($sql);
        $row = mysqli_fetch_assoc($result);
        $modelResult = !empty($row)? $this->mapToModel($row):'';
        return $modelResult;
        
    }
    public function GetInsertString($product) {
        $sql = "INSERT INTO `ivr_orders`.`products`(`Id`,`CatalogNumber`,`FirstCategory`,`SecondaryCategory`,`Material`,`Thickness`,`Color`,`Size`,`Price`,`Manufacturer`,`Brand`,`Strength`,`Example`,`Name`,`Description`)VALUES('',"
        . "'".$product->CatalogNumber."','".$product->FirstCategory."','".$product->SecondaryCategory."','".$product->Material."','".$product->Thickness."','".$product->Color."','".$product->Size."','".$product->Price."','".$product->Manufacturer."','".$product->Brand."','".$product->Strength."','".$product->Example."','".$product->Name."','".$product->Description."');";
        return $sql;
    }

    public function GetUpdateString($callerItem) {
        $sql = "update `OrderItems` set `OrderId` = '" . $callerItem->OrderId . "', `ProductId`='" . $callerItem->ProductId . "', `CollerId` = '" . $callerItem->CollerId . "', `Quantity` ='" . $callerItem->Quantity . "' WHERE `Id` = '" . $callerItem->Id . "'";
        return $sql;
    }

}
