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
        $arr = array("dir-intro-fnln","incoming-call-1-accept-2-decline","continue-or-finish","enter-product-code","enter-quantity","error-no-id","quantity-wanted","units");
        
        $this->agi->answer();
        $cid = $this->agi->parse_callerid();
        
        if ($this->is_call_identified($cid)) {
            $this->read_product_details($arr,"");
        } else {
            $this->throw_error_messege("call from good cid", "next_step");
        }
    }

    public function is_call_identified($cid) {
        $id = implode("|", array_keys($cid));
        $this->agi->conlog("call from -----conlog-----{$id} ");
    
        $this->agi->verbose("call from ----------{$id} ");
        $id = implode("|", $cid);
        $this->agi->verbose("call from ----------{$id} ");

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
            
        }
    }

    public function is_product_id_valid($product_id) {
        return !empty($product_id);
    }

    public function read_product_details($product, $next) {

        if (is_array($product)) {
            foreach ($product as $row) {
                $this->sayFile("gerev/".$row);
            }
        }
    }

    public function validit_quntity($qty, $next) {
        
    }

    public function read_total_order() {
        
    }

    public function read_and_ask($state, $nextOK, $nextErr) {
        
    }

    private function sayFile($filename) {
        if (!empty($filename)) {
            $this->agi->stream_file("/var/lib/asterisk/sounds/".$filename,"#");
        }
    }

    private function getData($playFile, $onErr, $maxDigit = self::MAX_DIGIT) {
        $cycle = 0;

        do {
            $cycle ++;
            $result = $this->agi->get_data($playFile, self::TIME_OUT, $maxDigit);
            $this->agi->conlog("Failed to ping {$result['result']}");
        } while (!empty($result['result']) && $cycle < self::MAX_CYCLES);

        if (self::MAX_CYCLES - 1 == $cycle) {
            if (function_exists($onErr)) {
                $onErr();
            } else {
                return FALSE;
            }
        } else {
            return $result['result'];
        }
    }

}
