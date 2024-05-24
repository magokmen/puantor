<?php 

session_start();
ob_start();
require_once "../../config/connect.php";
require_once "../../config/functions.php";
$func = new Functions();
$account_id=$_SESSION["accountID"];
//$company_id=$_SESSION["companyID"];

