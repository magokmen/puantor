<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include_once "../../config/connect.php";
require_once "../../config/functions.php";

$func = new Functions();
//use config\Functions;

$id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];
$fullname = $phone = $phone = $username = $password = "";

if ($_POST) {
    // Verileri al

    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'] ? password_hash($_POST['password'],PASSWORD_BCRYPT) : $password;

    // Veritabanına güncelleme işlemini gerçekleştir
   
    if ($fullname != Null) {
        $up = $con->prepare("UPDATE users SET fullname = ? , 
                                                   phone = ? ,
                                                   email = ? ,
                                                   username = ? ,
                                                   password = ? 
                                                   WHERE id = ? ");
        $result = $up->execute(array($fullname, $phone, $email, $username, $password,$id));

    }
}
;
try {
    // seçme sorgusunu hazırla 
    global $kayit;
    $sorgu = "Select * from users where id = ?";
    $stmt = $con->prepare($sorgu);
    $stmt->execute(array($id));
    $kayit = $stmt->fetch(PDO::FETCH_ASSOC);
    //data adında bir fonksiyon oluşturuldu (htmlspecialchars($kayit['name'], ENT_QUOTES);) bu şekilde yazmak yerine
    $fullname = $func->data('fullname');
    $phone = $func->data('phone');
    $email = $func->data('email');
    $username = $func->data('username');
    $password = $func->data('password');

} catch (PDOException $exception) {
    die('HATA: ' . $exception->getMessage());
}

?>
<div class="card card-outline card-info">
    <div class="card-body ">

        <form id="myForm">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="fullname">Adı Soyadı <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="fullname" name="fullname"
                        value="<?php echo $fullname ?>" placeholder="Adı soyadını yazınız">
                </div>

                <div class="col-md-6 col-sm-12">
                    <label for="username">Kullanıcı Adı <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="username" name="username"
                        value="<?php echo $username ?>" placeholder="Kullanıcı Adı">
                </div>

            </div>
            <br>

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="phone">Telefon Numarası <font color="red">*</font></label>
                    <input type="text" required class="form-control" id="phone" name="phone"
                        value="<?php echo $phone ?>" placeholder="Telefon numarası ">
                </div>
                <div class="col-md-6 col-sm-12">
                    <label for="password">Şifre </label>
                    <input type="password" autocomplete="off" class="form-control" id="password" name="password"
                        value="" placeholder="Şifre">
                </div>

            </div>
            <br>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="email">Email Adresi<font color="red">*</font></label>
                    <input type="text" required class="form-control" id="email" name="email"
                        value="<?php echo $email ?>" placeholder="Email Adresi">
                </div>


            </div>
            <br>


            <button class="btn btn-secondary" type="button" onclick="RoutePage('users/main',this)"
                data-title="Kullanıcı Listesi">Listeye Dön</button>

            <?php
            $params = array(
                "id" => $id,
                "method" => "update",
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
    </div>


</div>
<script>


    function usernamekontrol(params) {
        // e.preventDefault(); // Bu satırı silebilirsiniz, çünkü e parametresine ihtiyaç yok
        //alert(params);
        //kullanıcı kontrolü yapılacak
        submitFormbyAjax('users/edit', params)
    }

</script>