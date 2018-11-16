<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>pdf</title>
<style>

<style>
@page{margin: 50px;}
body{margin:0; padding:0; font-family:Arial, Helvetica, sans-serif;}
table, td, th {
    border: 1px solid #d2d2d2;
	font-size:26px;
	padding:5px 10px;
}

table {
    border-collapse: collapse;
    width: 100%;}
table tr:first-child td{font-weight:bold; font-size:16px; background:#1cb8e1; padding:10px 2px; -webkit-print-color-adjust:exact;}

    </style>
<style media="screen">
@page { margin: 0px;}
body{margin:0; padding:0; font-family:Arial, Helvetica, sans-serif;}
table, td, th {
    border: 1px solid #d2d2d2;
	font-size:26px;
	padding:5px 10px;
}

table {
    border-collapse: collapse;
    width: 100%;}
table tr:first-child td{font-weight:bold; font-size:16px; background:#1cb8e1; padding:10px 2px; -webkit-print-color-adjust:exact;}

</style>
</head>

<body>

<table cellpadding="0" cellspacing="0" width="100%" style="text-align:center; ">

<?php
if($request == 'amount' || $request == 'cash+' || $request == 'cash-' || $request == 'bank+' || $request == 'bank-' || $request == 'other' || $request == 'count')
{

?>
<tr style="font-weight:bold; font-size:16px; background:#1cb8e1; padding:10px; -webkit-print-color-adjust:exact;">
<td><?php echo ucfirst($request); ?></td>

</tr>

<?php 
    }

    if($request == 'amount')
     {
?>
<tr>
<td><?php  if($data->Total_Amount == ''){ echo "0"; } else { echo $data->Total_Amount; }; ?></td>

</tr>

<?php } 

else if($request == 'count')
{ ?>

<tr>
<td><?php  if($data->No_Of_Entry == ''){ echo "0"; } else { echo $data->No_Of_Entry; }; ?></td>

</tr>

<?php }

?>



</table>

<p><strong>&nbsp;&nbsp;&nbsp;&nbsp; No Of Transaction : </strong> <?php echo $data->No_Of_Entry; ?></p>
<p><strong>&nbsp;&nbsp;&nbsp;&nbsp; Total Amount : </strong> <?php  if($data->Total_Amount == '') { echo "0"; } else { echo $data->Total_Amount; }; ?></p>

</body>
</html>
