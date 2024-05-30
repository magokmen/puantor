<?php require_once "config/connect.php";
require_once "config/functions.php";
require_once "include/send-mail.php";
// use SendMail\SendMail;
$func = new Functions();





?>

<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Şifremi Unuttum</title>

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
  <link rel="apple-touch-icon" sizes="180x180" href="src/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="src/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="src/img/favicon-16x16.png">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="index.php"><b>Puantor</b> | Puantaj Takip</a>
    </div>
    
    
    <?php
    $email = "";
    if ($_POST && isset($_POST["send"]) ) {

      $email = $func->security($_POST["email"]);
      if ($email == null) {
        echo showAlert("Mail adresi boş bırakılamaz!");

      } else {

        try {
          if (emailVarmi($email, "users")) {
            // Güçlü ve rastgele bir token oluştur
            $token = bin2hex(random_bytes(32));
            $expires = date("U") + 3600; // Token 1 saat geçerli
    
            // Tokeni veritabanında sakla
            $stmt = $con->prepare("INSERT INTO password_reset (email, token, expires) VALUES (:email, :token, :expires)");
            $stmt->execute(['email' => $email, 'token' => password_hash($token, PASSWORD_DEFAULT), 'expires' => $expires]);

            // Kullanıcıya şifre sıfırlama bağlantısı gönder
            $resetLink = "https://puantor.mbeyazilim.com/recover-password.php?token=" . $token . "&email=" . urlencode($email);
            $subject = "Şifre Sıfırlama Talebi";
            $message = "Şifrenizi sıfırlamak için aşağıdaki bağlantıya tıklayın" . "</br><a href=".$resetLink.">".$resetLink."</a>";

            // // Email gönderme fonksiyonu
            $mailer = new SendMail();

            if($mailer->send($email,$message , "Şifre Sıfırlama")){
              echo showAlert("Şifre sıfırlama maili başarıyla gönderildi.", "info");
              exit();
            };

            //header("location: recover-password.php");
          } else {
            echo showAlert("Mail adresi geçerli değil");
          }
        } catch (PDOException $ex) {
          echo showAlert("Error: " . $ex->getMessage());
        }

      }
    }

    ?>
    <script>
      $(document).ready(function () {
        // show the alert
        setTimeout(function () {
          $(".alert-danger").fadeOut("slow");
        }, 5000);
      });
    </script>

    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Şifrenizi mi unuttunuz? Endişelenmeyin! Kısa sürede şifrenizi sıfırlayacağız</p>

        <form action="" method="post">
          
          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" value="<?php echo $email; ?>"
              placeholder="Kayıtlı email adresinizi giriniz!">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" name="send" class="btn btn-primary btn-block">Gönder</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mt-3 mb-1">
          <a href="login.php">Giriş Yap</a>
        </p>
        <p class="mb-0">
          <a href="register.php" class="text-center">Yeni Hesap Oluştur</a>
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