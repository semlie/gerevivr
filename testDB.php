<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once __DIR__.'/models/order_item.php';
require_once __DIR__."/models/contects.php";
require_once __DIR__. '/services/orderItem_dataService.php';
$conttext = new contects("ivr_orders","root","","localhost");

$a = new \order_item($conttext);

$a->OrderId="14";
$a->ProductId="14";
$a->Quantity="1";
$a->CollerId="15";
$a->Uid=  uniqid();
$b =new orderItem_dataService;
$b->Add($a);


$a->Quantity="2";
$a->CollerId="13";
$b->Update($a);
$c =$b->GetAll();