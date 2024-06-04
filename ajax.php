<?php

require_once "config/connect.php";
require_once "config/functions.php";
if ($_POST && $_POST["action"] == "kasa") {
    $vault_id = $_POST["vault_id"];

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
    $update_date = date("Y-m-d H:i:s");

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


            $puantaj_id = kayitVarmi($company_id, $full_name, $year, $months);

            if ($puantaj_id > 0) {
                $sql = $con->prepare("UPDATE puantaj SET datas = ?,updated_at = ? WHERE id = ?");
                $sql->execute(array($records_json, $update_date, $puantaj_id));

            } else {
                $sql = $con->prepare("INSERT INTO puantaj SET company_id = ?, project_id = ?, person = ? , yil = ?, ay = ?, datas = ?");
                $sql->execute(array($company_id, $project_id, $full_name, $year, $months, $records_json));
            }
            $res = array(
                "message" => "Puantaj Başarı ile kaydedildi!",
                "status" => 200
            );

        } catch (PDOException $ex) {
            $res = array(
                "employee" => $full_name,
                "message" => "Bir hata oluştu. Hata mesajı :" . $ex->getMessage(),
                "status" => 400
            );

        }
        ;

    }
    echo json_encode($res);
    return false;


}

if ($_POST && $_POST["action"] == "puantaj-kapat") {
    $company_id = $_POST["company_id"];
    $yil = $_POST["yil"];
    $ay = $_POST["ay"];
    $state_val = $_POST["state_val"];
    $sql = $con->prepare("SELECT * FROM puantaj WHERE company_id = ? AND yil = ? AND ay= ?");
    $sql->execute(array($company_id, $yil, $ay));


    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        $up_query = $con->prepare("UPDATE puantaj SET isClosed = ? WHERE id = ?");
        $up_query->execute(array($state_val, $result["id"]));

    }

    $res = array(
        "statu" => 200,
        "message" => "Başarılı"
    );
    echo json_encode($res);
    return;

}


if ($_POST && $_POST["action"] == "bordro-gorsun") {
    $company_id = $_POST["company_id"];
    $yil = $_POST["yil"];
    $ay = $_POST["ay"];
    $state_val = $_POST["state_val"];
    $sql = $con->prepare("SELECT * FROM puantaj WHERE company_id = ? AND yil = ? AND ay= ?");
    $sql->execute(array($company_id, $yil, $ay));


    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        $up_query = $con->prepare("UPDATE puantaj SET isView = ? WHERE id = ?");
        $up_query->execute(array($state_val, $result["id"]));

    }

    $res = array(
        "statu" => 200,
        "message" => "Başarılı"
    );
    echo json_encode($res);
    return;

}



if ($_POST && $_POST["action"] == "maas_hesapla") {

    // POST verilerini al
    $company_id = $_POST["company_id"];
    $yil = $_POST["yil"];
    $ay = $_POST["ay"];

    //Puantaj tablosundan verileri çeker
    $pnt_query = $con->prepare("SELECT * FROM puantaj WHERE company_id = ? AND yil = ? AND ay= ?");
    $pnt_query->execute(array($company_id, $yil, $ay));

    // Ayın ilk günü
    $start_date = date("Y-m-d", strtotime("$yil-$ay-01"));

    // Ayın son günü
    $end_date = date("Y-m-d", strtotime("last day of $yil-$ay"));

    //Döneme ait parametre var mı yok mu kontrol edilir
    $prm = $con->prepare("SELECT * FROM parameters WHERE param_type = ? 
                                                    AND start_date <= ? 
                                                    AND end_date >=  ? 
                                                    ORDER by id DESC ");
    $prm->execute(array(1, $start_date, $end_date));
    //$result = $prm->fetchAll(PDO::FETCH_ASSOC);

    while ($row = $prm->fetch(PDO::FETCH_ASSOC)) {
        $pnt[] = $row["param_val"];
    }

    // Eğer döneme ait parametre yoksa hata mesajı döndür
    if (empty($pnt)) {
        $res = array(
            "statu" => 400,
            "message" => "Seçilen döneme ait parametre bulunamadı!",
        );
        echo json_encode($res);
        return;
    }


    $statu = 0;
    $ucret_statu = 200;

    // Puantaj tablosundan gelen verileri döngüye al
    while ($row = $pnt_query->fetch(PDO::FETCH_ASSOC)) {

        // Personel id'sini al ve günlük ücretini çek
        $per_sql = $con->prepare("SELECT daily_wages FROM person WHERE id = ?");
        $per_sql->execute(array($row["person"]));
        $person = $per_sql->fetch(PDO::FETCH_OBJ);

        // Eğer boş dönerse 0 olarak ayarla
        if (empty($person)) {
            $person = 0;
        }

        $toplam_ucret = 0;
        $maas_data = array();

        ## puantaj tablosunda personeli çalışma verileri alıyoruz
        $data = $row["datas"];

        ## veriyi php formatına çeviriyoruz
        $data_json = json_decode($data);

        #veriler arasında dolaşıyoruz
        foreach ($data_json as $value => $key) {
            //     // $params = puantaj_val($key);
            // puantaj tablosundaki datas alanından puantaj kodunu alarak turunu buluyoruz


            $sql = $con->prepare("SELECT * FROM puantajturu WHERE id = ?");
            $sql->execute(array($key));
            $puantaj = $sql->fetch(PDO::FETCH_ASSOC);

            // Eğer "key (puantaj kodu)" değeri boş değilse ve null değilse
            if ($key != null) {
                $tur = $puantaj["Turu"];
                if ($tur != "Saatlik") {



                    // // #Saatlik ücret dışındaki çalışma ise günlük saat üzerinden puantajdaki saat hesaplanır
                    $ctrl_date = DateTime::createFromFormat('d.m.Y', $value)->format('Y-m-d');


                    try {

                        // Döngüye alına tarihlerin parametrelerini kontrol ederek ücreti alır
                        $prm = $con->prepare("SELECT * FROM parameters WHERE param_type = ? 
                                      AND STR_TO_DATE(start_date,'%Y-%m-%d') <= ? 
                                      AND STR_TO_DATE(end_date,'%Y-%m-%d') >= ? 
                                      AND STR_TO_DATE(start_date,'%Y-%m-%d') <= ?
                                      AND STR_TO_DATE(end_date,'%Y-%m-%d') >= ? 
                                      ORDER by id DESC LIMIT 1 ");
                        $prm->execute(array(1, $ctrl_date, $ctrl_date, $start_date, $end_date));


                        // // Eğer parametre varsa döngüye alınır
                        while ($paramVal = $prm->fetch(PDO::FETCH_ASSOC)) {
                            //



                            // //Döneme ait paramaetre varsa günlük ücreti alır yoksa geri mesaj döner
                            if (isset($paramVal["param_val"])) {
                                $ucret = $paramVal["param_val"];
                                if (isset($person->daily_wages)) {
                                    if ($person->daily_wages > $ucret) {
                                        $ucret = $person->daily_wages;
                                    }

                                }
                            } else {
                                $ucret = 0;
                                $ucret_statu = 400;
                                $res = array(
                                    "statu" => 400,
                                    "message" => "Seçilen döneme ait parametre bulunamadı!",
                                );
                                echo json_encode($res);
                                return;
                            }


                            // puantaj kodunun saat karşılığı alınır
                            $puantaj_saati = $puantaj["PuantajSaati"];

                            // Günlük ücret hesaplanır(buradaki 8 daha sonra parametre olarak alınacak)
                            $tutar = ($puantaj_saati / 8) * $ucret;

                            // Toplam ücret hesaplanır
                            $toplam_ucret = $toplam_ucret + $tutar;


                            // veritabanına kayıt edilecek verileri oluştur
                            if (isset($puantaj_turu_tutar[$puantaj["id"]])) {
                                // Eğer "tur_id" değeri zaten varsa, mevcut "tutar" değerine ekleyin
                                $puantaj_turu_tutar[$puantaj["id"]]["tutar"] += $tutar;
                            } else {
                                // Yoksa yeni bir öğe ekleyin ve hesaplanmış "tutar" değerini kullanın
                                $puantaj_turu_tutar[$puantaj["id"]] = array(
                                    "tur_id" => $puantaj["id"],
                                    "tutar" => $tutar
                                );

                            }
                            $maas_data = json_encode($puantaj_turu_tutar);

                            // Kontrol etmek için verileri diziye ekler
                            // $pnt[] = "Tarih: " . $value . ";" .
                            //     "Parametre: " . $paramVal["param_val"] . ";" .
                            //     "Saat: " . $puantaj["PuantajSaati"] . ";" .
                            //     "Tutar: " . $tutar . ";" .
                            //     "Personel:" . $row["person"];

                        }
                        //code here
                    } catch (PDOException $ex) {
                        $res = array(
                            "statu" => 400,
                            "message" => $ex->getMessage(),
                        );
                        echo json_encode($res);
                        return;
                    }


                }
            }

        }

        $maas_id = maasHesaplimi($company_id, $row["person"], $yil, $ay);

        if ($maas_id > 0) {

            try {

                $calc_date = date("Y-m-d H:i:s");
                $upd_maas_query = $con->prepare("UPDATE maas_gelir SET company_id = ? ,
                                                            person_id = ?,  yil = ? , ay = ?, 
                                                            datas= ? , toplam_maas = ? , calc_date = ?  WHERE id = ? ");
                $upd_maas_query->execute(array($company_id, $row["person"], $yil, $ay, $maas_data, $toplam_ucret, $calc_date, $maas_id));
                $message = "İşlem Başarılı!";
            } catch (PDOException $ex) {
                $message = $ex->getMessage();
            }


        } else {
            if (!empty($maas_data)) {

                $ins_maas_query = $con->prepare("INSERT INTO maas_gelir SET company_id = ? ,
                                                                        person_id = ?, yil = ? , ay = ?, 
                                                                        datas= ? , toplam_maas = ?");
                $ins_maas_query->execute(array($company_id, $row["person"], $yil, $ay, $maas_data, $toplam_ucret));
                $statu = 200;
                $message = "İşlem Başarılı!";
            }
        }
    }

    $res = array(
        "statu" => 200,
        "message" => $message,
    );
    echo json_encode($res);
    return;

}




function puantaj_val($id)
{
    global $con;
    // Eğer $id boş veya null ise, null döndür
    if (empty($id)) {
        return null;
    }

    // SQL sorgusunu hazırlayıp çalıştır
    // $sql = $con->prepare("SELECT * FROM parameters WHERE param_type REGEXP CONCAT('(^|[[:<:]])', ?, '([[:>:]]|$)')"); //Dizi halinde sorgulamak için
    $sql = $con->prepare("SELECT * FROM puantajturu WHERE id = ?");
    $sql->execute(array($id));
    $params = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $params;

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

if ($_POST && $_POST["action"] == "person-count") {
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);

        try {

            $sql = $con->prepare("SELECT * FROM person WHERE company_id = ?");
            $sql->execute([$id]);
            $result = $sql->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                // JSON formatında çıktı veriyoruz
                echo json_encode($result);
            } else {
                // Sonuç yoksa boş bir JSON dizisi döndürün
                echo json_encode([]);
            }
        } catch (PDOException $e) {
            // Hata durumunda hata mesajı döndürülür
            echo json_encode(["error" => $e->getMessage()]);
        }
    } else {
        // ID belirtilmemişse boş bir JSON dizisi döndürülür
        echo json_encode([]);
    }
}

if ($_POST && $_POST["action"] == "auth-define") {
    $data = $_POST["data"];
    $roleId = $_POST["roleId"];

    // JSON verisini PHP array olarak decode et
    $data_array = json_decode($data, true);

    // Eğer decode işlemi başarılı ise (JSON hatası yoksa)
    if (json_last_error() === JSON_ERROR_NONE) {
        // JSON array'ini tekrar JSON string'e çevir
        $data_json = json_encode($data_array);

        if (!empty($roleId)) {
            try {
                if (roleVarmi($roleId)) {
                    // PDO prepare ve execute kullanarak veriyi ekle
                    $sql = $con->prepare("UPDATE userauths set auths = ?");
                    $sql->execute(array($data_json));
                } else {

                    // PDO prepare ve execute kullanarak veriyi ekle
                    $sql = $con->prepare("INSERT INTO userauths (roleId, auths) VALUES (?, ?)");
                    $sql->execute(array($roleId, $data_json));
                }

                $status = 200;
                $message = "İşlem başarılı";
            } catch (PDOException $ex) {
                $status = 400;
                $message = "Hata: " . $ex->getMessage();
            }
        } else {
            $status = 400;
            $message = "Role ID boş olamaz.";
        }
    } else {
        $status = 400;
        $message = "Geçersiz JSON verisi.";
    }

    $res = array(
        "state" => $status,
        "message" => $message
    );
    echo json_encode($res);
    exit();
}

