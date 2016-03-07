<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of contects
 *
 * @author Admin
 */
class contects {
    //put your code here
    public $db ,$dbuser ,$dbpass ,$dbhost ;
    function __construct($db, $dbuser, $dbpass, $dbhost) {
        $this->db = $db;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
        $this->dbhost = $dbhost;
    }

    public function getDb() {
        return $this->db;
    }

    public function getDbuser() {
        return $this->dbuser;
    }

    public function getDbpass() {
        return $this->dbpass;
    }

    public function getDbhost() {
        return $this->dbhost;
    }

    public function setDb($db) {
        $this->db = $db;
    }

    public function setDbuser($dbuser) {
        $this->dbuser = $dbuser;
    }

    public function setDbpass($dbpass) {
        $this->dbpass = $dbpass;
    }

    public function setDbhost($dbhost) {
        $this->dbhost = $dbhost;
    }



}
