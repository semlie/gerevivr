<?php

require realpath(dirname(__FILE__)) . '/../models/product.php';
require realpath(dirname(__FILE__)) . '/product_manager.php';
require realpath(dirname(__FILE__)) . '/caller_manager.php';
require realpath(dirname(__FILE__)) . '/order_manager.php';
require_once realpath(dirname(__FILE__)) . '/mail_service.php';

class callFlow_manager {

    const MAX_DIGIT = 8;
    CONST TIME_OUT = 8000;
    CONST MAX_CYCLES = 4;
    CONST FAILES_BASE_PATH = 'gerev/';

    public $agi, $productManager, $callerManager, $callerItem, $orderId, $orderManager, $mailService;

    function __construct($agi) {
        $this->agi = $agi;
        $this->productManager = new product_manager();
        $this->callerManager = new caller_manager();
        $this->orderManager = new order_manager();
        $this->mailService = new mail_service();
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
            $this->throw_error_messege(self::FAILES_BASE_PATH . "error-no-id");
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
        $this->loger("addProductToOrder");
        $this->loger($productId);
        $this->loger("<product id | quntity>");
        $this->loger($quantity);

        if (empty($this->orderId)) {
            $this->orderId = $this->orderManager->CreateNewOrder($this->callerItem->Id);
        }

        $this->loger("$this->callerItem->Id");
        $this->loger($this->callerItem->Id);

        $this->orderManager->AddNewItemForOrder($this->callerItem->Id, $this->orderId, $productId, $quantity);
    }

    private function askUserProductId() {
        $playFile = self::FAILES_BASE_PATH . "enter-product-code";
        $keys = array();
        $result = $this->loopToGetUserDataFromPhone("getData", array($playFile));
        $this->loger("askUserProductId");
        $this->loger("result == " . $result);
        if ($result == FALSE) {
            //TODO
            return False;
        } else {

            return $result;
        }
    }

    private function getQuntityStep($param) {
        $playFile = self::FAILES_BASE_PATH . "enter-quantity";
        $keys = array();
        $count = 0;
        do {
            $result = $this->loopToGetUserDataFromPhone("getData", array($playFile));

            if ($result == FALSE) {
//TODO
                $this->throw_error_messege("");
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
        $this->loger("finishStep");
        $this->loger("param ==== > " . $param);

        $order = $this->orderManager->CalculateOrder($param);

        $this->say_array_details($order);
        $orderItemsArray = $this->orderManager->getOrderItems($order->Id);
        $this->mailService->sendOrderToAdmin($order, $orderItemsArray, "");
        $this->agi->hangup();
// say total 
// hangup
    }

    public function is_call_identified($cid) {
        $this->agi->conlog("call from {$cid['username']} ");
        if (!empty($cid['username']) && $cid['username'] != "Restricted") {

            $this->callerItem = $this->callerManager->GetCallerItem($cid['username']);
//$this->agi->say_digits($cid['username']);
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function is_user_entered_digits($results) {
        
    }

    public function throw_error_messege($err_file_name) {
        $this->sayFile($err_file_name);
    }

    public function get_product_by_id($product_id) {
        $product = $this->productManager->GetProductByCatalogNumber($product_id);
        if (empty($product)) {
            $this->throw_error_messege(self::FAILES_BASE_PATH . "err-not-valid-product");
        }
        if (!empty($product)) {
            $productArray = $this->productManager->mapProductToArray($product);

            if ($this->validate_product($productArray)) {
                return $product->Id;
            }
        }
        return FALSE;
    }

    public function say_array_product($productArray) {
        if (!empty($productArray)) {
            foreach ($productArray as $value) {
                $prefix = self::FAILES_BASE_PATH;

                $this->sayFile($prefix . $value);
            }
        }
    }

    public function say_array_details($order) {
        if (!empty($order)) {


            $prefix = self::FAILES_BASE_PATH;

            $this->sayFile($prefix . 'order-id');
            $this->agi->say_number($order->Id);

            $this->sayFile($prefix . 'total-items');
            $this->agi->say_number($order->TotalItems);

            $this->sayFile($prefix . 'total-quantity');
            $this->agi->say_number($order->TotalQuantity);

            $this->sayFile($prefix . 'total-price');
            $this->agi->say_number($order->TotalPrice);
        }
    }

    public function validate_quntity($qty) {
        return 1;
    }

    public function validate_product($productArray) {
        $this->say_array_product($productArray);
        $result = $this->confirmOrCancel();
        $this->loger("validate_product  ========= > result = {$result}");
        return $result;
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
            $result = $this->loopToGetUserDataFromPhone("getData", array($playFile, "1"));
            return $result;
        }
    }

    private function getData($playFile, $maxDigit = self::MAX_DIGIT) {
        return $this->agi->get_data($playFile, self::TIME_OUT, $maxDigit);
    }

    private function loopToGetUserDataFromPhone($function, $param) {
        $cycle = 0;
        do {
            if ($cycle > 0) {
                $this->throw_error_messege(self::FAILES_BASE_PATH . "err-no-product-entered");
            }
            $cycle ++;
            $result = call_user_func_array(array($this, $function), $param);

            $this->agi->conlog("call {$function} with {$param}");
        } while (!$this->returnData($result) && $cycle < self::MAX_CYCLES);
        if (intval($result['result']) > 0) {
            return $result['result'];
        } else {
            return FALSE;
        }
    }

    private function confirmOrCancel() {
        $result = $this->getData(self::FAILES_BASE_PATH . "confirm-or-cancel", "1");
        $this->loger("confirmOrCancel ==========  result = {$result['result']}");
        return intval($result['result']) == 1;
    }

    private function loopToGetUserData($function, $param) {
        $cycle = 0;
        do {
            $cycle ++;
            $result = call_user_func_array(array($this, $function), $param);
            $this->loger("loopToGetUserData");
            $this->loger("$result ===== >");
            $this->loger($result);
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

    private function loger($param) {
        $this->agi->conlog("loger -------> {$param}");
    }

}
