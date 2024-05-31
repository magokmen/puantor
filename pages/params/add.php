<?php
require_once "../../include/requires.php";

//  echo "Kullanıcı id :" . $account_id ;
if ($account_id == '') {
    go("logout.php", "");

}


if ($_POST) {

    $company_id = $_POST["company"];
    $param_name = $_POST["param_name"];
    $param_type = $_POST["param_type"];
    $start_date = $_POST["start_date"];
    $end_date = $_POST["end_date"];
    $param_val = $_POST["param_val"];
    $calc_type = $_POST["calc_type"];
    $description = $_POST["description"];

    //Tarihlerin doğruluğunu kontrol edin
    if ($start_date > $end_date) {
        $res = array(
            "status" => 400,
            "message" => "Bitiş tarihi başlama tarihinden küçük olamaz!"
        );
    } else {
        try {
            $insq = $con->prepare("INSERT INTO parameters SET 
                account_id = ? , 
                company_id = ? , 
                param_name = ? , 
                param_type = ? , 
                start_date = ? , 
                end_date = ? , 
                param_val = ? , 
                calc_type = ? , 
                description = ? , 
                state = ? 
            ");
            $insq->execute(array($account_id, $company_id, $param_name, $param_type, $start_date, $end_date, $param_val, $calc_type, $description, 1));

            $res = array(
                "status" => 200,
                "message" => "İşlem başarıyla gerçekleşti!",
                "page" => "params/main",
                "pTitle" => "Parametre Listesi"
            );

        } catch (PDOException $ex) {
            $res = array(
                "status" => 500,
                "message" => "Veritabanı hatası: " . $ex->getMessage()
            );

        }
    }

    echo json_encode($res); // JSON yanıtı döndürün
    return;
}


?>

<style>
    .card {
        word-wrap: normal;
    }
    .container {
        padding: 0px !important;
        max-width: 100% !important;
    }
</style>

<form id="myForm">
    <div class="container">


        <div class="row">
            <div class="form-group col-md-6">
                <label for="company">Şirket Adı</label> <span style="color:red">(*) Parametrenin geçerli
                    olacağı
                    şirketiniz</span>
                <?php echo $func->companies("company", ""); ?>
            </div>
            <div class="form-group col-md-6">
                <label for="param_name">Parametre Adı</label><span style="color:red"> (*)</span>
                <input required id="param_name" name="param_name" type="text" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="start_date">Başlama Tarihi </label><span style="color:red"> (*)</span>

                <div class="input-group date" id="startdate" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#startdate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input required type="text" id="start_date" name="start_date"
                        class="form-control datetimepicker-input" data-target="#startdate"
                        data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask />

                </div>
            </div>
            <div class="form-group col-md-6">
                <label for="end_date">Bitiş Tarihi </label><span style="color:red"> (*)</span>

                <div class="input-group date" id="enddate" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#enddate" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input required type="text" id="end_date" name="end_date" class="form-control datetimepicker-input"
                        data-target="#enddate" data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy"
                        data-mask />

                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                <label for="param_type">Parametre Türü</label> <span style="color:red"> (*)</span>
                <select required name="param_type" id="param_type" class="select2">
                    <option value="">Tür seçiniz</option>
                    <option value="1" selected>Günlük Ücret</option>
                    <option value="2">Kesinti</option>
                    <option value="3">Gelir</option>
                </select>
            </div>



            <div class="form-group col-md-3">
                <label for="param_val">Değer</label> <span style="color:red"> (*)</span>
                <input id="param_val" name="param_val" type="text" class="form-control">
            </div>

            <div class="form-group col-md-3">
                <label for="calc_type">Hesaplama Türü</label> <span style="color:red"> (*)</span>
                <select required name="calc_type" id="calc_type" class="select2">
                    <option value="">Tür seçiniz</option>
                    <option value="1">TL</option>
                    <option value="2">Günlük Ücretin yüzdesi</option>
                </select>
            </div>

        </div>



        <div class="row">

            <div class="form-group col-md-6">
                <input type="checkbox" checked disabled class="check">
                <label class="ml-2">Yayında mı?</label>


            </div>


            <div class="form-group col-md-6">
                <label for="description">Açıklama</label>
                <textarea type="text" name="description" class="form-control"></textarea>
            </div>

        </div>
    </div>
</form>

