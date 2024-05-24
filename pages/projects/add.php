<?php
require_once "../../config/connect.php";
require_once "../../config/functions.php";

$func = new Functions();


$name = "";
$description = "";
$eventdate = "";




if ($_POST && $_POST["method"] == "add") {

    $full_name = @$_POST["full_name"];
    $kimlik_no = @$_POST["kimlik_no"];
    $sigorta_no = @$_POST["sigorta_no"];
    $address = @$_POST["address"];
    $gunluk_ucret = @$_POST["gunluk_ucret"];
    $email = @$_POST["email"];
    $iban_number = @$_POST["iban_number"];
    $job = @$_POST["job"];
    $company_id = 1;



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
                                                    company_id = ?");
            $insq->execute(array($full_name, $kimlik_no, $sigorta_no, $address, $gunluk_ucret, $email, $iban_number, $job, $company_id));


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
                    <h3 class="card-title">Proje Bilgileri</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="company">Firma <span style="color:red">(*)</span></label>
                        <input id="company" name="company" type="text" class="form-control" required>
                    </div>


                    <div class="form-group">
                        <label for="project_name">Proje Adı</label>
                        <input id="project_name" name="project_name" type="text" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="company_official">Yetkilisi</label>
                        <input id="company_official" name="company_official" type="text" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="sigorta_no">Açılış Bütçesi</label>
                        <input id="sigorta_no" name="sigorta_no" type="text" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="exampleInputFile">Sözleşmesi</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Dosya Seçin</label>
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <div class="card card-danger">
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
                        <label for="project_start_date">Proje Başlama Tarihi <span style="color:red">(*)</span></label>

                        <div class="input-group date" id="startdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#startdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input required type="text" id="project_start_date" name="project_start_date"
                                class="form-control datetimepicker-input" data-target="#startdate"
                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask />

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="project_finish_date">Proje Bitiş Tarihi</label>

                        <div class="input-group date" id="enddate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#enddate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="text" id="project_finish_date" name="project_finish_date"
                                class="form-control datetimepicker-input" data-target="#enddate"
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
                        <label for="project_city">Şehir<span style="color:red">(*)</span> </label>
                        <input required type="text" id="project_city" name="project_city" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="project_town">İlçe<span style="color:red">(*)</span></label>
                        <input required type="text" id="project_town" name="project_town" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="address">Adresi</label>
                        <textarea type="text" id="address" name="address" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Adresi </label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="iban_number">Hesap Numarası </label>
                        <input type="text" id="iban_number" name="iban_number" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="project_notes">Proje Hakkında Not</label>
                        <textarea type="text" id="project_notes" name="project_notes" class="form-control"></textarea>
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
    <!-- row -->


    <button class="btn btn-secondary" id="returnlist" data-title="Proje Listesi" type="button">Listeye Dön</button>


    <?php
    $params = array("method" => "add");
    $params_json = $func->jsonEncode($params);
    ?>

    <button type="button" id="" data-title="Yeni Personel"
        onclick="submitFormbyAjax('person/main','<?php echo $params_json ?>')"
        class="btn btn-primary float-right">Kaydet</button>
</form>


<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

    });

    $("#returnlist").click(function () {
        $("#liste").tab("show");
        $("#page-title").text("Alınan Projeler");
    })

    $('[data-mask]').inputmask('dd.mm.yyyy')
    $('#startdate,#enddate').datetimepicker({
        format: 'DD.MM.YYYY',
        locale: 'tr'

    });
</script>