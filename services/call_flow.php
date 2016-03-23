<?php

require realpath(dirname(__FILE__)) .'/../models/product.php';
require realpath(dirname(__FILE__)) .'/product_manager.php';
/*
 *
 * 
 */

class callFlow_manager {

    const MAX_DIGIT = 8;
    CONST TIME_OUT = 15000;
    CONST MAX_CYCLES = 4;
    CONST FAILES_BASE_PATH = 'gerevsounds';

    public $agi,$productManager;

    function __construct($agi) {
        $this->agi = $agi;
        $this->productManager = new product_manager();
        }

    public function init_call_flow() {
        $this->agi->answer();
        $cid = $this->agi->parse_callerid();
        $this->is_call_identified($cid);
    }

    public function is_call_identified($cid) {
        if (!empty($cid)) {
           $this->agi->conlog("call from {$cid['name']}");

            $this->agi->say_number(123456);
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
        
    }

    public function is_product_id_valid($product_id) {
        return !empty($product_id);
    }

    public function read_product_details($product, $next) {
        
        if (is_array($product)) {
            foreach ($product as $row) {
                $this->sayFile($row);
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
