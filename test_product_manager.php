<?php


require realpath(dirname(__FILE__)) .'/services/product_manager.php';



$a = new product_manager();

$b = $a->getProbuctById('10101');

var_dump($b);


