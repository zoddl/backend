<?php
function Send_Mail($to, $subject, $body, $bcc = array(), $cc = array())
{
if (ENVIRONMENT == 'local') {
    return true;
}
//require 'class.phpmailer.php';
require_once(dirname(__FILE__) . '/class.phpmailer.php');
$from = "admin@zoddl.com";
$mail = new PHPMailer();
$mail->IsSMTP(true); // SMTP
$mail->SMTPAuth   = true;  // SMTP authentication
$mail->Mailer = "smtp";
$mail->Host       = "tls://email-smtp.us-east-1.amazonaws.com"; // Amazon SES server, note "tls://" protocol
$mail->Port       = 465;                    // set the SMTP port
$mail->Username   = "AKIAJQQEKF65H44EG2VQ";  // SES SMTP  username
$mail->Password   = "Aj/+IJPPhhk9vmSMqptH8hVNkC862m5tfViZN8smdUN7";  // SES SMTP password
$mail->SetFrom($from, 'Zoddl');
$mail->AddReplyTo($from,'admin@zoddl.com');
$mail->Subject = $subject;
$mail->MsgHTML($body);
//$address = $to;
//$mail->AddAddress($address, $to);
if (count($to) && is_array($to)) {
    foreach($to as $mail_id) {
        $mail->AddAddress($mail_id, $mail_id);
    }
}
elseif (count($to)) {
    $mail->AddAddress($to, $to);
}
if (count($bcc)) {
    foreach($bcc as $mail_id) {
        $mail->AddBCC($mail_id, $mail_id);
    }
}
if (count($cc)) {
    foreach($cc as $mail_id) {
        $mail->AddCC($mail_id, $mail_id);
    }
}
if(!$mail->Send())
    return false;
else
    return true;
}
?>