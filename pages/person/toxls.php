<?php
#exceli oluşturmak için gereken ÜST kodlar bir dosyaya alındı
require '../../include/xlshead.php';

#excel dosyasının adı
$file_name      = "bordro";

#varsayılan olarak bu kadar değerde döner
$colums         = array("A", "B", "C", "D", "E");

#kolon sayısı ile aynı sayıda olmak zorunda, yoksa hata verir
$columnNames    = ['id', 'Adı Soyadı', 'Firma Adı', 'Proje Adı', 'Yıl', "Ay","Brüt" , "Kesinti","Net Ele Geçen","Hesaplama Tarihi"];

#veritabanından alınacak kolon isimleri
$field          = [ 'id', 'full_name', 'company_id', 'project', 'yil', 'ay', 'toplam_maas', 'kesinti', 'elegecen', 'hesaplama_tarihi'];


#sorguyu değiştirmek gerekirse buradan değiştirilir
$sql            = $con->prepare("SELECT * FROM sqlmaas");
$sql            ->execute();
$persons        = $sql->fetchAll(PDO::FETCH_ASSOC);


#exceli oluşturmak için gereken ALT kodlar bir dosyaya alındı
require '../../include/xlsfoot.php';




