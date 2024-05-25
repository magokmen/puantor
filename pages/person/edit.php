<?php
require_once "../../include/requires.php";

// echo "hesap id : " . $account_id;

$id = isset($_GET["id"]) ? $_GET["id"] : @$_POST["id"];

if ($_POST && $_POST["method"] == "add") {

    $full_name = @$_POST["full_name"];
    $kimlik_no = @$_POST["kimlik_no"];
    $sigorta_no = @$_POST["sigorta_no"];
    $address = @$_POST["address"];
    $gunluk_ucret = @$_POST["daily_wages"];
    $email = @$_POST["email"];
    $iban_number = @$_POST["iban_number"];
    $phone = @$_POST["phone_number"];
    $job = @$_POST["job"];
    $company_id = @$_POST["companies"];
    $project_id = @$_POST["projects"];
    $job_start_date = @$_POST["job_start_date"];
    $job_end_date = @$_POST["job_end_date"];


    // Veritabanına güncelleme işlemini gerçekleştir
    try {

        if ($full_name != Null) {
            $insq = $con->prepare("UPDATE person SET  full_name = ? ,
                                                    kimlik_no = ?, 
                                                    sigorta_no = ?, 
                                                    address = ?,  
                                                    phone = ?,  
                                                    daily_wages = ?, 
                                                    email = ?, 
                                                    iban_number = ?,
                                                    job = ? ,
                                                    company_id = ?,
                                                    project_id = ?,
                                                    job_start_date = ?,    
                                                    job_end_date = ?   
                                                    WHERE id = ?");
            $insq->execute(
                array(
                    $full_name,
                    $kimlik_no,
                    $sigorta_no,
                    $address,
                    $phone,
                    $gunluk_ucret,
                    $email,
                    $iban_number,
                    $job,
                    $company_id,
                    $project_id,
                    $job_start_date,
                    $job_end_date,
                    $id
                )
            );


        }

    } catch (PDOException $ex) {
        showMessage("Bir hata oluştu. Hata mesajı :" . $ex->getMessage());
    }
}


$sql = $con->prepare("SELECT * FROM person WHERE id = ?");
$sql->execute(array($id));
$person = $sql->fetch(PDO::FETCH_ASSOC);


?>
<div class="card card-outline card-info">
    <div class="card-header p-2">

        <div class="d-flex justify-content-between">


            <ul class="nav nav-pills">
                <li class="nav-item"><a class="tabMenu nav-link" id="liste" href="#list" data-title="Personel Listesi"
                        data-toggle="tab">Tüm Personeller</a>
                </li>
           </ul>
            <?php
            $params = array(
                "method" => "add",
                "id" => $id);
            $params_json = $func->jsonEncode($params);
            ?>

            <button type="button" id="save" data-title="Yeni Personel"
                onclick="submitFormbyAjax('person/edit','<?php echo $params_json ?>')"
                class="btn btn-info">Kaydet</button>
        </div>

    </div><!-- /.card-header -->
    <div class="card-body">



        <form id="myForm">
            <div class="row">

                <div class="col-md-6">
                    <div class="card card-teal">
                        <div class="card-header">
                            <h3 class="card-title">Kimlik Bilgileri</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="full_name">Adı Soyadı <span style="color:red">(*)</span></label>
                                <input id="full_name" name="full_name" type="text" class="form-control" required
                                    value="<?php echo $person["full_name"]; ?>">
                            </div>


                            <div class="form-group">
                                <label for="kimlik_no">Tc Kimlik No</label>
                                <input id="kimlik_no" name="kimlik_no" type="text" class="form-control"
                                    value="<?php echo $person["kimlik_no"]; ?>">
                            </div>

                            <div class="form-group">
                                <label for="sigorta_no">Sigorta No</label>
                                <input id="sigorta_no" name="sigorta_no" type="text" class="form-control"
                                    value="<?php echo $person["sigorta_no"]; ?>">
                            </div>

                            <div class="form-group">
                                <label for="address">Adresi</label>
                                <textarea type="text" id="address" name="address"
                                    class="form-control"><?php echo $person["address"]; ?></textarea>
                            </div>

                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Görev Bilgileri</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label for="job_start_date">İşe Başlama Tarihi <span
                                        style="color:red">(*)</span></label>

                                <div class="input-group date" id="startdate" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#startdate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input required type="text" id="job_start_date" name="job_start_date"
                                        class="form-control datetimepicker-input" data-target="#startdate"
                                        data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy"
                                        data-mask value="<?php echo $person["job_start_date"]; ?>" />

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="isten_ayrilma_tarihi">İşten Ayrılma Tarihi</label>

                                <div class="input-group date" id="enddate" data-target-input="nearest">
                                    <div class="input-group-prepend" data-target="#enddate"
                                        data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    <input type="text" id="isten_ayrilma_tarihi" name="isten_ayrilma_tarihi"
                                        class="form-control datetimepicker-input" data-target="#enddate"
                                        data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy"
                                        data-mask value="<?php echo $person["job_end_date"]; ?>" />

                                </div>
                            </div>



                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
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
                                <label for="companies">Firması</label>
                                <?php echo $func->companies("companies", $person["company_id"]) ?>
                            </div>

                            <div class="form-group">
                                <label for="companies">Projesi</label>
                                <?php echo $func->projectsMultiple("projects",$person["company_id"],$person["project_id"]) ;?>
                                <!-- <select name="projects" id="projects" class="select2 form-control">
                                    <option value="<?php echo $person["project_id"]?>"><?php echo $func->getProjectName($person["project_id"]) ;?></option>
                                </select> -->

                                

                            </div>
                            <div class="form-group">
                                <label for="daily_wages">Günlük Ücreti<span style="color:red">(*)</span> </label>
                                <input required type="text" id="daily_wages" name="daily_wages" class="form-control"
                                value="<?php echo $person["daily_wages"]; ?>" >
                            </div>

                            <div class="form-group">
                                <label for="phone_number">Telefon<span style="color:red">(*)</span></label>
                                <input required type="text" id="phone_number" name="phone_number" class="form-control"
                                value="<?php echo $person["phone"]; ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">Email Adresi </label>
                                <input type="email" id="email" name="email" class="form-control"
                                value="<?php echo $person["email"]; ?>">
                            </div>


                            <div class="form-group">
                                <label for="iban_number">İban Numarası </label>
                                <input type="text" id="iban_number" name="iban_number" class="form-control"
                                value="<?php echo $person["iban_number"]; ?>">
                            </div>

                            <div class="form-group">
                                <label for="job">Görevi</label>
                                <select type="text" id="job" name="job" class="form-control select2">
                                    <option value="">Görev Seçiniz</option>
                                    <option selected value="1">Ustabaşı</option>
                                </select>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>
            <!-- row -->



        </form>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

    });

    $("#liste").click(function () {
       RoutePagewithParams("person/main")
        $("#page-title").text("Personel Listesi");
    })

    $('[data-mask]').inputmask('dd.mm.yyyy')
    $('#startdate,#enddate').datetimepicker({
        format: 'DD.MM.YYYY',
        locale: 'tr'

    });

    $("#companies").on("change", function () {
        var company_id = $("#companies option:selected").val();
        $('#projects').html('');
        $.ajax({
            url: "ajax.php",
            type: "POST",
            data: {
                "company_id": company_id,
                "action": "proje"
            },
            success: function (data) {
                $('#projects').html(data);
            }
        })
    })
</script>