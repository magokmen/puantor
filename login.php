<?php
ob_start();
session_start();
require_once "config/connect.php";
require_once "config/functions.php";

// Eğer aktif bir oturum yok ise.
if (!isset($_SESSION['Oturum']) || $_SESSION['Oturum'] != 'active') {

    // Eğer beni hatırla çerezi var ise.
    if (isset($_COOKIE['RMB']) and $_COOKIE['RMB'] != 'false') {

        $CookieToken = $_COOKIE['RMB']; // Çerez kodu.
        $Browser     = md5($_SERVER['HTTP_USER_AGENT']); // Tarayıcı bilgisi.
        $time        = time(); // Unix zaman.

        $query = $con->query("SELECT * FROM remember_me WHERE remember_token = '{$CookieToken}' and user_browser = '$Browser' and expired_time > $time ")->fetch(PDO::FETCH_ASSOC);

        // Eğer çerez kodu geçerli ise ve max oturum süresi 7 gün aşılmamış ise.
        if ($query) {

            $CookieUser = $query['user_id']; // Çereze ait kullanıcı id'si.

            // Çerezdeki kullanıcı id'sini veri tabanımızda sorguluyoruz.
            $CheckUser = $con->query("SELECT * FROM users WHERE id = '{$CookieUser}' ")->fetch(PDO::FETCH_ASSOC);

            if ($CheckUser) {

                // Kullanıcı geçerli. $CheckUser kullanıcısı için oturum başlatılabilir.

                // Burada standart login sayfanızda hangi işlemleri yapıyorsanız onları yapın ve oturumu başlatın. 
                // Bu şekilde kullanıcının oturumu otomatik olarak başlamış olacak.

                $_SESSION['Oturum'] = 'active';
                $_SESSION['UserID'] = $CheckUser['id'];
            } else {

                // Çerez geçersiz, çerezi sıfırla ve giriş sayfasına yönlendir.
                setcookie("RMB", 'false', time() - 3600, '/');
                header("Location:/Login");
                exit;
            }
        } else {

            // Çerez geçersiz, çerezi sıfırla ve giriş sayfasına yönlendir.
            setcookie("RMB", 'false', time() - 3600, '/');
            header("Location:/Login");
            exit;
        }
    }
}

















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
    <link rel="icon" type="image/png" sizes="32x32" href="src/img/favicon-32x32.png">
</head>

<body class="hold-transition login-page">



    <div class="login-box">
        <div class="login-logo">
            <a href="#"><b>Puantor | </b>Puantaj Takip</a>
        </div>
        <?php

        $email = "";
        $message = "";
        // Kullanıcı adı ve parola boş değilse

        if ($_POST) {

            $email = $_POST["email"];
            $password = $_POST["password"];

            if ($email == null) {
                showAlert("Mail adresini boş bırakmayınız!!!");
            } else if ($password == null) {
                showAlert("Şifreyi boş bırakmayınız!!!");
            } else {




                $query = $con->prepare("SELECT * FROM users WHERE email = ?");
                $query->execute([$email]);
                $account = $query->fetch(PDO::FETCH_ASSOC);
                if ($account) {

                    $sql = $con->prepare("SELECT * FROM accounts WHERE id = ?");
                    $sql->execute(array($account["account_id"]));
                    $result = $sql->fetch(PDO::FETCH_OBJ);


                    $tarih1 = new DateTime($result->created_at);
                    $tarih2 = new DateTime("now");

                    // Farkı hesapla
                    $fark = $tarih1->diff($tarih2);

                    // Farkı gün olarak al
                    $farkGun = 14 - $fark->days;
                    $user_state =  $result->state == 1 ? 1 : 0;

              
                    if ($user_state == 0 && $farkGun < 1) {
                        echo '<div class="alert alert-warning" role="alert">Deneme süreniz dolmuştur. Lütfen satın alın</div>';
                    } else if ($account && password_verify($password, $account["password"])) {

                        if (isset($_POST['remember-me'])) {

                            $UserID = $account['id']; // Kullanıcının id'si.
                            $delete = $con->exec("DELETE FROM remember_me WHERE user_id = '$UserID' "); // Önceki anahtarları siliyoruz.

                            $NewToken = bin2hex(openssl_random_pseudo_bytes(32)); // Rastgele kod üretiyoruz.

                            // Ürettiğimiz kodu kullanıcı id'si ve tarayıcı bilgisi ile birlikte veritabanımıza kaydediyoruz.
                            $Insert2 = $con->prepare("INSERT INTO remember_me SET
                                user_id = :bir,
                                remember_token = :iki,
                                expired_time = :uc,
                                user_browser = :dort");
                            $insert = $Insert2->execute(array(
                                "bir" => $UserID,
                                "iki" => $NewToken,
                                "uc" => time() + 604800,
                                'dort' => md5($_SERVER['HTTP_USER_AGENT'])

                            ));

                            // Kullanıcının tarayıcısına bu kodu çerez olarak kaydediyoruz.
                            setcookie("RMB", $NewToken, time() + 604801, '/');
                        }




                        session_regenerate_id();
                        $_SESSION['login'] = true;
                        $_SESSION['email'] = $_POST['email'];
                        $_SESSION['LoginIP'] = $_SERVER["REMOTE_ADDR"];
                        $_SESSION['userAgent'] = $_SERVER["HTTP_USER_AGENT"];

                        $_SESSION['companyID'] = $account["company_id"];
                        $_SESSION['UserFullName'] = $account["full_name"];
                        $_SESSION['accountType'] = $account["account_type"];
                        $_SESSION['expired_date'] = $farkGun;
                        $_SESSION['state'] = $user_state;
                        $_SESSION['accountID'] = $account["account_id"];
                        $_SESSION['id'] = $account["id"];
                        $_SESSION["page"] = "pages/index.php";


                        // Giriş başarılı mesajını göster
                        echo '<div class="alert alert-info" role="alert">Giriş başarılı! Ana Sayfaya yönlendiriliyorsunuz.</div>';

                        // Sayfayı yönlendir
                        echo '<meta http-equiv="refresh" content="2;url=index.php">';

                        //exit(); // header işleminden sonra kodun devam etmemesi için exit kullanılır
                    } else {
                        echo '<div class="alert alert-danger" role="alert">Hatalı parola veya şifre!</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Kullanıcı bulunamadı!</div>';
                }
            }
        }
        ?>
        <script>
            $(document).ready(function() {
                // show the alert
                setTimeout(function() {
                    $(".alert-info,.alert-danger").fadeOut("slow");
                }, 3000);
            });
        </script>

        <style>

        </style>
        <div class="card ">

            <div class="card-body login-card-body" style="border-radius: 2rem !important">

                <form action="" method="post">
                    <div class="text-center p-2 mb-3">

                        <img src="src/img/preload.png" alt="" style="width:64px;height:64px">
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" class="form-control" value="<?php echo $email ?>" name="email" placeholder="Email adresini giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
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
                    <div class="row">

                        <!-- /.col -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
                        </div>
                        <!-- /.col -->
                    </div>

                    <div class="row mt-4">

                        <div class="d-flex justify-content-between">
                            <p class="mb-1">
                                <a href="forgot-password.php">Şifremi Unuttum</a>
                            </p>
                            <p class="mb-1">
                                <a href="register.php">Hesap Oluştur</a>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember" name="remember-me">
                                <label for="remember">
                                    Beni Hatırla
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
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

