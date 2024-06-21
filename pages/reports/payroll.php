<?php
require '../../vendor/autoload.php';
require '../../include/requires.php';
// reference the Dompdf namespace

function toBase64($image)
{

    $data = base64_encode(file_get_contents($image));
    return 'data:' . mime_content_type($image) . ';base64,' . $data;
}

$project_id = $_GET["project_id"];
$person_id =decrypt($_GET["person_id"]);
$year = $_GET["year"];
$month = $_GET["month"];
$month_days = $func->daysInMonth($month, $year);


$sql = $con->prepare("SELECT sm.*,mg.datas 
                        FROM sqlmaas sm 
                        LEFT JOIN maas_gelir mg ON mg.person_id=sm.id 
                        WHERE sm.id = ? and sm.yil = ? and sm.ay = ?");

$sql->execute(array($person_id, $year, $month));
$result = $sql->fetch(PDO::FETCH_OBJ);

$groupedData = [];
$data = json_decode($result->datas, true);

foreach ($data as $item) {
    if (isset($item['tur_id'])) {
        $turId = $item['tur_id'];
        if (!isset($groupedData[$turId])) {
            $groupedData[$turId] = [];
        }
        $groupedData[$turId][] = $item;
    }
}


$document = 'bordro.pdf';

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
        <td class="col-7 text-bold">'. $result->full_name .'</td>
    </tr>
    <tr>
        <td class="col-2">Görevi :</td>
        <td class="col-7 text-bold">Usta</td>
    </tr>
    <tr>
        <td class="col-2">Ay Günü :</td>
        <td class="col-7 text-bold">'.$month_days.'</td>
    </tr>
    <tr>
        <td class="col-2">Ay / Yıl :</td>
        <td class="col-7 text-bold">'.$month.' / '.$year.'</td>
    </tr>
    <tr>
        <td class="col-2">Firma / Proje :</td>
        <td class="col-7 text-bold">'.$func->getCompanyName($result->company_id). ' / ' . $func->getProjectName($project_id) . ' </td>
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
                                <td colspan="3">Toplam Saat</td>
                                <td colspan="3">Tutar</td>
                            </tr>';

                            $data = json_decode($result->datas, true);

                            foreach ($data as $item) {
                                // Check if the item has tur_id and tutar properties
                                if (isset($item['tur_id']) && isset($item['tutar'])) {
                                    $tur_id = $item['tur_id'];
                                    $tutar = $item['tutar'];
                                    $sayi = $item['sayi'];
                                    $puantaj_saati =$sayi * $func->getPuantajSaati($tur_id);
                                    
                                    // Add the row to the HTML table
                                    if($tutar>0){

                                        $html .= '<tr>
                                        <td colspan="4">' . $func->getPuantajTuru($tur_id) . '</td>
                                        <td colspan="2" style="text-align:center">'.$sayi.' Gün</td>
                                        <td colspan="3">'.$puantaj_saati.' Saat</td>
                                        <td colspan="3">' . tlFormat($tutar) . ' TL</td>
                                        </tr>';
                                    }
                                }
                            };

    
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
                            $qryKesinti = $con->prepare("SELECT wc.*,ct.type_name,SUM(wc.cut_amount) AS toplam_kesinti 
                                                            FROM wagecuts wc 
                                                            LEFT JOIN cut_types ct ON ct.id = wc.cut_type
                                                            WHERE wc.person_id = ? AND wc.year = ? AND wc.month= ? 
                                                            AND ct.type_name IS NOT NULL
                                                            GROUP BY wc.cut_type");
                            $qryKesinti->execute(array($person_id, $year, $month));
                            while ($row = $qryKesinti->fetch(PDO::FETCH_OBJ)) {
                                $html .= '<tr>
                                        <td colspan="4">' . $row->type_name . '</td>
                                        <td colspan="2"></td>
                                        <td colspan="3"></td>
                                        <td colspan="3">' . tlFormat($row->toplam_kesinti) . ' TL</td>
                                    </tr>';
                            }

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
                    <td class="col-4 text-center border-right text-bold" >'.tlFormat($result->toplam_maas).' TL</td>
                    <td class="col-4 text-center border-right text-bold" >'.tlFormat($result->kesinti).' TL</td>
                    <td class="col-4 text-center border-right text-bold" >'.tlformat($result->elegecen).' TL</td>
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




// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'P');

// PDF'yi oluştur
$dompdf->render();
ob_end_clean();


//Dosyayı indir
$dompdf->stream($document, array("Attachment" => false));







// Output the generated PDF to Browser
// $dompdf->stream();