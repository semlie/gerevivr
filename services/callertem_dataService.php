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
require_once  realpath(dirname(__FILE__)) . '/../models/caller_item.php';
require_once  realpath(dirname(__FILE__)) . '/../models/sql_model.php';
require_once  realpath(dirname(__FILE__)) . '/../config.php';

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
        parent::__construct(Config::getConttext(), "caller_item");
    }

    public function Add(caller_item $callerItem) {

        $result = parent::Add($callerItem);
        $callerItem->Id = $result;
    }

    public function mapToModel($row) {
        $result = new caller_item;
        $result->Id = $row['Id'];
        $result->CallerId = $row['CallerId'];
        $result->Uid = $row['Uid'];
        $result->TimeStamp = $row['TimeStamp'];

        return $result;
    }

    public function GetInsertString($callerItem) {
        $sql = "INSERT INTO `ivr_orders`.`caller_item` (`Id`,`CallerId`, `Uid`,`TimeStamp`) "
                . "VALUES ('','" . $callerItem->CallerId . "', '" . $callerItem->Uid . "',CURRENT_TIMESTAMP);";
 return $sql;
    }

    public function GetUpdateString($callerItem) {
        $sql = "UPDATE `ivr_orders`.`caller_item` SET "
                . "`CallerId`='" . $callerItem->CallerId . "',"
                . " `Uid`='" . $callerItem->Uid . "' "
                . "WHERE `Id`='" . $callerItem->Id . "';";
        
        return $sql;
    }

}
