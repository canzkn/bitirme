<?php
/**
 * General Configurations.
 * @author      hasan can özkan <hasan@canozkan.net>
 * @author      oğuz kaan yıldız <yldzoguzkaan@gmail.com>
 * @copyright   2020
 * @license     The MIT License (MIT)
 */
    
// Error Reporting and Set Time Limit
error_reporting(0); // Hide error
set_time_limit(0);

// Sessions & Ob_Start & Date
ob_start();
session_start();
date_default_timezone_set('ETC/GMT-3');

// Include Database Model
include_once 'model/Database.php';

// Set Database
$database = new Database();
$db = $database->connect();

$api_url = "http://localhost/bitirme/api/";

define("API_URL", $api_url);