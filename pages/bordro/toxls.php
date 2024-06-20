<?php
require '../../include/xlshead.php';
require_once "../../config/functions.php";

$func = new Functions();





$file_name = "maaslar";
#varsayılan olarak bu kadar değerde döner
$columns = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J");

#kolon sayısı ile aynı sayıda olmak zorunda, yoksa hata verir
$columnName = ['id', 'Adı Soyadı', 'Firma Adı', 'Proje Adı', 'Yıl', "Ay", "Brüt", "Kesinti", "Net Ele Geçen", "Hesaplama Tarihi"];

#veritabanından alınacak kolon isimleri
$field = ['id', 'full_name', 'company_id', 'project_id', 'yil', 'ay', 'toplam_maas', 'kesinti', 'elegecen', 'hesaplama_tarihi'];


#sorguyu değiştirmek gerekirse buradan değiştirilir
$sql = $con->prepare("SELECT * FROM sqlmaas where yil = 2024 and ay = 6");
$sql->execute();
$persons = $sql->fetchAll(PDO::FETCH_ASSOC);



#excel dosyasına başlıkları yazdırma işlemi
$i = 0;
foreach ($columns as $column) {
    $sheet->setActiveSheetIndex(0)
        ->setCellValue($column . "1", $columnName[$i]);
    $i++;
}

// $column = $columns[0]; // Assuming you want to use the first column in the loop

#veritabanından alınan verileri excel dosyasına yazdırma işlemi
$i = 0;
foreach ($persons as $person) {
    $k = 0;
    $sheet->setActiveSheetIndex(0)
        ->setCellValue("A" . ($i + 2), $person["id"])
        ->setCellValue("B" . ($i + 2), $person["full_name"])
        ->setCellValue("C" . ($i + 2), $func->getCompanyName($person["company_id"]))
        ->setCellValue("D" . ($i + 2), $func->getProjectNames($person["project_id"]))
        ->setCellValue("E" . ($i + 2), $person["yil"])
        ->setCellValue("F" . ($i + 2), $person["ay"])
        ->setCellValue("G" . ($i + 2), tlFormat($person["toplam_maas"]))
        ->setCellValue("H" . ($i + 2), tlFormat($person["kesinti"]))
        ->setCellValue("I" . ($i + 2), tlFormat($person["toplam_maas"] - $person["kesinti"]))
        ->setCellValue("J" . ($i + 2), $person["hesaplama_tarihi"]);

    $i++;
}



#veritabanından alınan verileri excel dosyasına yazdırma işlemi
// $i = 0;
// foreach ($persons as $person) {
// $k = 0;
//     foreach ($columns as $column) {
//         $sheet->setActiveSheetIndex(0)
//             ->setCellValue($column . ($i + 2), $person[$field[$k]]);
//         $k++;
//     }
//     $i++;

// }




#exceli oluşturmak için gereken ALT kodlar bir dosyaya alındı
require '../../include/xlsfoot.php';
