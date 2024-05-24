<?php
require_once "../../include/requires.php";

// echo "hesap id : " . $account_id;

if ($_POST && $_POST["method"] == "add") {

    $full_name = @$_POST["full_name"];
    $kimlik_no = @$_POST["kimlik_no"];
    $sigorta_no = @$_POST["sigorta_no"];
    $address = @$_POST["address"];
    $gunluk_ucret = @$_POST["gunluk_ucret"];
    $email = @$_POST["email"];
    $iban_number = @$_POST["iban_number"];
    $job = @$_POST["job"];
    $company_id = @$_POST["companies"];

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
                        <label for="ise_baslama_tarihi">İşe Başlama Tarihi <span style="color:red">(*)</span></label>

                        <div class="input-group date" id="startdate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#startdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input required type="text" id="ise_baslama_tarihi" name="ise_baslama_tarihi"
                                class="form-control datetimepicker-input" data-target="#startdate"
                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd.mm.yyyy" data-mask />

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="isten_ayrilma_tarihi">İşten Ayrılma Tarihi</label>

                        <div class="input-group date" id="enddate" data-target-input="nearest">
                            <div class="input-group-prepend" data-target="#enddate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <input type="text" id="isten_ayrilma_tarihi" name="isten_ayrilma_tarihi"
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
                        <label for="companies">Firması</label>
                        <?php echo $func->companies("companies", $account_id) ?>
                    </div>

                    <div class="form-group">
                        <label for="gunluk_ucret">Günlük Ücreti<span style="color:red">(*)</span> </label>
                        <input required type="text" id="gunluk_ucret" name="gunluk_ucret" class="form-control">
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


    <button class="btn btn-secondary" id="returnlist" data-title="Personel Listesi" type="button">Listeye Dön</button>


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
        $("#page-title").text("Personel Listesi");
    })

    $('[data-mask]').inputmask('dd.mm.yyyy')
    $('#startdate,#enddate').datetimepicker({
        format: 'DD.MM.YYYY',
        locale: 'tr'

    });
</script>