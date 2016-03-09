<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of sql_model
 *
 * @author Admin
 */
interface sqlModel {
    //put your code here
    public function GetInsertString($object);
    public function GetUpdateString($object);
}
