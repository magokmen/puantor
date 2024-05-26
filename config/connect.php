
<?php
$username = "root";
$password = "";
$hostname = "localhost";
$database = "puantor";
global $con;
try {
    $con = new PDO(
        "mysql:host=$hostname;dbname=$database",
        $username,
        $password,
        array(pdo::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
    );
} catch (PDOException $getexetion) {
    echo "Bağlantı başarısız!" . $exception->getMessage();
};



