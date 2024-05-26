<?php 
require '../../include/xlshead.php';



// Add some data
$sheet->setActiveSheetIndex(0)
    ->setCellValue('A1', 'id')
    ->setCellValue('B1', 'Adı Soyadı')
    ->setCellValue('C1', 'Kimlik No')
    ->setCellValue('D1', 'Günlük Ücret')
    ->setCellValue('E1', 'İşe Başlama');


$sql=$con->prepare("SELECT * FROM person");
$sql->execute();
$persons = $sql->fetchAll(PDO::FETCH_OBJ);

$i=2;
foreach ($persons as $person){

  
     $sheet->setActiveSheetIndex(0)
     ->setCellValue('A' . $i, $person->id)
     ->setCellValue('B' . $i, $person->full_name)
     ->setCellValue('C' . $i, $person->kimlik_no)
     ->setCellValue('D' . $i, $person->daily_wages)
     ->setCellValue('E' . $i, $person->job_start_date);

    $i++;

}

## array içine yazılan sütunlarda genişliği otomatik yap. ##
$colums = array("A","B","C","D","E");
foreach($colums as $column){
    $sheet->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
}

$file_name="person";

require '../../include/xlsfoot.php';