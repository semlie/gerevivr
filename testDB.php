<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once  realpath(dirname(__FILE__)).'/models/order_item.php';
require_once  realpath(dirname(__FILE__))."/models/contects.php";
require_once  realpath(dirname(__FILE__))."/models/product.php";
require_once  realpath(dirname(__FILE__)). '/services/orderItem_dataService.php';
require_once  realpath(dirname(__FILE__)). '/services/order_dataService.php';
require_once  realpath(dirname(__FILE__)). '/services/product_dataService.php';
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

//var_dump($c);

$d = $b->getById("31");

echo "-----------";
//var_dump($d);

$e = new order;
$f = new order_dataService;
$e->CallerItemId = "2";
$e->Is_Delivered = true;
$e->Is_Paid = true;
$e->TotalPrice = 12;
$e->TotalQuantity = 2;
$f->Add($e);

$g = $f->GetAll();
var_dump($g);

$e->TotalPrice = rand(1,100);
$f->Update($e);

$h = $f->getById($e->Id);
echo '-=-=-=-=-=-=-=';
var_dump($h);

$aas = new product_dataService();

$aa = $aas->GetProductByCatalogNumber("10101");
var_dump($aa);
