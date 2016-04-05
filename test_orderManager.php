<?php

require_once realpath(dirname(__FILE__)) . '/services/order_manager.php';
require_once realpath(dirname(__FILE__)) . '/services/orderItem_dataService.php';
require_once realpath(dirname(__FILE__)) . '/models/order.php';
require_once realpath(dirname(__FILE__)) . '/models/order_item.php';

$a = new order();
$b = new order_item();

$as = new order_manager();

$orderId = $as->CreateNewOrder("54");

var_dump($orderId);

$as->AddNewItemForOrder("54", $orderId, "2", 3);
$as->AddNewItemForOrder("54", $orderId, "1", 2);

$calc = $as->CalculateOrder($orderId);

var_dump($calc);



