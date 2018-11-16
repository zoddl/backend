<?php
require_once '../includes/config.php';
require_once('ses.php');
$ses = new SimpleEmailService('AKIAJHTMDFC3KOHA5G2Q', 'q75BM81u6tpS2fShGH6Crv47gYIlEohoV9Ew32Bj');
$m = new SimpleEmailServiceMessage();
$m->addTo('shachishsneh@gmail.com');
$m->setFrom('admin@monarchcruise.com');
$m->setSubject('Hello, world!');
$template = 
                            '<div style="width:100%;">
                                <table cellpadding="0" cellspacing="0" border="0" style="width:	600px; text-align:center; background-color:#fbfbfb; margin-left:auto; margin-right:auto;">
                                    <tr style="width:100%;">
                                            <td style="border-top:4px #e9b255 solid;"><img src="'.PROTOCOL.URL.'/hdr.jpg"/></td>
                                    </tr>
                                    <tr style="width:100%;">
                                            <td style="padding-top:30px; padding-bottom:20px; font-family:Segoe, \'Segoe UI\', \'DejaVu Sans\', \'Trebuchet MS\', Verdana, sans-serif; font-size:18px; font-weight:300;">A very special welcome to you on Monarch Cruise,<br> To Log on to the site, use the following credentials: <br><br>
                                            </td>
                                    </tr>
                                    <tr style="width:100%;">
                                            <td style="padding-top:0px; padding-bottom:0px; font-family:Segoe, \'Segoe UI\', \'DejaVu Sans\', \'Trebuchet MS\', Verdana, sans-serif; font-size:16px; font-weight:300;"><strong>EMAIL ID:'." ".$result['email'].'</strong></td>
                                    </tr>
                                    <tr>&nbsp;</tr>
                                    <tr style="width:100%;">
                                            <td style="padding-top:0px; padding-bottom:30px; font-family:Segoe, \'Segoe UI\', \'DejaVu Sans\', \'Trebuchet MS\', Verdana, sans-serif; font-size:16px; font-weight:300;"><strong>PASSWORD:'." ".$result['password'].'</strong></td>
                                    </tr>
                                    <tr>&nbsp;</tr>
                                    <tr style="width:100%;">
                                            <td style="padding-top:0px; padding-bottom:30px; font-family:Segoe, \'Segoe UI\', \'DejaVu Sans\', \'Trebuchet MS\', Verdana, sans-serif; font-size:16px; font-weight:300;"><a href="'.PROTOCOL.URL.'/invitation.php">Click here to Login</a></td>
                                    </tr>
                                    <tr>&nbsp;</tr>
                                    <tr style="width:100%;">
                                            <td style="background-color:#efefef; padding-top:30px; padding-bottom:30px; border-bottom:4px #e9b255 solid; font-family:Segoe, \'Segoe UI\', \'DejaVu Sans\', \'Trebuchet MS\', Verdana, sans-serif; font-size:14px; font-weight:300; color:#868686;">'.PROTOCOL.URL.'</td>
                                    </tr>
                                </table>';
$m->setMessageFromString($template);

print_r($ses->sendEmail($m));

?>