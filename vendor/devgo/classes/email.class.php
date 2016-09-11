<?php

namespace Devgo;

use PHPMailer;

class Email
{
    public function sendEmail($fromName, $fromEmail, $toName, $toEmail, $subject, $message)
    {
        $mail = new PHPMailer();
        $mail->CharSet = 'UTF-8';
        $mail->IsSMTP();
        $mail->SMTPAuth = EMAIL_SMTP_AUTH;
        if (defined(EMAIL_SMTP_SECURE)) {
            $mail->SMTPSecure = EMAIL_SMTP_SECURE;
        }
        $mail->Host = EMAIL_HOST;
        $mail->Port = EMAIL_PORT;
        $mail->Username = EMAIL_USERNAME;
        $mail->Password = EMAIL_PASSWORD;
        $mail->IsHTML(true);

        $mail->From = $fromEmail;
        $mail->FromName = $fromName;
        $mail->AddAddress($toEmail);
        $mail->Subject = $subject;
        $mail->Body = $message;

        if ($mail->Send()) {
            return true;
        } else {
            $erro = $mail->ErrorInfo;

            return $erro;
        }
    }
}
