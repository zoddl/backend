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
	font-size:13px;
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
	font-size:13px;
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
<tr style="font-weight:bold; font-size:16px; background:#1cb8e1; padding:10px; -webkit-print-color-adjust:exact;">


<td><?php echo ucfirst($request1); ?></td>
<td><?php echo ucfirst($request2); ?></td>

</tr>

<?php

if(!empty($request3))
{

    foreach ($request3 as $request3)
    {
?>
<tr>


<td><?php echo ucfirst($request1); ?></td>
<td><?php if(!empty($request3->Year)) { echo $request3->Year; } else { echo $request3->Month; } ?></td>

</tr>

<?php }}?>

</table>

<p><strong>No Of Transection : </strong> <?php echo $data->No_Of_Entry; ?></p>
<p><strong>Total Amount : </strong> <?php  if($data->Total_Amount == '') { echo "0"; } else { echo $data->Total_Amount; }; ?></p>

</body>
</html>
