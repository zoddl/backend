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
      
          <h2 style="font-family:Arial; font-size:14px;"> Contact Us Message: </h2>

        <p style="font-family:Arial; font-size:16px;"><strong>Name : </strong> <?php echo $Customer_Name; ?></p>
        <p style="font-family:Arial; font-size:16px;"><strong>Email : </strong> <?php echo $Email_Id; ?> </p>
        <p style="font-family:Arial; font-size:16px;"><strong>Contact No : </strong> <?php echo $Phone_No; ?> </p>
        <p style="font-family:Arial; font-size:16px;"><strong>Message : </strong> <?php echo $Message; ?> </p>
        
        <p style="font-family:Arial; font-size:14px;">Thank you</p>

      </div>
    </div>  

</body>
</html>