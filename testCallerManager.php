<?php

require realpath(dirname(__FILE__)) .'/services/caller_manager.php';

$a = new caller_manager();
$b = $a->GetCallerIdByNumber('0527146368');

var_dump($b);

$d = $a->GetCallerIdByNumber('0527146367');

var_dump($d);

$rr =  $a->GetPhoneNumbar(1);
var_dump($rr);