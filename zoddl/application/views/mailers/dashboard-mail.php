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
      <p style="font-family:Arial; font-size:16px;">Hi Admin, </p>
      <div class="text-center">
      
          <h2 style="font-family:Arial; font-size:14px;">Dashboard Report Mail: </h2>


<table style="width:25%;">
  <tr>
    <td><strong>Staff Users</strong></td>
    <td><?php echo number_format($susers); ?></td>
  </tr>
  <tr>
    <td><strong>General Users</strong></td>
     <td><?php echo number_format($gusers); ?></td>
    </tr>
  <tr>
    <td><strong>Company Users</strong></td>
    <td><?php echo number_format($cusers); ?></td>
    </tr>
  <tr>
    <td><strong>Primary Tag</strong></td>
    <td><?php echo number_format($ptag); ?></td>
    </tr>
  <tr>
    <td><strong>Secondary Tag</strong></td>
    <td><?php echo number_format($stag); ?></td>
    </tr>
  <tr>
    <td><strong>Image Data Entry</strong></td>
    <td><?php echo number_format($images); ?></td>
    </tr>
  <tr>
    <td><strong>Document Data Entry</strong></td>
    <td><?php echo number_format($documents); ?></td>
    </tr>
  <tr>
    <td><strong>Pending Image Data Entry</strong></td>
    <td><?php echo number_format($pendingImageDataEntry); ?></td>
    </tr>
  <tr>
    <td><strong>Pending Document Data Entry</strong></td>
    <td><?php echo number_format($pendingDocumentDataEntry); ?></td>
    </tr>
  <tr>
    <td><strong>New Image Data Entry</strong></td>
     <td><?php echo number_format($newImageDataEntry); ?></td>
    </tr>
  <tr>
    <td><strong>New Document Data Entry</strong></td>
    <td><?php echo number_format($newDocumentDataEntry); ?></td>
    </tr>
  <tr>
    <td><strong>New App User</strong></td>
    <td><?php echo number_format($newUser); ?></td>
    </tr>
  <tr>
    <td><strong>Paid User</strong></td>
    <td><?php echo number_format($paidUser); ?></td>
    </tr>
  <tr>
    <td><strong>Free User</strong></td>
    <td><?php echo number_format($freeUser); ?></td>
    </tr>
    
</table>


 <p style="font-family:Arial; font-size:14px;">Thank you</p>
      </div>
    </div>  

</body>
</html>