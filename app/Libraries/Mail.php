<?php namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail {

    /**
     * Método para enviar email
     * @param String email destinatario
     * @param String asunto
     * @param String mesaje
     * @return boolean 'true' se envio el mensaje
     */
    public function sendEmail(String $emailAddress, String $subject, String $body)
    {

        $exito = true;
        $mail = new PHPMailer(true);
        
        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;                         // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = 'sis.jasschosica@gmail.com';            // SMTP username
            $mail->Password   = 'vhebfsrqcezsttax';                     // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
            $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            
            //Recipients
            $mail->setFrom('sis.jasschosica@gmail.com', 'J.A.S.S');
            $mail->addAddress($emailAddress);                           // Add a recipient
            
            // Content
            $mail->isHTML(true);                                        // Set email format to HTML
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $subject;
            $mail->Body    = $body;
            
            $mail->send();
        } catch (Exception $e) {
            $exito = false;
        }
        return $exito;
        
    }

}