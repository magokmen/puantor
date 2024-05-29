<?php 

session_start();
ob_start();
// Kök dizinini tanımlayın
$rootDir = $_SERVER['DOCUMENT_ROOT']  ;
define('ROOT_PATH', $rootDir);

require_once ROOT_PATH . "/config/connect.php";
require_once ROOT_PATH . "/config/functions.php";
$func = new Functions();
$account_id=$_SESSION["accountID"];
//$company_id=$_SESSION["companyID"];

