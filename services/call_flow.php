<?php

require realpath(dirname(__FILE__)) . '/../models/product.php';
require realpath(dirname(__FILE__)) . '/product_manager.php';
require realpath(dirname(__FILE__)) . '/caller_manager.php';
require realpath(dirname(__FILE__)) . '/order_manager.php';

class callFlow_manager {

    const MAX_DIGIT = 8;
    CONST TIME_OUT = 15000;
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
        $arr = array( "continue-or-finish", "enter-product-code", "enter-quantity", "error-no-id", "quantity-wanted", "units");

        $this->agi->answer();
        $cid = $this->agi->parse_callerid();

        if ($this->is_call_identified($cid)) {
            $this->read_product_details($arr, "");
            $this->getNevigationKey("continue-or-finish", "19");
        } else {
            $this->throw_error_messege("call from good cid", "next_step");
        }
    }

    private function findProductStep($param) {
        //enter product code
        //search for product 
        // if find go to getQuntityStep 
        // else 
        // say error and start agein 
    }

    private function getQuntityStep($param) {
        //say the product 
        // ask qunitity
        // validate quantity
        // create order and add orderItem 
        // ask if go to step finish or start
    }

    private function finishStep($param) {
        // close order and get total
        // say total 
        // hangup
    }

    public function is_call_identified($cid) {
        $id = implode("|", array_keys($cid));
        $this->agi->conlog("call from {$cid['username']} ");
        if (!empty($cid['username'])) {

            $this->callerItem = $this->callerManager->GetCallerItem($cid['username']);
            $this->agi->say_digits($cid['username']);
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
            $this->read_product_details($product);
        }
    }

    public function is_product_id_valid($product_id) {
        return !empty($product_id);
    }

    public function read_product_details($product, $next = '') {

        if (is_array($product)) {
            foreach ($product as $row) {
                $this->sayFile("gerev/" . $row);
            }
        }
    }

    public function validate_quntity($qty, $next) {
        
    }

    public function read_total_order() {
        
    }

    public function read_and_ask($state, $nextOK, $nextErr) {
        
    }

    private function sayFile($filename,$escape_digits="") {
        if (!empty($filename)) {
           return $this->agi->stream_file($filename, $escape_digits);
        }
        return '';
    }

    private function getNevigationKey($playFile, $keys) {
        if (!empty($filename)) {
            $result = $this->loopToGetUserData("sayFile",array($playFile,$keys));
            return $result;
        }
    }

    private function getData($playFile, $onErr = "", $maxDigit = self::MAX_DIGIT) {
           return $this->agi->get_data($playFile, self::TIME_OUT, $maxDigit);

    }

    private function loopToGetUserData($function, $param) {
        $cycle = 0;
        do {
            $cycle ++;
            $result = call_user_func_array(array($this, $function), $param);
//            $result = $this->agi->get_data($playFile, self::TIME_OUT, $maxDigit);
            var_dump($result);
            $this->agi->conlog("call {$function} with {$param}");
        } while (returnData($result) && $cycle < self::MAX_CYCLES);
        if ($result['result'] > 0) {
            return $result['result'];
        } else {
            return FALSE;
        }
    }

    private function returnData($result) {
        if (!empty($result['result']) && $result['result'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
