<?php
session_start();
if (!isset($_SESSION['login']) || !isset($_SESSION["accountID"])) {
    echo 'invalid';
} else {
    echo 'valid';
}

değiştirin: