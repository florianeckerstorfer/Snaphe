<?php

require_once dirname(dirname(__FILE__)).'/src/Snaphe/Autoload.php';
Snaphe_Autoload::register();

$wrapper = new Snaphe_Application_HTTP(dirname(dirname(__FILE__)) . '/wrappers');
$wrapper->run($_GET);