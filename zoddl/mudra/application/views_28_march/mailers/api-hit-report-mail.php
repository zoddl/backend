<!DOCTYPE html>
<html>
<head>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #dddddd;
}
</style>
</head>
<body>
  <div class="msg-content col-md-12 pull-left">
      <p style="font-family:Arial; font-size:16px;">Hi Admin</p>
      <div class="text-center">
      
          <h2 style="font-family:Arial; font-size:14px;">Api Hit Report is (<?php echo $currntDate; ?>) : </h2>

<?php 
      if(!empty($apidetail))
      {
  ?>
<table>
  <tr>
    <th>Api Name</th>
    <th>Api Count</th>
    <th>Api Date</th>
  </tr>
  <?php 
          foreach($apidetail as $apidetail)
          {
  ?>
  <tr>
    <td><?php echo $apidetail->Api_Name; ?></td>
    <td><?php echo $apidetail->Api_Count; ?></td>
    <td><?php echo $apidetail->Api_Date; ?></td>
  </tr>
  <?php } ?>
  
</table>
<?php 
  } else
  {
    echo '<p style="font-family:Arial; font-size:14px;">No Record Available!.</p>';

  }
?>

 <p style="font-family:Arial; font-size:14px;">Thank you</p>
      </div>
    </div>  

</body>
</html>