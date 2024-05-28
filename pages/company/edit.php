<?php

require_once "../../include/requires.php";

//  echo "Kullanıcı id :" . $account_id ;
if ($account_id == '') {
    go("logout.php", "");
}

$id = isset($_GET["id"]) ? $_GET["id"] : @$_POST["id"];
if ($_POST && $_POST["method"] == "add") {
    $company_name = $func->security($_POST["company_name"]);
    $company_official = $func->security($_POST["company_official"]);
    $tax_number = $func->security($_POST["tax_number"]);
    $address = $func->security($_POST["address"]);
    $open_date = $func->security($_POST["open_date"]);
    $close_date = $func->security(str_replace('dd.mm.yyyy', '', $_POST["close_date"]));
    $description = $func->security($_POST["description"]);


    try {
        $sql = $con->prepare("UPDATE companies SET account_id = ?, 
                                                    company_name = ? , 
                                                    company_official = ?,
                                                    tax_number = ?, 
                                                    address = ?,
                                                    open_date = ?,
                                                    close_date = ?,
                                                    description = ? WHERE id = ?");

        $sql->execute(
            array(
                $account_id,
                $company_name,
                $company_official,
                $tax_number,
                $address,
                $open_date,
                $close_date,
                $description,
                $id
            )
        );
    } catch (PDOException $ex) {
        showAlert("Error: " . $ex->getMessage());
    }
}


$sql = $con->prepare("SELECT * FROM companies where id = ?");
$sql->execute(array($id));
$result = $sql->fetch(PDO::FETCH_OBJ);


?>


<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">


            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Şirket Listesi"
                        data-toggle="tab">Şirket Listesi</a>
                </li>
            </ul>
            <?php
            $params = array(
                "method" => "add",
                "id" => $id
            );
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Personel"
                onclick="submitFormbyAjax('company/edit','<?php echo $params_json ?>')" class="btn btn-info"><i
                    class="fas fa-save mr-2"></i> Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">



        <form id="myForm">
            <div class="row">

                <div class="col-md-6">
                    <div class="card card-teal">
                        <div class="card-header">
                            <h3 class="card-title">Genel Bilgiler</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="company_name">Şirket Adı <span style="color:red">(*)</span></label>
                                <input id="company_name" name="company_name" value="<?php echo $result->company_name; ?>"
                                    type="text" class="form-control" required>
                            </div>


                            <div class="form-group">
                                <label for="company_official">Yetkili Adı</label>
                                <input id="company_official" name="company_official"
                                    value="<?php echo $result->company_official; ?>" type="text" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="tax_number">Vergi No</label>
                                <input id="tax_number" name="tax_number" type="text"
                                    value="<?php echo $result->tax_number; ?>" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="address">Adresi</label>
                                <textarea type="text" id="address" name="address"
                                    class="form-control"><?php echo $result->address; ?></textarea>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.card -->
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Diğer Bilgiler</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="open_date">Açılış Tarihi </span></label>

                                <div class="input-group date" id="startdate" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#startdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" id="open_date" name="open_date"
                                        value="<?php echo formatdDate($result->open_date); ?>"
                                        class="form-control datetimepicker-input" data-target="#startdate"
                                        data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy"
                                        data-mask />

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="close_date">Kapnaış Tarihi </span></label>

                                <div class="input-group date" id="enddate" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#enddate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" id="close_date" name="close_date"
                                        value="<?php echo $result->close_date; ?>"
                                        class="form-control datetimepicker-input" data-target="#enddate"
                                        data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy"
                                        data-mask />

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="description">Açıklama</label>
                                <textarea type="text" name="description"
                                    class="form-control"><?php echo $result->description; ?></textarea>
                            </div>



                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>

            </div>

    </div>

    </form>
</div>


<script>

    $('.select2').select2()
    $("#liste").click(function () {
        RoutePagewithParams("company/main")
        $("#page-title").text("Şirket Listesi");
    })
    $("#page-title").text("Şirket Güncelle");
    $('[data-mask]').inputmask('dd.mm.yyyy')
    $('#startdate,#enddate').datetimepicker({
        format: 'DD.MM.YYYY',
        locale: 'tr'

    });
    $("#returnlist").click(function () {
        var pageTitle = $("#returnlist").data("title");
        $("#liste").tab("show");
        $("#page-title").text(pageTitle);

    })

</script>