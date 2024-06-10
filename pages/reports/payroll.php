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




$html = '<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . $document . '</title>
</head>
    
    <style>
        ' .  file_get_contents("../../src/css/pdfcss.css") . '
    </style>

<table >
    <tr>
        <td colspan="3" class="col-12 form-head">PERSONEL MAAŞ BORDROSU</td>
        
    </tr>
    <tr>
        <td rowspan="5" class="col-3">LOGO BURAYA GELECEK</td>
        <td class="col-2">Adı Soyadı :</td>
        <td class="col-7 text-bold">Mehmet Ali Gökmen</td>
    </tr>
    <tr>
        <td class="col-2">Görevi :</td>
        <td class="col-7 text-bold">Usta</td>
    </tr>
    <tr>
        <td class="col-2">Ay Günü :</td>
        <td class="col-7 text-bold">30</td>
    </tr>
    <tr>
        <td class="col-2">Ay / Yıl :</td>
        <td class="col-7 text-bold">Haziran / 2024</td>
    </tr>
    <tr>
        <td class="col-2">Firma / Proje :</td>
        <td class="col-7 text-bold">Mbe Yazılım</td>
    </tr>

    <tr>
        <td colspan="3" class="col-12 form-head">MAAŞ BİLGİLERİ</td>
    </tr>
    <tr>
        <td colspan="3">
            <table class="table">
               
                <tr>
                    <td class="col-6 text-center border-right" >KAZANÇLAR</td>
                    <td class="col-6 text-center ">KESİNTİLER</td>
                </tr>
               
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3">
            <table class="table">   
    
                    <td class="col-6" style="border: 1px solid gray;">
                        <table class="table">   
                            <tr style="border-bottom: 1px solid gray;">
                                <td colspan="4">KAZANÇLAR</td>
                                <td colspan="2">Gün</td>
                                <td colspan="3">Saat</td>
                                <td colspan="3">Tutar</td>
                            </tr>';

                            

                         $html .= '<tr>
                                <td colspan="4">Normal Çalışma</td>
                                <td colspan="2">3 Gün</td>
                                <td colspan="3">24 Saat</td>
                                <td colspan="3">3.000,00 TL</td>
                            </tr>';

                            $html .= '<tr>
                                <td colspan="4">Fazla Mesai</td>
                                <td colspan="2">3 Gün</td>
                                <td colspan="3">24 Saat</td>
                                <td colspan="3">3.000,00 TL</td>';


                       $html .= '</table>
                    </td>
                   <td class="col-6" style="border: 1px solid gray;">
                         <table class="table">   
                           <tr style="border-bottom: 1px solid gray;">
                                <td colspan="4">KESİNTİLER</td>
                                <td colspan="2">Gün</td>
                                <td colspan="3">Saat</td>
                                <td colspan="3">Tutar</td>
                            </tr>';
                            $html .= '<tr>
                                        <td colspan="4">Bes Kesintisi</td>
                                        <td colspan="2"></td>
                                        <td colspan="3"></td>
                                        <td colspan="3">900,00 TL</td>
                                    </tr>';

                        $html .='</table>
                    </td>
                 
               
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="3" class="col-12 form-head">ÖDEME BİLGİLERİ</td>
    </tr>
    
    <tr>
        <td colspan="3">
            <table class="table">
                <tr>
                    <td class="col-4 text-center border-right" >KAZANÇ TOPLAMI</td>
                    <td class="col-4 text-center border-right" >KESİNTİ TOPLAMI</td>
                    <td class="col-4 text-center border-right" >ÖDENECEK TUTAR</td>
                </tr>
                <tr>
                    <td class="col-4 text-center border-right text-bold" >6.000,00 TL</td>
                    <td class="col-4 text-center border-right text-bold" >900,00 TL</td>
                    <td class="col-4 text-center border-right text-bold" >5.100,00 TL</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<body>
</head>
</html>

';

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
$dompdf->setPaper('A4', 'P');

// PDF'yi oluştur
$dompdf->render();
ob_end_clean();


//Dosyayı indir
$dompdf->stream($document, array("Attachment" => false));







// Output the generated PDF to Browser
// $dompdf->stream();