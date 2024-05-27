<?php 
//serverdaki veritabanı bilgileri
// $username = "mbeyazil_root";
// $password = "54tJ29L^tg1[";
// $hostname = "localhost";
// $database = "mbeyazil_puantor";

//localdeki veritabanı bilgileri
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

require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


$helper = new Sample();
if ($helper->isCli()) {
    $helper->log('This example should only be run from a Web Browser' . PHP_EOL);

    return;
}
// Create new Spreadsheet object
$sheet = new Spreadsheet();


// Set document properties
$sheet->getProperties()->setCreator('puantor.mbeyazilim.com')
    ->setLastModifiedBy('puantor.mbeyazilim.com')
    ->setTitle('Office 2007 XLSX Test Document')
    ->setSubject('Office 2007 XLSX Test Document')
    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
    ->setKeywords('office 2007 openxml php')
    ->setCategory('Test result file');
