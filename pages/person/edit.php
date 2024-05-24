<?php
require_once "../../config/functions.php";

$func = new Functions();
$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

$name = "";
$description = "";
$eventdate = "";

if ($_POST ) {
    $FirmaSahisAdi = @$_POST["FirmaSahisAdi"];
    $Mekanid = @$_POST["mekanadi"];
    $Salonid = @$_POST["salonadi"];
    $eventdate = @$_POST["eventdate"];
    $TeklifVeren = @$_POST["TeklifVeren"];
    $TeklifTarihi = date("Y-m-d");
    $Durumu = @$_POST["Durumu"];
    $PersonelID = 1;
    $FirmaSahisTel = @$_POST["FirmaSahisTel"];
    $MusteriEmail = @$_POST["MusteriEmail"];
    $Turu = @$_POST["Turu"];
    $KisiSayisi = @$_POST["KisiSayisi"];
    $description = @$_POST["description"];

       //Veritabanına güncelleme işlemini gerçekleştir
    if ($FirmaSahisAdi != Null) {
        $insq = $con->prepare("UPDATE teklifler SET  FirmaSahisAdi = ? , 
                                                      Mekanid = ? , 
                                                      Salonid = ? , 
                                                      eventdate = ? , 
                                                      TeklifVeren = ? , 
                                                      TeklifTarihi = ? , 
                                                      Durumu = ? , 
                                                      PersonelID = ? , 
                                                      FirmaSahisTel = ? , 
                                                      MusteriEmail = ? , 
                                                      Turu = ? , 
                                                      KisiSayisi = ? , 
                                                      description = ? 
                                                      WHERE id = ?");
        $insq->execute(array($FirmaSahisAdi, $Mekanid, $Salonid, $eventdate, $TeklifVeren, $TeklifTarihi, $Durumu, $PersonelID, $FirmaSahisTel, $MusteriEmail, $Turu, $KisiSayisi, $description,$id ));
    }

}
;
try {
    // seçme sorgusunu hazırla 
    global $kayit;
    $sorgu = "Select * from teklifler where id = ?";
    $stmt = $con->prepare($sorgu);
    $stmt->execute(array($id));
    $kayit = $stmt->fetch(PDO::FETCH_ASSOC);
    //data adında bir fonksiyon oluşturuldu (htmlspecialchars($kayit['name'], ENT_QUOTES);) bu şekilde yazmak yerine
    $eventdate = $func->data('eventdate');
    $FirmaSahisAdi = $func->data('FirmaSahisAdi');
    $Mekanid = $func->data('Mekanid');
    $Salonid = $func->data('Salonid');
    $FirmaSahisTel = $func->data('FirmaSahisTel');
    $MusteriEmail = $func->data('MusteriEmail');



} catch (PDOException $exception) {
    die('HATA: ' . $exception->getMessage());
}

?>

<form id="myForm">
    <div class="row">

        <div class="col-md-6">
            <div class="card card-teal">
                <div class="card-header">
                    <h3 class="card-title">Organizasyon Bilgileri</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">

                    <div class="form-group">
                        <label for="eventdate">Organizasyon Tarihi <font color="red">*</font>
                        </label>
                        <div class="input-group date" id="eventdate" data-target-input="nearest">
                            <input type="text" required name="eventdate" value="<?php echo $eventdate; ?>"
                                class="form-control datetimepicker-input" data-target="#eventdate"
                                placeholder="Organizasyon Tarihi" />
                            <div class="input-group-append" data-target="#eventdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                            <script type="text/javascript">
                                $(function () {
                                    $('#eventdate').datetimepicker({
                                        format: 'DD.MM.YYYY'
                                    });
                                });
                            </script>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="mekanadi">Organizasyon Mekanı<font color="red">*</font></label>
                       <?php $func->select_mekan($id) ;?>
                    </div>
                    <div class="form-group">
                        <label for="salonadi">Salon Adı<font color="red">*</font></label>
                            <?php $func->select_salon($Salonid) ;?>
                       
                    </div>
                    <div class="form-group">
                        <label for="Turu">Organizasyon Türü <font color="red">*</font></label>
                        <?php $func->organizasyon_turu($func->data("Turu")) ;?>
                    </div>
                    <div class="form-group">
                        <label for="KisiSayisi">Kişi Sayısı</label>
                        <input id="KisiSayisi" name="KisiSayisi" type="text" 
                        value="<?php echo $func->data("KisiSayisi") ;?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="description">Açıklama</label>
                        <textarea id="description" name="description" class="form-control" rows="3"
                            placeholder="Açıklama ..."><?php echo $func->data("description") ;?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputFile">Dosya </label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" data-browse="Gözat" id="exampleInputFile">
                                <label class="custom-file-label" for="exampleInputFile">Dosya Seçiniz</label>
                            </div>

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
                    <h3 class="card-title">Firma/Müşteri Bilgileri</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="FirmaSahisAdi">Firma/Müşteri Adı</label>
                        <input type="text" id="FirmaSahisAdi" name="FirmaSahisAdi" 
                        value="<?php echo $FirmaSahisAdi;?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="FirmaSahisTel">Telefon Numarası </label>
                        <input type="phone" id="FirmaSahisTel" name="FirmaSahisTel" 
                       value="<?php echo $FirmaSahisTel ?>" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="MusteriEmail">Mail Adresi</label>
                        <input type="email" id="MusteriEmail" name="MusteriEmail" 
                        value="<?php echo $MusteriEmail ?>" class="form-control">
                    </div>
                </div>
                <!-- /.card-body -->
            </div>

            <div class="card">
                <div class="card-header bg-gray">
                    <h3 class="card-title">Teklif Bilgileri</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="Turu">Teklif Durumu<font color="red">*</font></label>
                        <?php $func->teklifDurum($func->data("Durumu")) ;?>
                    </div>
                    <div class="form-group">
                        <label for="TeklifVeren">Teklifi Hazırlayan</label>
                        <input type="text" id="TeklifVeren" name="TeklifVeren" disabled readonly
                         value="<?php echo $func->user_info($func->data('TeklifVeren'),'fullname') ;?>" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="offeremail">Mail Adresi</label>
                        <input type="email" id="offeremail" disabled readonly
                        value="<?php echo $func->user_info($func->data('TeklifVeren'),'email') ;?>" 
                        class="form-control">
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- row -->


    <button class="btn btn-secondary" onclick="RoutePage('offers/main',this)" data-title="Teklif Listesi" type="button">Listeye Dön</button>


    <?php
    $params = array(
        "id" => $id,
        "method" => "edit");
    $params_json = $func->jsonEncode($params);
    ?>

    <button type="button" id="" data-title="Yeni Teklif"
        onclick="submitFormbyAjax('offers/edit','<?php echo $params_json ?>')"
        class="btn btn-primary float-right">Kaydet</button>
</form>



<script>
    $(function () {
        bsCustomFileInput.init();
    });
</script>

<script type="text/javascript">
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()

        //Initialize Select2 Elements
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })

    });

    $(document).ready(function () {
        $("#mekanadi").change(function () {
            var mekanid = $(this).val();
            $.ajax({
                type: "POST",
                url: "pages/salonlar.php",
                data: { "mekanid": mekanid },
                success: function (e) {
                    $("#salonadi").text('');
                    $("#salonadi").val('');
                    $("#salonadi").html(e)
                }

            })
        })
    });

    $("#returnlist").click(function () {
        $("#liste").tab("show");
        $("#page-title").text("Teklif Listesi");
    })

</script>