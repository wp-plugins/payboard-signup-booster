<?php
/*
Plugin Name: Payboard
Description: Payboard Plugin
Author: Payboard
Version: 2.0
Author URI: http://www.payboard.com/
 */

global $payboardVars;
$payboardVars = null;

include_once('libs/Payboard.php');
include_once('libs/PayboardVars.php');
$pawboardInstance = new Payboard(__FILE__);

?>
