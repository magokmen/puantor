<?php ob_start();
session_start();
require_once "config/connect.php";
require_once "config/functions.php";
$func = new Functions();
<<<<<<< HEAD
=======


>>>>>>> 5eb87c96cfbeb689ce88cb27f4d9adeb75ad055d

?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Puantor | Puantaj Takip Uygulaması</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/png" sizes="32x32" href="src/img/favicon-32x32.png">

</head>

<body class="hold-transition login-page">


    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Puantor | </b>Puantaj Takip</a>
        </div>
        <!-- /.login-logo -->

        <?php
        $company_name = "";
        $full_name = "";
        $email = "";


        if (isset($_POST["kayıt"])) {

            $company_name = $_POST["company_name"];
            $full_name = $_POST["full_name"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $password_repeat = $_POST["password_repeat"];




            if ($company_name == '' || $company_name == null) {
                showAlert("Firma Adı boş bırakılamaz");
            } else if ($full_name == '' || $full_name == null) {
                showAlert("Adı Soyadı boş bırakılamaz");
            } else if ($email == '' || $email == null) {
                showAlert("Email adresi boş bırakılamaz");
            } else if (!$func->preg_match($full_name)) {
                showAlert("Ad soyad yalnızca boşluk ve harf içermelidir");
            } else if ($password == '' || $password == null) {
                showAlert("Şifre bırakılamaz");
            } else if ($password_repeat == '' || $password_repeat == null) {
                showAlert("Şifre Tekrarı boş bırakılamaz");
            } else if ($password != $password_repeat) {
                showAlert("Şifreler eşleşmiyor");
            } else if (emailVarmi($email) || emailVarmi($email, "users")) {
                showAlert("Bu email adresi kayıtlı");

            } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                showAlert("Geçersiz email adresi");
            } else {
                //$password = password_hash($password, PASSWORD_BCRYPT);
                $password_hashed = password_hash($_POST['password'], PASSWORD_BCRYPT);

                try {

                    $con->beginTransaction();
                    $sql = $con->prepare("INSERT INTO accounts SET company_name= ? , full_name= ?, email= ? , password = ? ");
                    $sql->execute(array($company_name, $full_name, $email, $password_hashed));
                    $lastid = $con->lastInsertId();


                    $userquery = $con->prepare("INSERT INTO users SET account_id= ? , full_name= ?, email= ? , password = ? ");
                    $userquery->execute(array($lastid, $full_name, $email, $password_hashed));


                    $compquery = $con->prepare("INSERT INTO companies SET account_id= ? , company_name= ?, email= ? ");
                    $compquery->execute(array($lastid, $company_name, $email));
                    $con->commit();

                    showAlert("Başarı ile kayıt oluşturuldu.Giriş sayfasına yönlendiriliyorsunuz", "success");
                    $company_name = "";
                    $full_name = "";
                    $email = "";
                    go("login.php", 3);
                } catch (PDOException $ex) {
                    echo showAlert("Bir Hata ile karşılaşıldı. Hata :" . $ex->getMessage());
                }

            }
        }


        ?>
        <script>
            $(document).ready(function () {
                // show the alert
                setTimeout(function () {
                    $(".alert").fadeOut("slow");
                }, 3000);
            });
        </script>

        <style>

        </style>
        <div class="card ">

            <div class="card-body login-card-body" style="border-radius: 2rem !important">

                <form id="myForm" action="" method="post">
                    <div class="text-center p-2 mb-3">

                        <img src="src/img/preload.png" alt="" style="width:64px;height:64px">
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="company_name" value="<?php echo $company_name ?>"
                            placeholder="Firma Adını giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-building"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="full_name" value="<?php echo $full_name ?>"
                            placeholder="Ad soyad giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" autocomplete="off" value="<?php echo $email ?>"
                            name="email" placeholder="Email adresini giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password" placeholder="Şifrenizi giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" class="form-control" name="password_repeat"
                            placeholder="Şifrenizi tekrar giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">

                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block" name="kayıt">Kaydol</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                <div class="row">

                    <p class="mb-1">
                        <a href="login.php">Zaten bir hesabım var</a>
                    </p>
                </div>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
</body>

</html>