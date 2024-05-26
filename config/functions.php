<?php

include "connect.php";

class Functions
{

    public function security($val)
    {
        return htmlspecialchars($val);
    }
    public function preg_match($val) {
        return preg_match("/^[a-zA-ZÇĞİÖŞÜçğıöşü' -]*$/u", $this->security($val));
    }
    
    
    public static function data($value)
    {
        global $kayit; // Eğer $kayit global bir değişken ise
        return htmlspecialchars($kayit[$value], ENT_QUOTES);
    }

    public static function showToastrSuccessMessage()
    {
        echo '
        <script>
            const options = {
                closeButton: true,
                positionClass: "toast-top-center",
            };
            toastr.success("İşlem Durumu", "İşlem başarı ile yapıldı.", options);
        </script>
    ';
    }

    
    public function user_info($id, $field)
    {
        global $con;
        $sql = $con->prepare('SELECT * FROM users WHERE id = ?');
        $sql->execute([$id]);
        $user = $sql->fetch(PDO::FETCH_ASSOC);
        return $user[$field];

    }

    // public function teklifDurum($val)
    // {
    //     $array_tur = ["Bekleyen", "Teklif Verildi", "İptal Edildi"];
    //     $selectHTML = '<select id="Durumu" name="Durumu" class="form-control select2" style="width: 100%;">';
    //     foreach ($array_tur as $durum) {
    //         $selectHTML .= '<option ' . ($durum == $val ? " selected" : "") . ' value="' . $durum . '">' . $durum . '</option>';
    //     }
    //     $selectHTML .= '</select>';
    //     echo $selectHTML;
    // }

    // public function organizasyon_turu($tur)
    // {
    //     $array_tur = ["Düğün", "Kına", "Genel"];
    //     $selectHTML = '<select id="Turu" name="Turu" class="form-control select2" style="width: 100%;">';
    //     foreach ($array_tur as $org) {
    //         $selectHTML .= '<option ' . ($org == $tur ? " selected" : "") . ' value=' . $org . '>' . $org . '</option>';
    //     }
    //     $selectHTML .= '</select>';
    //     echo $selectHTML;
    // }

    public function companies($name, $id)
    {
        global $con;
        global $account_id;
        $html = ' <select required id="' . $name . '" name="' . $name . '"  class="select2"
                            style="width: 100%;">
                            <option value="">Firma Seçiniz</option>';

        $sql = $con->prepare("Select id,company_name from companies WHERE account_id = ?");
        $sql->execute(array($account_id));

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $company_name = $row['company_name'];
            $html .= '<option ' . ($id == $row["id"] ? " selected" : '') . ' value=' . $row["id"] . '>' . $company_name . '</option>';
        }
        ;

        $html .= ' </select>';
        echo $html;
    }


    public function projects($name,$company_id, $id)
    {
        global $con;
        $html = ' <select required id="' . $name . '" name="' . $name . '"  class="select2"
                            style="width: 100%;">
                            <option value="">Proje Seçiniz</option>';

        $sql = $con->prepare("Select id,project_name from projects WHERE company_id = ?");
        $sql->execute(array($company_id));

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $project_name = $row['project_name'];
            $html .= '<option ' . ($id == $row["id"] ? " selected" : '') . ' value=' . $row["id"] . '>' . $project_name . '</option>';
        }
        ;

        $html .= ' </select>';
        echo $html;
    }

    public function projectsMultiple($name, $company_id, $ids)
    {
        global $con;
        $html = '<select id="' . $name . '" name="' . $name . '[]" multiple class="select2" style="width: 100%;">
                    <option disabled value="0">Proje Seçiniz</option>';
    
        $sql = $con->prepare("SELECT id, project_name FROM projects WHERE company_id = ?");
        $sql->execute(array($company_id));
    
        $proarray = explode('|', $ids);
    
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $project_name = $row['project_name'];
            $selected = in_array($row["id"], $proarray) ? " selected" : "";
            $html .= '<option value="' . $row["id"] . '"' . $selected . '>' . htmlspecialchars($project_name) . '</option>';
        }
    
        $html .= '</select>';
        echo $html;
    }



    
    function getProjectNames($projectIds, $delimiter = "|") {
        global $func; // $func global değişkenini kullanmak için global bildirimi yapıyoruz
    
        if ($projectIds) {
            // Proje ID'lerini ayır
            $proArray = explode($delimiter, $projectIds);
            $projects = "";
    
            // Her bir proje ID'sine karşılık gelen projeyi al ve birleştir
            foreach ($proArray as $projectId) {
                $projects .= $func->getProjectName($projectId) . ", ";
            }
    
            // Sondaki virgülü kaldır ve sonucu döndür
            return rtrim($projects, ", ");
        }
    
        return "";
    }
    

    function shortProjectsName($data, $delimiter = ',') {
        $pieces = explode($delimiter, $data); // Veriyi belirli bir ayraçtan bölelim
    
        if (count($pieces) >= 2) {
            $first_piece = trim($pieces[0]); // İlk parçayı alalım ve boşlukları temizleyelim
            $second_piece = trim($pieces[1]); // İkinci parçayı alalım ve boşlukları temizleyelim
    
            // İkinci parçanın ilk karakterini ve dört noktayı ekleyerek kısaltılmış veriyi oluşturalım
            $shorted = $first_piece . $delimiter . substr(trim($second_piece), 0, 1) . "....";
            return $shorted;
        } else {
            return $data;
        }
    }

    public static function select_salon($id)
    {
        global $con;
        $places = ' <select id="salonadi" name="salonadi" required class="form-control select2"
                            style="width: 100%;">
                            <option value="0" disabled>Seçiniz</option>';

        $sql = "Select * from salonlar";
        $sql = $con->prepare($sql);
        $sql->execute();

        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
            $salonAdi = $row['SalonAdi'];
            $salonid = $row['id'];

            $places .= '<option ' . ($id == $row["id"] ? " selected" : "") . ' value=' . $salonid . '>' . $salonAdi . '</option>';
        }
        ;

        $places .= ' </select>';
        echo $places;
    }


    function getPuantajTuruList($turu)
    {
        global $con;
        // SQL sorgusu ve verilerin alınması
        $sql = $con->prepare("SELECT * FROM puantajturu WHERE Turu = ?");
        $sql->execute(array($turu));

        // Başlangıç HTML
        $output = '<ul class="nav flex-column">';

        // Veritabanından gelen verilerle liste öğeleri oluşturma
        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
            $output .= '
            <li class="nav-item">
                <div class="user-block">
                    <span class="avatar" data-id="' . $result["id"] . '" style="background-color:' . htmlspecialchars($result["ArkaPlanRengi"])
                . ';color:' . $result["FontRengi"] . '">' . htmlspecialchars($result["PuantajKod"]) . '</span>
                    <span class="username">' . htmlspecialchars($result["PuantajAdi"]) . '</span>
                    <span class="description">' . htmlspecialchars($result["Turu"]) . '</span>
                </div>
            </li>';
        }

        // Kapanış HTML
        $output .= '</ul>';

        return $output;
    }

    function puantajClass($turu)
    {
        global $con;
        $pcq = $con->prepare("SELECT * FROM puantajturu WHERE id = ?");
        $pcq->execute(array($turu));
        $result = $pcq->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo "<td class='gun noselect' data-id=" . $result["id"] . " style='background:" . $result["ArkaPlanRengi"] . ";color:" . $result["FontRengi"] . "'>" . $result["PuantajKod"] . "</td>";
        } else {
            echo "<td class='gun noselect'></td>";
        }
    }

    function getMonthsSelect($name = 'months', $selected = null)
    {
        // Ayları tanımla
        $months = [
            1 => 'Ocak',
            2 => 'Şubat',
            3 => 'Mart',
            4 => 'Nisan',
            5 => 'Mayıs',
            6 => 'Haziran',
            7 => 'Temmuz',
            8 => 'Ağustos',
            9 => 'Eylül',
            10 => 'Ekim',
            11 => 'Kasım',
            12 => 'Aralık'
        ];

        // Eğer seçili ay belirtilmemişse, içinde bulunduğumuz ayı al
        if ($selected === null) {
            $selected = date('n');
        }

        // Select elemanını başlat
        $select = "<select id=" . $name . " class='select2' style='min-width:100%' name=\"$name\">\n";

        // Ayları select elemanına ekle
        foreach ($months as $key => $value) {
            $isSelected = ($key == $selected) ? 'selected' : '';
            $select .= "<option value=\"$key\" $isSelected>$value</option>\n";
        }

        // Select elemanını kapat
        $select .= "</select>\n";

        return $select;
    }
    function daysInMonth($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }

    public static function getCompanyName($id)
    {
        global $con;
        $sql = $con->prepare("Select * from companies where id = ?");
        $sql->execute(array($id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['company_name'];

        } else {
            return '';
        }

    }
    
    public static function getProjectName($id)
    {
        global $con;
        $sql = $con->prepare("Select * from projects where id = ?");
        $sql->execute(array($id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['project_name'];

        } else {
            return '';
        }

    }

    public static function paket_adi($id)
    {
        global $con;
        $sql = "Select * from paketler where id = ?";
        $sql = $con->prepare($sql);
        $sql->execute(array($id));
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['PaketAdi'];

        } else {
            return $id;
        }

    }

    public static function KdvOranları($name, $val)
    {
        echo '<select required name="' . $name . '" class="selectpicker" data-style="btn-outline-secondary">
            <option disabled>Oran Seçiniz </option>
            <option ' . ($val == 20 ? 'selected' : '') . ' value="20">%20</option>
            <option ' . ($val == 18 ? 'selected' : '') . ' value="18">%18</option>
            <option ' . ($val == 10 ? 'selected' : '') . ' value="10">%10</option>
            <option ' . ($val == 8 ? 'selected' : '') . ' value="8">%8</option>
            <option ' . ($val == 1 ? 'selected' : '') . ' value="1">%1</option>
        </select>';
    }

    public static function getTableColumns($tableName)
    {
        global $con; // $ac değişkeni global olarak tanımlanmalı veya fonksiyon içinde tanımlanmalıdır

        $ttquery = $con->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'yakarallevici' AND TABLE_NAME = ?");
        $ttquery->execute([$tableName]);

        $columns = '';
        $field = '';
        $insquery = '';
        while ($row = $ttquery->fetch(PDO::FETCH_ASSOC)) {
            if ($row['COLUMN_NAME'] != "ID") {
                $columns .= '$' . $row['COLUMN_NAME'] . ' = @$_POST["' . $row['COLUMN_NAME'] . '"];' . "\n";
                $field .= $row['COLUMN_NAME'] . ' = ? , ' . "\n";
                $insquery .= '$' . $row['COLUMN_NAME'] . ',';
            }
        }
        $result = $columns . "\n" .
            '$insq = $con->prepare("INSERT INTO ' . $tableName . ' SET ' . $field . '");' . "\n" .
            '$insq->execute(array(' . $insquery . '));';


        echo '<script> console.log(`' . addslashes($result) . '`); </script>';
    }


    public static function jsonEncode($params)
    {
        $params_json = htmlspecialchars(json_encode($params, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8');
        return $params_json;
    }

}



function emailVarmi($mail_address,$table="accounts")
{

    global $con;
    $sql = $con->prepare("SELECT email FROM $table WHERE email = ? ");
    $sql->execute(array($mail_address));
    $email_varmi = $sql->fetch(PDO::FETCH_ASSOC);
    return $email_varmi ? true : false;
}

//giriş ve kayıt ol sayfasındaki mesajları vermek için
function showAlert($message, $type = "danger")
{
    echo '<div class="alert alert-' . $type . '" role="alert">' . $message . '</div>';

}
function showMessage($message, $type = "danger")
{
    echo '<script>
            showMessage('.$message.','.$type.');
        </script>';
}

function go($url, $time)
{
    if ($time != 0) {
        header("Refresh:$time;url=$url");
    } else {
        header("location:$url");
    }
}



function kayitVarmi($company_id,$project_id, $person, $year, $months)
{
    global $con;
    $sql = $con->prepare("SELECT * FROM puantaj where company_id = ? AND project_id = ? AND person = ? AND yil = ? AND ay = ? ");
    $sql->execute(array($company_id ,$project_id, $person, $year, $months));
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    return $result ? $result["id"] : 0; // Eğer kayıt bulunamazsa null döndür
}