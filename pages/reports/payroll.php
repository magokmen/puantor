<?php
require '../../vendor/autoload.php';
require '../../include/requires.php';
// reference the Dompdf namespace

function toBase64($image)
{

    $data = base64_encode(file_get_contents($image));
    return 'data:' . mime_content_type($image) . ';base64,' . $data;
}


$id = $_GET["id"];


$sql = $con->prepare("SELECT * FROM sqlmaas WHERE id = ? and yil = 2024 and ay = 6");
$sql->execute(array(143));
$result = $sql->fetch(PDO::FETCH_OBJ);


$html = '<h4>Personel Maas : '.$result->toplam_maas.'</h4>';
echo $html;

use Dompdf\Dompdf;
use Dompdf\Options;

// instantiate and use the dompdf class
$options = new Options();
$options->set('isPhpEnabled', true); // PHP kodlarının çalıştırılmasını etkinleştir
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);



$document = 'bordro.pdf';
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// PDF'yi oluştur
$dompdf->render();
ob_end_clean();


//Dosyayı indir
$dompdf->stream($document , array("Attachment" => false));







// Output the generated PDF to Browser
// $dompdf->stream();