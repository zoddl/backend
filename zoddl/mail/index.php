<?php
require 'Send_Mail.php';
$to = "shachish@apptology.in";
$subject = "Test Mail Subject";
$template = "This is test message";
Send_Mail($to,$subject,$template);
?>