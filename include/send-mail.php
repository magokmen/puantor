<?php
namespace SendMail ;
// permcontrol("mailandsmssend");
ini_set('display_errors', 1);
error_reporting(E_ALL);

use PDOException;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'vendor/autoload.php';

//MÜSTERİLER SAYFASINDAN MAİL GÖNDERMEK İÇİN

class SendMail {
    // public $from = set("company_name") ;

    function send($address,$content,$subject = null, $file = null){
        

        try {
            
			if (!empty($address)) {
                
				$mail = new PHPMailer();
				$mail->IsSMTP();
				$mail->SMTPDebug = 2;
				$mail->SMTPAuth = true;
           
				$mail->SMTPSecure   = 'tls'; // Güvenli bağlantı için tls kullanıyoruz
				$mail->Host         = "mt-zelda.guzelhosting.com"; //set("mail_host"); // Mail sunucusunun adresi (IP de olabilir)
				$mail->Port         = "25";
				$mail->IsHTML(true);
   				$mail->Encoding     = 'base64';
				$mail->SetLanguage("tr", "phpmailer/language");
				$mail->Username     = "_mainaccount@mbeyazilim.com" ;//set('mail_username'); // Gönderici adresiniz (e-posta adresiniz)
				$mail->Password     = "kpQ~+9^Gh(8x" ; //set('mail_password'); // Mail adresimizin sifresi
				$mail->setFrom($address, "beyzade83@hotmail.com");

	     		$mail->AddAttachment($file); // Yüklenen dosyayı ekle
				$mail->Subject      = $subject;
				$mail->Body         = $content;
                $mail->CharSet      = "UTF-8";
				if ($mail->Send()) {

					// //Eğer başarılı ise veritabanına kayıt edilir
					// $sql= $con->prepare("INSERT INTO mail_logs SET tomail = ?, from_mail = ?, mail_file = ? , mail_body = ? ,sender = ?");
					// $sql->execute(array($mailto,$mail_from,$dosya_adi,$mailicerik,sesset("id")));
					// header("Location: index.php?p=send-mail&send=true");

				} else {
					

				}
			} else {
				

			}
			
	} catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
		
	}
}


    
}

    