<?php

require_once "config/connect.php";
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

            // $var_kayit = kayitVarmi(1, $full_name, $year, $months); 
            $kayitVar = kayitVarmi($company_id, $full_name, $year, $months);

            if ($kayitVar > 0) {
                $sql = $con->prepare("UPDATE puantaj SET sub_company_id = ?, person = ? , yil = ?, ay = ?, datas = ?, company_id = ? WHERE id = ?");
                $sql->execute(array($company_id, $full_name, $year, $months, $records_json, $kayitVar, $kayitVar));

            } else {
                $sql = $con->prepare("INSERT INTO puantaj SET sub_company_id = ?, person = ? , yil = ?, ay = ?, datas = ?, company_id = ?");
                $sql->execute(array($company_id, $full_name, $year, $months, $records_json, $kayitVar));
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


function kayitVarmi($company_id, $person, $year, $months)
{
    global $con;
    $sql = $con->prepare("SELECT * FROM puantaj where sub_company_id = ? AND person = ? AND yil = ? AND ay = ? ");
    $sql->execute(array($company_id, $person, $year, $months));
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    return $result ? $result["id"] : 0; // Eğer kayıt bulunamazsa null döndür
}