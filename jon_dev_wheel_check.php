<?php
/* 
Plugin Name: Jon Dev Wheel Check
Plugin URI: https://site.jon-dev.fr/plugin/jon_dev_weel_check
Description: A simple plugin to check the weel details
Version: 1.0
Author: Jon Dev
Author URI: https://site.jon-dev.fr


*/

use Berou\JonDevWheelCheck\JonDevWheel;
//use Berou\JonDevWheelCheck\Controller\DatabaseController;

if (!defined('ABSPATH')) {
    exit;
}


define("JON_DEV_WHEEL_CHECK_DIR_PATH", plugin_dir_path(__FILE__));

require JON_DEV_WHEEL_CHECK_DIR_PATH . 'vendor/autoload.php';



$plugin = new  JonDevWheel(__FILE__);
