<?php

require './services/call_flow.php';

require('../phpagi.php');

set_time_limit(30);
$call = new callFlow_manager(new AGI());

$call->init_call_flow();