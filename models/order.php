<?php

require_once  realpath(dirname(__FILE__)).'/ModelInfo.php';

class order extends ModelInfo {

    public $CallerItemId, $Is_Delivered, $Is_Paid,$TotalQuantity,$TotalPrice;


}
