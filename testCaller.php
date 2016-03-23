<?php

require_once  realpath(dirname(__FILE__))."/models/caller.php";
require_once  realpath(dirname(__FILE__))."/models/caller_item.php";
require_once  realpath(dirname(__FILE__)). '/services/caller_dataService.php';
require_once  realpath(dirname(__FILE__)). '/services/calleritem_dataService.php';

$caller =  new caller();
$callerItem =  new caller_item();

$callerDs = new caller_dataService();
$callerItemDs = new callerItem_dataService();

$caller->Address= "Hertzog";
$caller->City= "beni brak";
$caller->Name= "Shmulik";
$caller->PhoneNumber= "0544430915";
$caller->OtherPhone= "0544430915";
$caller->Notes= "no note";

$a = $callerDs->Add($caller);
$b = $callerDs->getById($caller->Id);
var_dump($b);

$callerItem->CallerId = $caller->Id;
$callerItem->Uid = uniqid();

$c = $callerItemDs->Add($callerItem);
$e = $callerItemDs->getById($callerItem->Id);

var_dump($e);
