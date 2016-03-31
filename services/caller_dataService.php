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
require_once  realpath(dirname(__FILE__)) . '/../models/caller.php';
require_once  realpath(dirname(__FILE__)) . '/../models/sql_model.php';
require_once  realpath(dirname(__FILE__)) . '/../config.php';

class caller_dataService extends DataService implements sqlModel {

    
    public function __construct() {
        parent::__construct(Config::getConttext(), "caller");
    }

    public function Add(caller $caller) {

        $result = parent::Add($caller);
        $caller->Id = $result;
    }

    public function mapToModel($row) {
        $result = new caller;
        $result->Id = $row['Id'];
        $result->Name = $row['Name'];
        $result->Address = $row['Address'];
        $result->City = $row['City'];
        $result->PhoneNumber = $row['PhoneNumber'];
        $result->OtherPhone = $row['OtherPhone'];
        $result->Notes = $row['Notes'];
        $result->TimeStamp = $row['TimeStamp'];

        return $result;
    }

    public function GetInsertString($caller) {
        $sql = "INSERT INTO `ivr_orders`.`caller` (`Id`,`Name`, `Address`, `City`, `PhoneNumber`, `OtherPhone`, `Notes`,`TimeStamp`) "
        ."VALUES ('','".$caller->Name."', '".$caller->Address."', '".$caller->City."', '".$caller->PhoneNumber."', '".$caller->OtherPhone."', '".$caller->Notes."',CURRENT_TIMESTAMP);";

              return $sql;
    }

    public function GetUpdateString($caller) {
        $sql ="UPDATE `ivr_orders`.`caller` SET "
                . "`Name`='" . $caller->Name . "', "
                . "`Address`='" . $caller->Address . "', "
                . "`City`='" . $caller->City . "', "
                . "`PhoneNumber`='" . $caller->PhoneNumber . "', "
                . "`OtherPhone`='" . $caller->OtherPhone . "', "
                . "`Notes`='" . $caller->Notes . "' "
                . "WHERE `Id`='" . $caller->Id . "';";
        return $sql;
    }
    public function GetCallerByPhoneNumber($phoneNumber) {
        
        $sql = " SELECT * FROM `ivr_orders`.`caller` WHERE `caller`.`PhoneNumber` = '".$phoneNumber."'";

        $result = $this->selectQuery($sql);
        $row = ($result != FALSE) ? mysqli_fetch_assoc($result) : '';
        $modelResult = ($row > 0) ? $this->mapToModel($row) : '';

        return $modelResult;
    }
}
