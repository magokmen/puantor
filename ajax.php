<?php

require_once "config/connect.php";
require_once "config/functions.php";
if ($_POST && $_POST["action"] == "kasa" ) {
    $vault_id =$_POST["vault_id"];

    $sql = $con->prepare("SELECT * FROM transactions WHERE vault_id = ?");
    $sql->execute(array($vault_id)); 
   $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    $res = array(
        "message" => $vault_id,
        "columns" => $result
    );

    header('Content-Type: application/json');
    echo json_encode($res);
    return false;


} 



//header('Content-Type: application/json');
if ($_POST && $_POST["action"] == "puantaj") {
    $company_id = $_POST["company_id"];
    $project_id = $_POST["project_id"];
    $year = $_POST["year"];
    $months = $_POST["months"];
    $data = ($_POST["data"]);

    // Gönderilen JSON verisini doğrudan kontrol et
    $puantajData = json_decode($data, true);

    foreach ($puantajData as $employee => $data) {
        // Adı Soyadı ve Ünvanı ayırmak için patlat
        list($fullName, $position) = explode(' : ', $employee);
        $full_name = trim(preg_replace('/\s+/', ' ', $fullName));
        $position = trim(preg_replace('/\s+/', ' ', $position));

        // Her tarih ve durum bilgisini array'e ekle
        foreach ($data as $date => $status) {
            $records[$date] = $status;
        }
        $records_json = json_encode($records);

        try {

           
            $puantaj_id = kayitVarmi($company_id,$full_name, $year, $months);

            if ($puantaj_id > 0) {
                $sql = $con->prepare("UPDATE puantaj SET datas = ? WHERE id = ?");
                $sql->execute(array($records_json, $puantaj_id));

            } else {
                $sql = $con->prepare("INSERT INTO puantaj SET company_id = ?, project_id = ?, person = ? , yil = ?, ay = ?, datas = ?");
                $sql->execute(array($company_id, $project_id, $full_name, $year, $months, $records_json));
            }
            $res = array(
                "message" => "Puantaj Başarı ile kaydedildi!",
                "status" => 200
            );

        } catch (PDOException $ex) {
            $res=array(
                "employee" => $full_name,
                "message" =>"Bir hata oluştu. Hata mesajı :" .  $ex->getMessage(),
                "status" => 400
            );

        }
        ;

    }
    echo json_encode($res);
    return false;


} 


if ($_POST && $_POST["action"] == "proje") {
    $company_id = $_POST["company_id"];

    // Veritabanı sorgusu
    $sql = $con->prepare("SELECT * FROM projects WHERE company_id = ?");
    $sql->execute(array($company_id));
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        // Eğer kayıtlar varsa, sadece option öğelerini oluştur
        $html = '<option>Proje Seçiniz</option>';
        foreach ($results as $result) {
            $html .= '<option value="' . $result["id"] . '">' . $result["project_name"] . '</option>';
        }
        echo $html; // HTML'yi döndürmek için echo kullanın
    } else {
        // Eğer kayıt yoksa bir mesaj döndürebilirsiniz
        echo '<option disabled >Bu firmaya ait proje bulunamadı.</option>';
    }
}


if ($_POST && $_POST["action"] == "ilce") {
    $il_id = $_POST["il_id"];

    // Veritabanı sorgusu
    $sql = $con->prepare("SELECT * FROM ilce WHERE il_id = ?");
    $sql->execute(array($il_id));
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        // Eğer kayıtlar varsa, sadece option öğelerini oluştur
        $html = '<option>İlçe Seçiniz</option>';
        foreach ($results as $result) {
            $html .= '<option value="' . $result["id"] . '">' . $result["ilce_adi"] . '</option>';
        }
        echo $html; // HTML'yi döndürmek için echo kullanın
    } else {
        // Eğer kayıt yoksa bir mesaj döndürebilirsiniz
        echo '<option disabled >İl bulunamadı</option>';
    }
}


