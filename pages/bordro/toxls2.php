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
// require '../../include/requires.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

// Yeni bir Spreadsheet nesnesi oluştur
$spreadsheet = new Spreadsheet();

// Veritabanından verileri al
$query = $con->prepare("SELECT * FROM person");
$query->execute();
 $results = $query->fetchAll(PDO::FETCH_ASSOC);

// while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
//     echo $row['full_name'] . "<br>";
// }
// Verileri Excel'e aktar
$sheet = $spreadsheet->getActiveSheet();
$rowCount = 1; // Excel satır sayacı
foreach ($results as $result) {
    $columnCount = 1; // Excel sütun sayacı
    foreach ($result as $value) {
        $column = Coordinate::stringFromColumnIndex($columnCount);
        $sheet->setCellValue($column . $rowCount, $value);
        $columnCount++;
    }
    $rowCount++;
}

// Excel dosyasını kaydet
// $writer = new Xlsx($spreadsheet);
// $writer->save('output.xlsx');
// ...
//Excel dosyasını indir
$writer = new Xlsx($spreadsheet);

// Doğru HTTP başlıklarını ayarla
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="output.xlsx"');
header('Cache-Control: max-age=0');

// Excel dosyasını çıktı tamponuna yaz
$writer->save('php://output');
exit;