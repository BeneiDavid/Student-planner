<?php
    use PHPMailer\PHPMailer\PHPMailer; 
    use PHPMailer\PHPMailer\SMTP; 
    use PHPMailer\PHPMailer\Exception; 

    require 'PHPMailer/src/Exception.php'; 
    require 'PHPMailer/src/PHPMailer.php'; 
    require 'PHPMailer/src/SMTP.php'; 

    require 'emailconfig.php';


    class EmailSender {


        public function Send($email, $subject, $message){
            $mail = new PHPMailer(true);

            $mail->isSMTP();

            $mail->SMTPAuth = true;

            $mail->Host = MAILHOST;

            $mail->Username = USERNAME;

            $mail->Password = PASSWORD;

            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port = 587;

            $mail->setFrom(SEND_FROM, SEND_FROM_NAME);

            $mail->addAddress($email);

            $mail->addReplyTo(REPLY_TO, REPLY_TO_NAME);

            $mail->IsHTML(true);

            $mail->Subject = $subject;

            $mail->Body = $message;

            $mail->AltBody = $message;

            $mail->CharSet = 'UTF-8';

            if(!$mail->send()){
                return 'E-mail küldési hiba: ' . $mail->ErrorInfo;
            }
            else{
                return 'success';
            }
        }
    }
?>