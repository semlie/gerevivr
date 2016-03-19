<?php

require realpath(dirname(__FILE__)) .'/../models/product.php';
/*
 *
 * 
 */

class callFlow_manager {

    const MAX_DIGIT = 8;
    CONST TIME_OUT = 15000;
    CONST MAX_CYCLES = 4;

    public $agi;

    function __construct($agi) {
        $this->agi = $agi;
    }

    public function init_call_flow() {
        $this->agi->answer();
        $cid = $this->agi->parse_callerid();
        $this->is_call_identified($cid);
    }

    public function is_call_identified($cid) {
        if (!empty($cid)) {
           $this->agi->conlog("call from {$cid['name']}");

            $this->agi->text2wav('The value must be a constant expression, not for example a variable, a property, or a function call.');
        } else {
            return FALSE;
        }
    }

    public function is_user_entered_digits($results) {
        
    }

    public function throw_error_messege($err_file_name, $next_step) {
        
    }

    public function get_product_by_id($product_id) {
        
    }

    public function is_product_id_valid($product_id) {
        
    }

    public function read_product_details(product $product, $next) {
        
    }

    public function validit_quntity($qty, $next) {
        
    }

    public function read_total_order() {
        
    }

    public function read_and_ask($state, $nextOK, $nextErr) {
        
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
