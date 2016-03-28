<?php

require_once './models/contects.php';

class Config {

    static $conttext;

    function __construct() {

        $this->init();
    }

    public static function init() {
        self::$conttext = new contects("ivr_orders", "ivrorder", "tMxqEveNDh9VSLfh", "localhost");
    }

    public static function getConttext() {
        if(empty(self::$conttext)){
            self::init();
        }
        return self::$conttext;
    }

    public static function setConttext($conttext) {
        $this->conttext = $conttext;
    }

}
