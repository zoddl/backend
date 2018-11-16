<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
   <?php // BEGIN HEAD ?>

    <head>
        <meta charset="utf-8" />
        <title>Zoddl Admin</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="Preview page of Metronic Admin Theme #1 for " name="description" />
        <meta content="" name="author" />
       
        <?php // BEGIN GLOBAL MANDATORY STYLES ?>
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('backend/'); ?>assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('backend/'); ?>assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('backend/'); ?>assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('backend/'); ?>assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
       <?php //END GLOBAL MANDATORY STYLES ?>


       <?php // BEGIN PAGE LEVEL PLUGINS ?>
        <link href="<?php echo base_url('backend/'); ?>assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url('backend/'); ?>assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
      <?php // END PAGE LEVEL PLUGINS ?>

      <?php //BEGIN THEME GLOBAL STYLES ?>
        <link href="<?php echo base_url('backend/'); ?>assets/global/css/components.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="<?php echo base_url('backend/'); ?>assets/global/css/plugins.min.css" rel="stylesheet" type="text/css" />
        <?php // END THEME GLOBAL STYLES ?>

        <?php // BEGIN PAGE LEVEL STYLES ?>
        <link href="<?php echo base_url('backend/'); ?>assets/pages/css/login-4.min.css" rel="stylesheet" type="text/css" />
       	<?php // END PAGE LEVEL STYLES ?>

        <?php // BEGIN THEME LAYOUT STYLES ?>

        <?php // END THEME LAYOUT STYLES ?>

        <link rel="shortcut icon" href="<?php echo base_url('backend/'); ?>assets/pages/img/favicon.ico" /> 
    </head>

   <?php // END HEAD ?>

    <body class=" login">
        <?php // BEGIN LOGO ?>
        <div class="logo">
            <a href="<?php echo base_url(); ?>">
                <img src="<?php echo base_url('backend/'); ?>assets/pages/img/zoddle_logo_login.png" alt="Zoddl" /> </a>
        </div>
        <?php // END LOGO ?>

        <?php  //BEGIN LOGIN ?>

        <div class="content">
            <?php // BEGIN LOGIN FORM  ?>
            <form id="login_form">
                <h3 class="form-title">Login to your account</h3>
                <div id="ajax"><?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');}?></div>
               <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">Email</label>
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control placeholder-no-fix" autocomplete="off" type="text" placeholder="Email" name="email" />
		    </div>
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">Password</label>
                    <div class="input-icon">
                        <i class="fa fa-lock"></i>
                        <input class="form-control placeholder-no-fix" type="password" autocomplete="off" placeholder="Password" name="password" /> </div>
                </div>
                <div class="form-actions">
                   
                    <button type="submit" id="submitloginbutton" class="btn green pull-right"> Login </button>
                </div>
                
                <div class="forget-password">
                    <h4>Forgot your password ?</h4>
                    <p> no worries, click
                        <a href="javascript:;" id="forget-password"> here </a> to reset your password. </p>
                </div>
               
            </form>
            <?php //  END LOGIN FORM ?>
            <?php //  BEGIN FORGOT PASSWORD FORM ?>
            <form class="forget-form">
                <h3>Forget Password ?</h3>
                <p> Enter your e-mail address below to reset your password. </p>
                <p id="sendajax"></p>
                <div class="form-group">
                    <div class="input-icon">
                        <i class="fa fa-envelope"></i>
                        <input class="form-control placeholder-no-fix" type="text" autocomplete="off" placeholder="Email" name="email" /> </div>
                </div>
                <div class="form-actions">
                    <button type="button" id="back-btn" class="btn red btn-outline">Back </button>
                    <button type="submit" id="sendpassword" class="btn green pull-right"> Submit </button>
                </div>
            </form>
            <?php // END FORGOT PASSWORD FORM  ?>
           
        </div>
        <?php // END LOGIN ?>
        <?php // BEGIN COPYRIGHT ?>
        <div class="copyright"> 2017 &copy; Zoddl - Admin. </div>
        <?php // END COPYRIGHT ?>
        <!--[if lt IE 9]>
<script src="<?php echo base_url('backend/'); ?> assets/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url('backend/'); ?> assets/global/plugins/excanvas.min.js"></script> 
<script src="<?php echo base_url('backend/'); ?> assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <?php // BEGIN CORE PLUGINS ?>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
     	<?php // END CORE PLUGINS ?>
        <?php //  BEGIN PAGE LEVEL PLUGINS ?>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        
       
       <script>
            $(document).ready(function(){
                $('#closebutton').click(function(){
                    $('#ajax').hide();
                });	        
	       $("#forget-password").click(function() {
		        $("#login_form").hide(); 
			$(".forget-form").show();
		    });
	       $("#back-btn").click(function() {
		        $("#login_form").show(); 
			$(".forget-form").hide();
		    });
            });
        </script>
	<script>
		$("#login_form").validate({
                errorElement: "span",
                errorClass: "help-block",
                focusInvalid: !1,
                rules: {
                   email: {
                        required: !0,
                        email: !0
                    },
                    password: {
                        required: !0
                    }
                },
                messages: {
                    username: {
                        required: "Email is required."
                    },
                    password: {
                        required: "Password is required."
                    }
                },
                invalidHandler: function(e, r) {                    
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error"), e.remove()
                },
                errorPlacement: function(e, r) {
                    e.insertAfter(r.closest(".input-icon"))
                },
                submitHandler: function(e) {
			$.ajax({
                cache: false,
                type: "POST",
                url: "<?php echo base_url('backend/login_process'); ?>",
                data: $("#login_form").serialize(),
                beforeSend : function(msg){ $("#submitloginbutton").html('<img src="<?php echo base_url('backend/assets/global/img/loading.gif'); ?>" />'); },
                success: function(msg)
                {
                    $('body,html').animate({ scrollTop: 0 }, 200);
                    if(msg.substring(1,7) != 'script')
                    {
                        $("#ajax").html(msg); 
                        $("#submitloginbutton").html('<button type="submit" id="submitloginbutton" class="btn green pull-right"> Login </button>');
                    }
                    else
                    { 
                        $("#ajax").html(msg); 
                    }
                }

               });
                    
                }
            }), $("#login_form input").keypress(function(e) {
                if (13 == e.which) return $("#login_form").validate().form() && $("#login_form").submit(), !1
            });
		</script>
	<script>
		 $(".forget-form").validate({
                errorElement: "span",
                errorClass: "help-block",
                focusInvalid: !1,
                ignore: "",
                rules: {
                    email: {
                        required: !0,
                        email: !0
                    }
                },
                messages: {
                    email: {
                        required: "Email field is required."
                    }
                },
                invalidHandler: function(e, r) {},
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error"), e.remove()
                },
                errorPlacement: function(e, r) {
                    e.insertAfter(r.closest(".input-icon"))
                },
                submitHandler: function(e) {
                    $.ajax({
                        cache: false,
                        type: "POST",
                        url: "<?php echo base_url('backend/forget_process'); ?>",
                        data: $(".forget-form").serialize(),
                        beforeSend : function(msg){ 
                            $("#sendpassword").html('<img src="<?php echo base_url('backend/assets/global/img/loading.gif'); ?>" />');
                        },
                        success: function(msg)
                        {
                            
                            $('body,html').animate({ scrollTop: 0 }, 200);
                            if(msg.substring(1,7) != 'script')
                            {
                                $("#sendajax").html(msg);  
                                $("#sendpassword").html('<button type="submit" id="sendpassword" class="btn green pull-right"> Submit </button>');       
                            }
                            else
                            { 
                                $("#sendajax").html(msg); 
                            }
                        }
                    });
                }
	     }), $(".forget-form input").keypress(function(e) {
		        if (13 == e.which) return $(".forget-form").validate().form() && $(".forget-form").submit(), !1
		    });
	</script>

    </body>

</html>
