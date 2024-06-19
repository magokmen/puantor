<?php
session_start();

$page = $_POST["page"];
$pageTitle = $_POST["pageTitle"];
$activeLink = $_POST["activeLink"];

$_SESSION["page"] = $page;
$_SESSION["pageTitle"] = $pageTitle;
$_SESSION["activeLink"] = $pageTitle;
