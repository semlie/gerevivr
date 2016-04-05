<?php

require_once realpath(dirname(__FILE__)) . '/services/mail_service.php';
require_once realpath(dirname(__FILE__)) . '/services/order_manager.php';


$mail = new mail_service();
$om = new order_manager();
$order = $om->CalculateOrder(51);
$orderItemsArray = $om->getOrderItems($order->Id);

var_dump($order);

$mail->sendOrderToAdmin($order, $orderItemsArray, "");