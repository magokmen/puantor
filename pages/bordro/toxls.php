<?php
require '../../include/xlshead.php';



$file_name = "maaslar";
#varsayılan olarak bu kadar değerde döner
$columns = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J");

#kolon sayısı ile aynı sayıda olmak zorunda, yoksa hata verir
$columnName = ['id', 'Adı Soyadı', 'Firma Adı', 'Proje Adı', 'Yıl', "Ay", "Brüt", "Kesinti", "Net Ele Geçen", "Hesaplama Tarihi"];

#veritabanından alınacak kolon isimleri
$field = ['id', 'full_name', 'company_id', 'project_id', 'yil', 'ay', 'toplam_maas', 'kesinti', 'elegecen', 'hesaplama_tarihi'];


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
