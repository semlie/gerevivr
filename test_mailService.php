<?php

require_once realpath(dirname(__FILE__)) . '/services/mail_service.php';
require_once realpath(dirname(__FILE__)) . '/services/order_manager.php';
require_once realpath(dirname(__FILE__)) . '/services/caller_manager.php';


$mail = new mail_service();
$om = new order_manager();
$calMang=  new caller_manager();

$order = $om->CalculateOrder(14);

$orderItemsArray = $om->getOrderItemsPrinModel($order->Id);
$ca =$calMang->GetPhoneNumbar(1);

var_dump($order);

$mail->sendOrderToAdmin($ca,$order, $orderItemsArray, "");