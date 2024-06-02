
<?php
// $username = "mbeyazil_root";
// $password = "54tJ29L^tg1[";
// $hostname = "45.84.189.194";
// $database = "mbeyazil_puantor";
setlocale(LC_TIME, 'tr_TR.UTF-8', 'turkish');
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
} catch (PDOException $ex) {
    echo "Bağlantı başarısız!" . $ex->getMessage();
};



