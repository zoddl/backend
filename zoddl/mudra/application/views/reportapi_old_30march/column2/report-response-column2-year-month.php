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

<tr style="font-weight:bold; font-size:16px; background:#1cb8e1; padding:10px; -webkit-print-color-adjust:exact;">
<?php
        foreach($request7 as $antar):
    ?>
        <td>
            <?php 

            if(in_array($antar, array('cash+', 'cash-', 'bank+', 'bank-', 'other')))
            {

                echo "Tag type";
            }
            else
            {
              echo ucfirst($antar);   
            }

            

        ?></td>
    <?php
        endforeach;
    ?>
</tr>

<?php
if(!empty($request4))
{
    foreach($request4 as $request61)
    {


?>
<tr>
<td>
    <?php
        $a = ucfirst($request7[0]);
        $a = str_replace(' ', '_', $a);      

        if(in_array($a, array('Primary_tag', 'Secondary_tag'))) {
            echo $$a;
        }
        else if(in_array($a, array('Cash+', 'Cash-', 'Bank+', 'Bank-', 'Other')))
        {
            echo ucfirst($a);

        }
        else if(in_array($a, array('Amount')))
        {
            if(empty($request61->$a))
            {
                echo $request61;
            }
            else
            {

               echo $request61->$a; 
            }

        }

        else {
            echo ucfirst($request61->$a);
        }
    ?>
</td>
<td>
    <?php
        $a = ucfirst($request7[1]);
        $a = str_replace(' ', '_', $a);                
        if(in_array($a, array('Primary_tag', 'Secondary_tag'))) {
            echo ucfirst($$a);
        }
        else if(in_array($a, array('Cash+', 'Cash-', 'Bank+', 'Bank-', 'Other')))
        {
            echo ucfirst($a);

        }
        else if(in_array($a, array('Amount')))
        {
            if(empty($request61->$a))
            {
                echo $request61;
            }
            else
            {

               echo $request61->$a; 
            }

        }

        else {

            echo ucfirst($request61->$a);
        }
    ?>
</td>

</tr>
<?php
    }
}
?>

</table>

<p><strong>&nbsp;&nbsp;&nbsp;&nbsp; No Of Transactions : </strong> <?php echo $data->No_Of_Entry; ?></p>
<p><strong>&nbsp;&nbsp;&nbsp;&nbsp; Total Amount : </strong> <?php  if($data->Total_Amount == '') { echo "0"; } else { echo $data->Total_Amount; }; ?></p>

</body>
</html>
