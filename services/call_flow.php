<?php

require realpath(dirname(__FILE__)) . '/../models/product.php';
require realpath(dirname(__FILE__)) . '/product_manager.php';
require realpath(dirname(__FILE__)) . '/caller_manager.php';
require realpath(dirname(__FILE__)) . '/order_manager.php';

class callFlow_manager {

    const MAX_DIGIT = 8;
    CONST TIME_OUT = 10000;
    CONST MAX_CYCLES = 4;
    CONST FAILES_BASE_PATH = 'gerevsounds';

    public $agi, $productManager, $callerManager, $callerItem, $orderId, $orderManager;

    function __construct($agi) {
        $this->agi = $agi;
        $this->productManager = new product_manager();
        $this->callerManager = new caller_manager();
        $this->orderManager = new order_manager();
    }

    public function init_call_flow() {
        $arr = array("continue-or-finish", "enter-product-code", "enter-quantity", "error-no-id", "quantity-wanted", "units");

        $this->agi->answer();
        $cid = $this->agi->parse_callerid();

        if ($this->is_call_identified($cid)) {
            //$this->read_product_details($arr, "");
            //$this->getNevigationKey("continue-or-finish", "19"); 
            
            $this->Flow();
        } else {
            $this->throw_error_messege("call from good cid", "next_step");
        }
    }

    private function Flow() {
        do {

            // get productId 
            $productId = $this->loopToGetUserData("findProductStep", array());
            if ($productId == FALSE && !empty($this->orderId)) {
                //TODO say error and close the call
                $this->finishStep($this->orderId);
                exit();
            }

            //get product quntity
            $quantity = $this->loopToGetUserData("getQuntityStep", array($productId));
            if ($quantity == FALSE && !empty($this->orderId)) {
                //TODO say error and close the call
                $this->finishStep($this->orderId);
                exit();
            }
            //add to order
            $this->addProductToOrder($productId, $quantity);

            // get more or finish
            $step = $this->getNevigationKey("continue-or-finish", "19");
        } while ($step == 1);

        if ($step == 9) {
            $this->finishStep($this->orderId);
            exit();
        }
    }

    private function findProductStep($param = 0) {
        $productNumber = $this->askUserProductId();
        //search for product 
        $productId = $this->get_product_by_id($productNumber);
        if ($productId != False) {

            return $productId;
        } else {
            return FALSE;
        }
    }

    private function addProductToOrder($productId, $quantity) {
        if (empty($this->orderId)) {
            $this->orderId = $this->orderManager->CreateNewOrder($this->callerItem->Id);
        }
        $this->orderManager->AddNewItemForOrder($this->callerItem->Id, $this->orderId, $productId, $quantity);
    }

    private function askUserProductId() {
        $playFile = "gerev/enter-product-code";
        $keys = array();
        $result = $this->loopToGetUserDataFromPhone("getData", array($playFile, $keys));
        if ($result == FALSE) {
            //TODO
            $this->throw_error_messege("", "");
            return False;
        }
        return $result;
    }

    private function getQuntityStep($param) {
        $playFile = "gerev/enter-quantity";
        $keys = array();
        $count = 0;
        do {
            $result = $this->loopToGetUserDataFromPhone("getData", array($playFile, $keys));

            if ($result == FALSE) {
                //TODO
                $this->throw_error_messege("", "");
                return FALSE;
            }
            $validQty = $this->validate_quntity($result);
        } while ($validQty != 1 && $count < self::MAX_CYCLES);
        if ($validQty != FALSE) {

            return $result;
        } else {
            return FALSE;
        }
    }

    private function finishStep($param) {
        // close order and get total
        $order = $this->orderManager->CalculateOrder($param);
        $ordertotal = $this->orderManager->MapOrderTotal($order);
        $this->say_array_details($ordertotal);
        $this->agi->hangup();
        // say total 
        // hangup
    }

    public function is_call_identified($cid) {
        $this->agi->conlog("call from {$cid['username']} ");
        if (!empty($cid['username'])) {

            $this->callerItem = $this->callerManager->GetCallerItem($cid['username']);
            //$this->agi->say_digits($cid['username']);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_user_entered_digits($results) {
        
    }

    public function throw_error_messege($err_file_name, $next_step) {
        
    }

    public function get_product_by_id($product_id) {
        $product = $this->productManager->getProbuctById($product_id);
        if (!empty($product)) {
            $this->say_array_details($product);
            return $product->Id;
        } else {
            return FALSE;
        }
    }

    public function say_array_details($product) {

        if (is_array($product)) {
            foreach ($product as $row) {
                $this->sayFile("gerev/" . $row);
            }
        }
    }

    public function validate_quntity($qty) {
        return 1;
    }

    public function read_total_order() {
        
    }

    public function read_and_ask($state, $nextOK, $nextErr) {
        
    }

    private function sayFile($filename, $escape_digits = "") {
        if (!empty($filename)) {
            return $this->agi->stream_file($filename, $escape_digits);
        }
        return '';
    }

    private function getNevigationKey($playFile, $keys) {
        if (!empty($playFile)) {
            $result = $this->loopToGetUserDataFromPhone("getData", array($playFile));
            return $result;
        }
    }

    private function getData($playFile, $maxDigit = self::MAX_DIGIT) {
        return $this->agi->get_data($playFile, self::TIME_OUT, $maxDigit);
    }

    private function loopToGetUserDataFromPhone($function, $param) {
        $cycle = 0;
        do {
            $cycle ++;
            $result = call_user_func_array(array($this, $function), $param);

//            $result = $this->agi->get_data($playFile, self::TIME_OUT, $maxDigit);
            $this->agi->conlog("call {$function} with {$param}");
        } while (!$this->returnData($result) && $cycle < self::MAX_CYCLES);
        if (intval($result['result']) > 0) {
            return $result['result'];
        } else {
            return FALSE;
        }
    }

    private function loopToGetUserData($function, $param) {
        $cycle = 0;
        do {
            $cycle ++;
            $result = call_user_func_array(array($this, $function), $param);
        } while (empty($result) && $cycle < self::MAX_CYCLES);
        if ($result != FALSE) {
            return $result;
        } else {
            return FALSE;
        }
    }

    private function returnData($result) {
        if (!empty($result['result']) && intval($result['result']) > 0) {
            $this->agi->conlog("returnData=true-> {$result['result']}");

            return TRUE;
        } else {
            $this->agi->conlog("returnData=false-> {$result['result']}");
            return FALSE;
        }
    }

}
