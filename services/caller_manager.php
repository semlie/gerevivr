<?php

require_once realpath(dirname(__FILE__)) . '/icaller_manager.php';
require_once realpath(dirname(__FILE__)) . '/caller_dataService.php';
require_once realpath(dirname(__FILE__)) . '/calleritem_dataService.php';
require_once realpath(dirname(__FILE__)) . '/../models/product.php';

class caller_manager implements ICaller_manager {

    //put your code here
    public $callerDataService, $callerItemDataService;

    function __construct() {
        $this->callerDataService = new caller_dataService();
        $this->callerItemDataService = new calleritem_dataService();
    }
    public function GetCallerItem($number) {
        $caller = $this->GetCallerIdByNumber($number);
        $callerItem = new caller_item();
        $callerItem->Uid = uniqid();
        $callerItem->CallerId = $caller->Id;
        $this->callerItemDataService->Add($callerItem);
        return $callerItem;
        
    }
    
    public function GetCallerIdByNumber($number) {
        $result = $this->callerDataService->GetCallerByPhoneNumber($number);

        if (empty($result)) {
            return $this->AddNewCallerFromNumber($number);
        }

        return $result;
    }

    public function AddNewCallerFromNumber($number) {
        $caller = new caller();
        $caller->PhoneNumber = $number;
        $this->InsertNewCaller($caller);
        return $caller;
    }

    private function InsertNewCaller(caller $caller) {
        $this->callerDataService->Add($caller);
        return $caller->Id;
    }



}
