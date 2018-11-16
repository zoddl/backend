<?php 

?>

<!DOCTYPE html>
<html lang="en" >

<head>
  <meta charset="UTF-8">
  <title>Customer Reset Password</title>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>


  
  <link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css'>
<link rel='stylesheet prefetch' href='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css'>
<link rel='stylesheet prefetch' href='http://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css'>
<link rel="shortcut icon" href="<?php echo base_url('backend/assets/pages/img/favicon.ico'); ?> " /> 

      <link rel="stylesheet" href="<?php echo base_url('assets/')?>include/style.css">

      <style>
      legend.logo { padding: 0 0 20px 0; margin:0;}
      </style>

  
</head>
<?php if(!empty($message) && isset($message)){ ?>
                <h4><?php echo $message; ?></h4>
                            
  <?php }
  elseif(!empty($succ) && isset($succ)) 
  { ?>
      <h4><?php echo exit($succ); ?></h4>

 <?php }

  else { ?>

<body>


      <div class="container">

    <form class="well form-horizontal" action="<?php echo base_url('customerverification/resetpassword/'.$cid.'/'.$token);?>" enctype="multipart/form-data" method="post"  id="contact_form">
<fieldset>

<!-- Form Name -->
<legend class="logo"><center><h2><b>

  <img src="<?php echo base_url('backend/assets/pages/img/zoddle_logo_login.png'); ?> " alt="Zoddl">


</b></h2></center></legend>



<!-- Text input-->
<h4 class="text-center"> Reset Password</h4>
<div class="form-group">
  <label class="col-md-4 control-label" >Password</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="password" placeholder="Password" class="form-control"  type="password">
  <div style="color:red; font-weight:bold;"><?php  echo form_error('password'); ?></div>
    </div>
  </div>
</div>

<!-- Text input-->

<div class="form-group">
  <label class="col-md-4 control-label" >Confirm Password</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="cpassword" placeholder="Confirm Password" class="form-control"  type="password">   
   <div style="color:red; font-weight:bold;"><?php  echo form_error('cpassword'); ?></div>
    </div>
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4"><br>
    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp<button type="submit" class="btn btn-primary" >&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspSUBMIT <span class="glyphicon glyphicon-send"></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</button>
  </div>
</div>

</fieldset>
</form>
</div>

    </div><!-- /.container -->
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js'></script>

  

    <script  src="<?php echo base_url('assets/')?>include/reset.js"></script>




</body>

<?php }?>

</html>
