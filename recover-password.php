<?php require_once "config/connect.php";
require_once "config/functions.php";
$func = new Functions;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Recover Password</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../../plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="../../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="index.php"><b>Puantor</b>| Puantaj Takip</a>
        </div>
        <?php


        // if ($_POST) {
        //     $password = $func->security($_POST["password"]);
        //     $password_repeat = $func->security($_POST["password_repeat"]);
        //     if ($password == null || $password_repeat == null) {
        //         echo showAlert("Şifreler boş olamaz!");
        //     } else if ($password != $password_repeat) {
        //         echo showAlert("Şifreler uyuşmuyor!");
        //     } elseif ($password == null || $password_repeat == null || $password == '' || $password_repeat == '') {
        //         echo showAlert("Şifreler boş olamaz!");
        //     } else {
        //         try {
        //             header("location:login.php");
        //         } catch (PDOException $ex) {
        //             echo showAlert("Error: " . $ex->getMessage());
        //         }
        //     }
        // }
        
        // ?>
        <?php
        
        if ($_POST) {
            
            $email = $func->security($_GET['email']);
            $token = $func->security($_GET['token']);
            $newPassword = $func->security($_POST['new_password']);
            $confirmPassword = $func->security($_POST['confirm_password']);

            // Şifrelerin eşleştiğini kontrol et
            if ($newPassword !== $confirmPassword) {
                echo showAlert("Şifreler eşleşmiyor.");

            }

            // Tokeni ve süresini kontrol et
            $stmt = $con->prepare("SELECT * FROM password_reset WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $resetRequest = $stmt->fetch();
// echo showAlert(date("U") . "-" . $resetRequest['expires'] , "info");
echo "token :" . $resetRequest['token'] ; 
echo "token : 2 " . $token ;
            if ($resetRequest && password_verify($token, $resetRequest['token']) && date("U") < $resetRequest['expires']) {
                // Şifreyi güncelle
                $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $stmt = $con->prepare("UPDATE users SET password = :password WHERE email = :email");
                $stmt->execute(['password' => $newPasswordHash, 'email' => $email]);

                // Tokeni sil
                $stmt = $con->prepare("DELETE FROM password_reset WHERE email = :email");
                $stmt->execute(['email' => $email]);

                echo showAlert("Şifreniz başarıyla sıfırlandı.", "info");
            } else {
                echo showAlert("Geçersiz veya süresi dolmuş token.");
            }
        } else if ($_GET['token'] && $_GET['email']) {
            $token = $_GET['token'];
            $email = $func->security($_GET['email']);
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



        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Yeni şifrenize yalnızca bir adım kaldı, şifrenizi şimdi değiştirin.</p>

                <form action="" method="post">
                    <input type="text" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    <div class="input-group mb-3">
                        <input type="password" name="new_password" class="form-control"
                            placeholder="Yeni Şifrenizi giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="confirm_password" class="form-control"
                            placeholder="Şifrenizi tekrar giriniz">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Şifremi Değiştir</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <p class="mt-3 mb-1">
                    <a href="login.php">Giriş Yap</a>
                </p>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery -->
    <script src="../../plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../../dist/js/adminlte.min.js"></script>
</body>

</html>