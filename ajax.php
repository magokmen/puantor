<?php

require_once "include/requires.php";


if ($_POST && $_POST["action"] == "kasa") {
    $vault_id = $_POST["vault_id"];
    $_SESSION["vault_id"] = $vault_id;

    $sql = $con->prepare("SELECT ct.*,c.case_name 
                          FROM cases_transactions ct
                          LEFT JOIN cases c ON c.id = ct.vault_id 
                          WHERE vault_id = ?");
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

if ($_POST && $_POST["action"] == "kasa-ozeti") {
    $vault_id = $_POST["vault_id"];

    try {
        //GELİRLER
        $sql = $con->prepare("SELECT SUM(amount) as total_income FROM cases_transactions WHERE sub_type = ? and vault_id = ? ");
        $sql->execute(array(2, $vault_id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        $total_income = $result["total_income"] ? $result["total_income"] : 0;

        //GİDERLER
        $sql = $con->prepare("SELECT SUM(amount) as total_expense FROM cases_transactions WHERE sub_type = ? and vault_id = ? ");
        $sql->execute(array(1, $vault_id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        $total_expense = $result["total_expense"] ? $result["total_expense"] : 0;

        $res = array(
            "status" => 200,
            "total_income" => tlFormat($total_income),
            "total_expense" => tlFormat($total_expense),
            "total_balance" => tlFormat($total_income - $total_expense),
            "total_profit" => $total_income != 0 ? number_format(($total_income - $total_expense) / $total_income * 100, 2) : 0,
        );
    } catch (PDOException $ex) {
        $res = array(
            "status" => 400,
            "message" => $ex->getMessage()
        );
    }

    // header('Content-Type: application/json');
    echo json_encode($res);
    return;
}



if ($_POST && $_POST["action"] == "todos") {

    $account_id = isset($_SESSION["accountID"]) ? $_SESSION["accountID"] : 0;
    $company_id = isset($_POST["company_id"]) ? $_POST["company_id"] : 0;
    $project_id = isset($_POST["project_id"]) ? $_POST["project_id"] : 0;
    $todo_name = isset($_POST["todo_name"]) ? $_POST["todo_name"] : "";
    $state = 1;
    $action_type = isset($_POST["action_type"]) ? $_POST["action_type"] : "";
    $page = isset($_POST["page"]) ? $_POST["page"] : 1;
    $firstRec = ($page - 1) * 5;
    $pageLimit = $page * 5;
    $todo_id = isset($_POST["todo_id"]) ? $_POST["todo_id"] : 0;

    try {

        if ($action_type == "add-todo") {
            $sql = $con->prepare("INSERT INTO todos (account_id, company_id, project_id, todo_name, state) VALUES (?,?, ?, ?, ?)");
            $sql->execute(array($account_id, $company_id, $project_id, $todo_name, $state));
        }

        if ($action_type == "delete-todo") {
            $sql = $con->prepare("DELETE FROM todos WHERE id = ?");
            $sql->execute(array($todo_id));
        }



        $html = '';
        $sql = $con->prepare("SELECT * FROM todos WHERE company_id = ? order by id desc LIMIT {$firstRec}, {$pageLimit}");
        $sql->execute(array($company_id));
        $todos = $sql->fetchAll(PDO::FETCH_OBJ);
        $i = 0;
        foreach ($todos as $todo) {
            $html .= '
           <li>
              <div class="widget-content">

                <div class="widget-content-wrapper">

                  <!-- drag handle -->
                  <span class="handle">
                    <i class="fas fa-ellipsis-v"></i>
                    <i class="fas fa-ellipsis-v"></i>
                  </span>
                  <!-- checkbox -->
                  <div class="icheck-primary d-inline ml-2">
                    <input type="checkbox" value="" name="todo' . $i . '" id="todoCheck' . $i . '">
                    <label for="todoCheck' . $i . '"></label>
                  </div>


                  <div class="widget-content-left ml-2">
                    <div class="widget-heading">' . $todo->todo_name . '</div>
                    <div class="widget-subheading">
                      <div>' . $func->getProjectName($todo->project_id) . '<div class="badge badge-pill badge-info ml-2">Yeni</div>
                      </div>
                    </div>
                  </div>
                  <!-- todo text -->';


            // Calculate the time elapsed since the registration date
            $registrationDate = strtotime($todo->created_at);
            $currentDate = time();
            $timeElapsed = $currentDate - $registrationDate;

            // Convert the time elapsed to minutes
            $minutesElapsed = round($timeElapsed / 60);

            $html .= '<small class="badge badge-danger"><i class="far fa-clock"></i> ' . $minutesElapsed . ' mins</small>
                  <!-- General tools such as edit or delete-->
                  <div class="tools widget-content-right">
                    <i class="fas fa-edit info edit-todo" data-id="' . $todo->id . '"></i>
                    <i class="fas fa-trash danger del-todo" data-id="' . $todo->id . '"></i>
                  </div>
                </div>
              </div>
            </li>
';
            $i++;
        }

        $res = array(
            "statu" => 200,
            "data" => $html,
        );
    } catch (PDOException $ex) {
        $res = array(
            "statu" => 200,
            "data" => $ex->getMessage()
        );
    }

    // header('Content-Type: application/json');
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

    if (permtrue("bordrohesapla")) {

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
        $prm = $con->prepare("SELECT * FROM parameters WHERE company_id = ? and param_type = ? 
                                                    AND STR_TO_DATE(start_date,'%Y-%m-%d') <= ? 
                                                    AND STR_TO_DATE(end_date,'%Y-%m-%d') >=  ? 
                                                    ORDER by id DESC ");
        $prm->execute(array($company_id, 1, $start_date, $end_date));
        //$result = $prm->fetchAll(PDO::FETCH_ASSOC);

        while ($row = $prm->fetch(PDO::FETCH_ASSOC)) {
            $pnt[] = $row["param_val"];
        }

        // Eğer döneme ait parametre yoksa hata mesajı döndür
        if (empty($pnt)) {
            $res = array(
                "statu" => 400,
                "message" => "Bu firmanın seçilen döneme ait parametresi bulunamadı!",
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
            $person_id = 0;


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



                        // Tarihi kontrol et ve formatını değiştir
                        $ctrl_date = DateTime::createFromFormat('d.m.Y', $value)->format('Y-m-d');


                        try {

                            // Döngüye alına tarihlerin parametrelerini kontrol ederek ücreti alır
                            $prm = $con->prepare("SELECT * FROM parameters WHERE account_id = ? and param_type = ? 
                                      AND STR_TO_DATE(start_date,'%Y-%m-%d') <= ? 
                                      AND STR_TO_DATE(end_date,'%Y-%m-%d') >= ? 
                                      AND STR_TO_DATE(start_date,'%Y-%m-%d') <= ?
                                      AND STR_TO_DATE(end_date,'%Y-%m-%d') >= ? 
                                      ORDER by id DESC LIMIT 1 ");
                            $prm->execute(array($account_id, 1, $ctrl_date, $ctrl_date, $start_date, $end_date));


                            // // Eğer parametre varsa döngüye alınır
                            while ($paramVal = $prm->fetch(PDO::FETCH_ASSOC)) {
                                //



                                // //Döneme ait parametre varsa günlük ücreti alır yoksa geri mesaj döner
                                if (isset($paramVal["param_val"])) {
                                    $ucret = $paramVal["param_val"];
                                    if (isset($person->daily_wages)) {
                                        if ($person->daily_wages != null && $person->daily_wages != 0) {
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


                                if ($person_id == 0) {
                                    $person_id = $row["person"];
                                    $puantaj_turu_tutar = array();
                                };
                                // veritabanına kayıt edilecek verileri oluştur
                                if (isset($puantaj_turu_tutar[$puantaj["id"]])) {
                                    // Eğer "tur_id" değeri zaten varsa, mevcut "tutar" ve "sayi" değerlerini güncelleyin
                                    $puantaj_turu_tutar[$puantaj["id"]]["tutar"] += $tutar;
                                    $puantaj_turu_tutar[$puantaj["id"]]["sayi"] += 1;
                                } else {
                                    // Yoksa yeni bir öğe ekleyin ve hesaplanmış "tutar" ve "sayi" değerlerini kullanın
                                    $puantaj_turu_tutar[$puantaj["id"]] = array(
                                        "tur_id" => $puantaj["id"],
                                        "tutar" => $tutar,
                                        "sayi" => 1
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
                    } else if ($tur == "Saatlik") {
                        // Tarihi kontrol et ve formatını değiştir
                        $ctrl_date = DateTime::createFromFormat('d.m.Y', $value)->format('Y-m-d');

                        // Döngüye alına tarihlerin parametrelerini kontrol ederek ücreti alır
                        $prm = $con->prepare("SELECT * FROM parameters WHERE account_id = ? and param_type = ? 
                                      AND STR_TO_DATE(start_date,'%Y-%m-%d') <= ? 
                                      AND STR_TO_DATE(end_date,'%Y-%m-%d') >= ? 
                                      AND STR_TO_DATE(start_date,'%Y-%m-%d') <= ?
                                      AND STR_TO_DATE(end_date,'%Y-%m-%d') >= ? 
                                      ORDER by id DESC LIMIT 1 ");
                        $prm->execute(array($account_id, 2, $ctrl_date, $ctrl_date, $start_date, $end_date));

                        // // Eğer parametre varsa döngüye alınır
                        while ($paramVal = $prm->fetch(PDO::FETCH_ASSOC)) {
                            // //Döneme ait parametre varsa günlük ücreti alır yoksa geri mesaj döner
                            if (isset($paramVal["param_val"])) {
                                $saatlik_ucret = $paramVal["param_val"];
                                
                            } else {
                                $saatlik_ucret = 0;
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

                                // Saatlik ücretin karşılığı hesaplanır
                                $tutar = ($puantaj_saati) * $saatlik_ucret;

                                // Toplam ücret hesaplanır
                                $toplam_ucret = $toplam_ucret + $tutar;

                               
                                // veritabanına kayıt edilecek verileri oluştur
                                if (isset($puantaj_turu_tutar[$puantaj["id"]])) {
                                    // Eğer "tur_id" değeri zaten varsa, mevcut "tutar" ve "sayi" değerlerini güncelleyin
                                    $puantaj_turu_tutar[$puantaj["id"]]["tutar"] += $tutar;
                                    $puantaj_turu_tutar[$puantaj["id"]]["sayi"] += 1;
                                } else {
                                    // Yoksa yeni bir öğe ekleyin ve hesaplanmış "tutar" ve "sayi" değerlerini kullanın
                                    $puantaj_turu_tutar[$puantaj["id"]] = array(
                                        "tur_id" => $puantaj["id"],
                                        "tutar" => $tutar,
                                        "sayi" => 1
                                    );
                                }
                                
                                $maas_data = json_encode($puantaj_turu_tutar);

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
                                                                        datas= ? , toplam_maas = ?, puantaj_id = ?");
                    $ins_maas_query->execute(array($company_id, $row["person"], $yil, $ay, $maas_data, $toplam_ucret, $row["id"]));
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
    } else {
        $res = array(
            "statu" => 600,
            "message" => "Bu işlemi yapma yetkiniz yok!",
        );
        echo json_encode($res);
        return;
    }
}


if ($_POST && $_POST["action"] == "add_person_toperiod") {

    $puantaj_ids = $_POST["puantaj_ids"];
    if (empty($puantaj_ids)) {
        $res = array(
            "statu" => 400,
            "message" => "Eklenecek personel bulunamadı!"
        );
    } else {

        try {

            foreach ($puantaj_ids as $puantaj_id) {
                $up_query = $con->prepare("UPDATE puantaj SET viewonPayroll = ? WHERE id = ?");
                $up_query->execute(array(1, $puantaj_id));
            }
            $res = array(
                "statu" => 200,
                "message" => "Başarılı"
            );
        } catch (PDOException $ex) {
            $res = array(
                "statu" => 400,
                "message" => $ex->getMessage()
            );
        }
    }


    echo json_encode($res);
    return;
}

if ($_POST && $_POST["action"] == "user-info") {
    $user_id = $_POST["id"];
    try {

        $sql = $con->prepare("SELECT * FROM person WHERE id = ?");
        $sql->execute(array($user_id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        $res = array(
            "statu" => 200,
            "data" => $result,
        );
        //code here
    } catch (PDOException $ex) {
        $res = array(
            "statu" => 400,
            "message" => $ex->getMessage()
        );
    }


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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['action'] == 'update_status') {
    $userId = $_POST['userId'];
    $isActive = $_POST['isActive'];

    $sql = "UPDATE users SET isActive = :isActive WHERE id = :userId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':isActive', $isActive, PDO::PARAM_INT);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Başarılı";
    } else {
        echo "Hata: Güncelleme yapılamadı.";
    }

}
