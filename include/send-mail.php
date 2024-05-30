<?php

// permcontrol("mailandsmssend");
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// MÜŞTERİLER SAYFASINDAN MAİL GÖNDERMEK İÇİN

class SendMail
{
    // public $from = set("company_name");
    /**
     * Create a message and send it.
     * Uses the sending method specified by $Mailer.
     *
     * @throws Exception
     *
     * @return bool false on error - See the ErrorInfo property for details of the error
     */
   public function send($address, $content, $subject = null, $file = null)
    {
        global $con;

        try {
            if (!empty($address)) {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->SMTPDebug = 0; // Hata ayıklamayı kapat
                $mail->SMTPAuth = true;
                $mail->SMTPSecure = 'tls'; // Güvenli bağlantı için TLS kullanıyoruz
                $mail->Host = "mt-zelda.guzelhosting.com"; //set("mail_host"); // Mail sunucusunun adresi (IP de olabilir)
                $mail->Port = 587; // TLS için genellikle port 587 kullanılır
                $mail->isHTML(true);
                $mail->Encoding = 'base64';
                $mail->setLanguage("tr", "phpmailer/language/");
                $mail->Username = "puantor@mbeyazilim.com"; //set('mail_username'); // Gönderici adresiniz (e-posta adresiniz)
                $mail->Password = "*vn-hwC}.L1D"; // set('mail_password'); // Mail adresinizin şifresi
                $mail->setFrom("puantor@mbeyazilim.com", "Puantor");
                $mail->addAddress($address); // Gönderilen Alıcı
                if ($file) {
                    $mail->addAttachment($file); // Yüklenen dosyayı ekle
                }
                $mail->Subject = $subject;
                $mail->Body = $content;
                $mail->CharSet = "UTF-8";

                if ($mail->send()) {
                    $sql = $con->prepare("INSERT INTO mail_logs (tomail, from_mail, mail_file, mail_body, sender) VALUES (?, ?, ?, ?, ?)");
                    $sql->execute([$address, "PUANTOR", $file ?? "", $content, 1]);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
}

