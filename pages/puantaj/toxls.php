<?php
require '../../include/xlshead.php';



$file_name = "puantaj";
#varsayılan olarak bu kadar değerde döner

$columns = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", "AA", "AB", "AC", "AD", "AE", "AF", "AG", "AH", "AI");

#kolon sayısı ile aynı sayıda olmak zorunda, yoksa hata verir
$columnName = ['Adı Soyadı',"Unvanı", 'Proje Adı'];

#veritabanından alınacak kolon isimleri


#sorguyu değiştirmek gerekirse buradan değiştirilir
$sql = $con->prepare("SELECT * FROM sqlmaas where yil = 2024 and ay = 5");
$sql->execute();
$persons = $sql->fetchAll(PDO::FETCH_ASSOC);



#excel dosyasına başlıkları yazdırma işlemi
$i = 0;
foreach ($columns as $column) {
    $sheet->setActiveSheetIndex(0)
        ->setCellValue($column . "1", $columnName[$i]);
    $i++;
}

#veritabanından alınan verileri excel dosyasına yazdırma işlemi
$i = 0;
foreach ($persons as $person) {
$k = 0;
    foreach ($columns as $column) {
        $sheet->setActiveSheetIndex(0)
            ->setCellValue($column . ($i + 2), $person[$field[$k]]);
        $k++;
    }
    $i++;

}




#exceli oluşturmak için gereken ALT kodlar bir dosyaya alındı
 require '../../include/xlsfoot.php';
