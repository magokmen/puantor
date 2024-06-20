<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
require_once $_SERVER["DOCUMENT_ROOT"] ."/include/requires.php";

// $id = isset($_GET['id']) ? $_GET['id'] : die('HATA: Kayıt bulunamadı.');
// aktif kayıt bilgilerini oku 
$name = "";
$phone = "";
$phone = "";
$username = "";
$password = "";

echo "account_id : " . $account_id;
if ($_POST && $_POST['method'] == "add") {
    // Verileri al
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $userroles = $_POST['userroles'];
    $companies = $_POST['companies'];


    //kullanıcı var mı kontrol et
    $query = "SELECT username FROM users WHERE username = ?";
    $statement = $con->prepare($query);
    $statement->execute(array($username));

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    //mail adresi var mı kontrol edilir
    $mailVarmi = emailVarmi($email, "users");

    if (!$mailVarmi) {
        // Veritabanına ekleme işlemini gerçekleştir
        if ($full_name != Null) {
            $up = $con->prepare("INSERT INTO users SET account_id = ?,
                                                        company_id = ?,
                                                        full_name = ? , 
                                                        phone = ? ,
                                                        email = ? ,
                                                        password = ? ,
                                                        groups = ?,
                                                        username = ? 
                                                        ");
            $result = $up->execute(array($account_id, $companies, $full_name, $phone, $email, $password, $userroles, $username));

        }
    } else {
        showMessage("Bu mail adresi kayıtlı");
    }
}
;


?>
<form id="myForm">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <label for="full_name">Adı Soyadı <font color="red">*</font></label>
            <input type="text" required class="form-control" id="full_name" name="full_name" value=""
                placeholder="Adı soyadını yazınız">
        </div>



        <div class="col-md-6 col-sm-12">
            <label for="username">Kullanıcı Adı <font color="red">*</font></label>
            <input type="text" required class="form-control" id="username" name="username" value=""
                placeholder="Kullanıcı Adı">
        </div>

    </div>
    <br>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <label for="phone">Telefon Numarası <font color="red">*</font></label>
            <input type="text" required class="form-control" id="phone" name="phone" value=""
                placeholder="Telefon numarası ">
        </div>
        <div class="col-md-6 col-sm-12">
            <label for="password">Şifre Adı <font color="red">*</font></label>
            <input type="text" required class="form-control" id="password" name="password" value="" placeholder="Şifre">
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <label for="email">Email Adresi<font color="red">*</font></label>
            <input type="text" required class="form-control" id="email" name="email" value=""
                placeholder="Email Adresi">
        </div>
        <div class="col-md-6 col-sm-12">
            <label for="companies">Firması <font color="red">*</font></label>
            <?php echo $func->companies("companies", $account_id) ?>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <label for="authority">Pozisyonu (Yetki) <font color="red">*</font></label>
            <?php echo $func->authority("userroles", $account_id) ?>
        </div>
    </div>
    <br>

    <button class="btn btn-secondary" id="returnlist" type="button">Listeye Dön</button>

    <?php
    $params = array(
        "method" => "add",
    );
    $params_json = $func->jsonEncode($params);
    ?>
    <!-- <button type="button" id="" onclick="usernamekontrol('<?php echo $params_json; ?>',this)"
        class="btn btn-primary float-right">Kaydet</button> -->
    <button type="button" id="" onclick="usernamekontrol('<?php echo $params_json; ?>')"
        class="btn btn-primary float-right">Kaydet</button>

    <!-- <button type="button" id="" onclick="submitFormbyAjax('users/main','<?php echo $params_json; ?>')"
        class="btn btn-primary float-right">Kaydet</button> -->
</form>

<script>
    $("#returnlist").click(function () {
        $("#liste").tab("show");
        $("#page-title").text("Kullanıcı Listesi");
    })
    $('.select2').select2()

    function usernamekontrol(params) {
        submitFormbyAjax('users/main', params)
    }

</script>