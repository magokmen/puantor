<?php
require_once "../../include/requires.php";

//  echo "Kullanıcı id :" . $account_id ;
if ($account_id == '') {
    go("logout.php", "");

}

$id = isset($_GET["id"]) ? $_GET["id"] : $_POST["id"];


if ($_POST && $_POST["method"] == "update") {
    $company_id = $_POST["company"];
    $param_name = $_POST["param_name"];
    $param_type = $_POST["param_type"];
    $start_date = date("Y-m-d", strtotime($_POST["start_date"]));
    $end_date = date("Y-m-d", strtotime($_POST["end_date"]));
    $param_val = $_POST["param_val"];
    $state = $_POST["state"];
    $calc_type = $_POST["calc_type"];
    $description = $_POST["description"];

    // Tarihlerin doğruluğunu kontrol edin
    if ($start_date > $end_date) {
        $res = array(
            "status" => 400,
            "message" => "Bitiş tarihi başlama tarihinden küçük olamaz!"
        );
    } else {
        try {
            $updq = $con->prepare("UPDATE parameters SET 
                company_id = ? , 
                param_name = ? , 
                param_type = ? , 
                start_date = ? , 
                end_date = ? , 
                param_val = ? , 
                calc_type = ? , 
                state = ? ,
                description = ? 
                WHERE id = ?
            ");
            $updq->execute(array($company_id, $param_name, $param_type, $start_date, $end_date, $param_val, $calc_type,$state, $description, $id));

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
    exit();
}


$sql = $con->prepare("SELECT * FROM parameters WHERE id = ?");
$sql->execute(array($id));
$param = $sql->fetch(PDO::FETCH_OBJ);

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

<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">


            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Parametre Listesi"
                        data-toggle="tab">Tüm Parametreler</a>
                </li>
            </ul>
            <?php
            $params = array(
                "method" => "update",
                "id" => $id
            );
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Parametre"
                onclick="submitFormReturnJson('params/edit','<?php echo $params_json ?>')"
                class="btn btn-info">Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">


        <form id="myForm">
            <div class="container">


                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="company">Şirket Adı</label> <span style="color:red">(*) Parametrenin geçerli
                            olacağı
                            şirketiniz</span>
                        <?php echo $func->companies("company", $param->company_id); ?>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="param_name">Parametre Adı</label><span style="color:red"> (*)</span>
                        <input required id="param_name" name="param_name" type="text" class="form-control"
                            value="<?php echo $param->param_name; ?>">
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
                                value="<?php echo date("d.m.Y", strtotime($param->start_date));?>" class="form-control datetimepicker-input"
                                data-target="#startdate" data-inputmask-alias="datetime"
                                data-inputmask-inputformat="dd.mm.yyyy" data-mask />

                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="end_date">Bitiş Tarihi </label><span style="color:red"> (*)</span>

                        <div class="input-group date" id="enddate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#enddate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input required type="text" id="end_date" name="end_date"
                                class="form-control datetimepicker-input" value="<?php echo date("d.m.Y", strtotime($param->end_date)); ?>"
                                data-target="#enddate" data-inputmask-alias="datetime"
                                data-inputmask-inputformat="dd.mm.yyyy" data-mask />

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="param_type">Parametre Türü</label> <span style="color:red"> (*)</span>
                        <!-- <?php echo $func->paramsTur("param_type[]", ""); ?> -->
                        <select name="param_type" id="param_type" class="select2 form-control">

                            <option value="0">Parametre Türü seçiniz</option>
                            <option <?php echo $param->param_type == 1 ? "selected" : "" ;?> value="1">Günlük Ücret</option>
                            <option <?php echo $param->param_type == 2 ? "selected" : "" ;?> value="2">Saatlik Ücret</option>

                        </select>
                    </div>



                    <div class="form-group col-md-3">
                        <label for="param_val">Değer</label> <span style="color:red"> (*)</span>
                        <input id="param_val" name="param_val" type="text" class="form-control"
                            value="<?php echo $param->param_val; ?>">
                    </div>

                    <div class="form-group col-md-3">
                        <label for="calc_type">Hesaplama Türü</label> <span style="color:red"> (*)</span>
                        <select required name="calc_type" id="calc_type" class="select2 form-control">
                            <option value="">Tür seçiniz</option>
                            <option <?php echo $param->calc_type == 1 ? "selected" : "" ;?> value="1">TL</option>
                            <option <?php echo $param->calc_type == 2 ? "selected" : "" ;?> value="2">Günlük Ücretin yüzdesi</option>
                        </select>
                    </div>

                </div>



                <div class="row">

                    <div class="form-group col-md-6">
                        <input  type="checkbox" name="state" <?php echo $param->state == "on" ? "checked" : "" ;?> class="check">
                        <label class="ml-2">Yayında mı?</label>


                    </div>


                    <div class="form-group col-md-6">
                        <label for="description">Açıklama</label>
                        <textarea type="text" name="description"
                            class="form-control"><?php echo $param->description; ?></textarea>
                    </div>

                </div>
            </div>
        </form>


    </div>
</div>
<script src="../../src/component.js"></script>