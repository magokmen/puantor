<?php

if ($_POST && $_POST["method"] == "add") {

    $full_name = @$_POST["full_name"];
    $kimlik_no = @$_POST["kimlik_no"];
    $sigorta_no = @$_POST["sigorta_no"];
    $address = $_POST["address"];
    $gunluk_ucret = isset($_POST["gunluk_ucret"]) && $_POST["gunluk_ucret"] != "" ? $_POST["gunluk_ucret"] : 0; 
    $email = $_POST["email"];
    $iban_number = $_POST["iban_number"];
    $job = $_POST["job"];
    $company_id = $_POST["companies"];
    $job_start_date = @$_POST["job_start_date"];
    $job_end_date = @$_POST["job_end_date"];

    $projects = "";

    if (isset($_POST["projects"])) {
        foreach ($_POST["projects"] as $project) {
            $projects .= $project . "|";
        }
        $projects = rtrim($projects, "|");
    }


    // Veritabanına güncelleme işlemini gerçekleştir
    try {

        if ($full_name != Null) {
            $insq = $con->prepare("INSERT INTO person SET  full_name = ? ,
                                                    kimlik_no = ?, 
                                                    sigorta_no = ?, 
                                                    address = ?,  
                                                    daily_wages = ?, 
                                                    email = ?, 
                                                    iban_number = ?,
                                                    job = ? ,
                                                    company_id = ?,
                                                    project_id = ?   ,
                                                    job_start_date = ?,    
                                                    job_end_date = ?   ");
            $insq->execute(
                array(
                    $full_name,
                    $kimlik_no,
                    $sigorta_no,
                    $address,
                    $gunluk_ucret,
                    $email,
                    $iban_number,
                    $job,
                    $company_id,
                    $projects,
                    $job_start_date,
                    $job_end_date
                )
            );


        }

    } catch (PDOException $ex) {
        echo $ex->getMessage();
    }
}
;


?>


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
                        <input id="full_name" name="full_name" type="text" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label for="kimlik_no">Tc Kimlik No</label>
                        <input id="kimlik_no" name="kimlik_no" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="sigorta_no">Sigorta No</label>
                        <input id="sigorta_no" name="sigorta_no" type="text" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="address">Adresi</label>
                        <textarea type="text" id="address" name="address" class="form-control"></textarea>
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
                        <label for="job_start_date">İşe Başlama Tarihi <span style="color:red">(*)</span></label>

                        <div class="input-group date" id="startdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#startdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input required type="text" id="job_start_date" name="job_start_date"
                                class="form-control datetimepicker-input" data-target="#startdate"
                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask />

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
                        <?php echo $func->companies("companies", $account_id) ?>
                    </div>

                    <div class="form-group">
                        <label for="companies">Projesi</label>
                        <select class="select2" id="projects" name="projects[]" multiple="multiple"
                            data-placeholder="Proje Seçiniz" data-dropdown-css-class="select2" style="width: 100%;">

                        </select>

                    </div>
                    <div class="form-group">
                        <label for="gunluk_ucret">Günlük Ücreti<span style="color:red">(*)</span><small>İlgili dönemdeki
                                parametreden yüksek olması durumunda buradan hesaplanır.</small> </label>
                        <input type="text" id="gunluk_ucret" name="gunluk_ucret" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="phone_number">Telefon<span style="color:red">(*)</span></label>
                        <input required type="text" id="phone_number" name="phone_number" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Adresi </label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="iban_number">İban Numarası </label>
                        <input type="text" id="iban_number" name="iban_number" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="job">Görevi</label>
                        <select type="text" id="job" name="job" class="form-control select2">
                            <option value="">Görev Seçiniz</option>
                            <option value="1">Ustabaşı</option>
                        </select>
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <!-- row -->




</form>

<script src="../../src/component.js"></script>
