<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include "../config/connect.php";


require "../config/functions.php";

// $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: Kayıt bulunamadı.');
// aktif kayıt bilgilerini oku 
$name = "";
$description = "";
if ($_POST) {
    // Verileri al
    $name = $_POST['MekanAdi'];
    $description = $_POST['description'];

    // Veritabanına güncelleme işlemini gerçekleştir
    if ($name != Null) {
        $up = $con->prepare("INSERT INTO mekanlar SET MekanAdi = ? , description = ? ");
        $result = $up->execute(array($name, $description));

    }
}
;


?>

<div class="card card-outline card-info">
    <div class="card-body">


        <form id="myForm">
            <div class="row">

                <div class="col-md-6 col-sm-12">
                    <label for="MekanAdi">Firma Adı <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="MekanAdi" name="MekanAdi"
                        value="<?php echo $name; ?>" placeholder="Mekan adını yazınız">
                </div>
                <div class="col-md-6 col-sm-12">
                    <label for="MekanAdi">Firma Adı <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="MekanAdi" name="MekanAdi"
                        value="<?php echo $name; ?>" placeholder="Mekan adını yazınız">
                </div>

            </div>
            <br>
            <div class="row">

                <div class="col-md-12 col-sm-12">
                    <label for="description">Açıklama <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="description" name="description"
                        value="<?php echo $description; ?>" placeholder="Açıklama ">
                </div>
            </div>
            <br>

            <button type="button" id="" onclick="submitFormbyAjax('settings','')"
                class="btn btn-primary">Kaydet</button>
        </form>

        <br>
        <br>
    </div>

</div>