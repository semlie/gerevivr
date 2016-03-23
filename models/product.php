<?php

require_once realpath(dirname(__FILE__)) .'/ModelInfo.php';
class product extends ModelInfo{
    //put your code here
   public  $Name,$description,$CatalogNumber,$FirstCategory, $SecondaryCategory,  $Material, $Thickness ,$Color ,$Size, $Price ,$Manufacturer, $Brand ,$Strength, $Example;
}
