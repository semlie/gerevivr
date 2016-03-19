<?php

require realpath(dirname(__FILE__)) .'/services/call_flow.php';

require realpath(dirname(__FILE__)) .'/../phpagi.php';

set_time_limit(30);
$call = new callFlow_manager(new AGI());

$call->init_call_flow();