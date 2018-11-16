             <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Add User 
                            
                        </h1>
                        <!-- END PAGE TITLE-->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo base_url(); ?>">Home</a>
                                    <i class="fa fa-angle-right"></i>
                                </li>
                                <li>

                                    <a href="<?php echo base_url('/'.$usertype); ?>"><?php if($usertype == 'staffcustomer'){ echo "Staff User"; } elseif($usertype == 'appcustomer'){ echo "General User"; } else { echo "Company User"; } ?></a>
                                    <i class="fa fa-angle-right"></i>
                                </li>
                                <li>
                                    <span>Add User</span>
                                </li>
                            </ul>
                            
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- END PAGE HEADER-->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="portlet light bordered">
                                               
                                                <div class="portlet-body form">
                                                    <!-- BEGIN FORM-->
                                                    <form class="form-horizontal" method="post" action="<?php echo base_url('customer/add_process/'.$usertypeid);?>" enctype="multipart/form-data" autocomplete="off"  id="form_sample_1">
							 <?php if($this->session->flashdata('message')){?>
							<div class="form-group">
								<p class='flashMsg forgetSuccess' style="color:#F00; margin-left:26%">
                                <?php echo $this->session->flashdata('message'); ?></p>
                            </div>
								 <?php }?>
                                                        <div class="form-body">
                                                                                                                    

                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">First Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" data-required="1" name="First_Name" class="form-control" value="" placeholder="First Name">
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Last Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" data-required="1" name="Last_Name" class="form-control" value="" placeholder="Last Name">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Gender </label>
                                                                  <div class="input-icon col-md-4">
                                                                  
                                                                   <input type="radio"  name="Gender" value="Male">
                                                                   <label for="radio1">Male</label>

                                                                   <input type="radio"  name="Gender" value="Female">
                                                                   <label for="radio1">Female</label>

                                                                    
                                                                  </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="control-label col-md-3">DOB </label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group date date-picker1" data-date-format="yyyy-mm-dd">
                                                                        <input type="text" class="form-control" readonly="" name="Dob" aria-required="true" aria-invalid="false" aria-describedby="datepicker-error" value="">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn default" type="button">
                                                                                <i class="fa fa-calendar"></i>
                                                                            </button>
                                                                        </span>
                                                                    </div><span id="datepicker-error" class="help-block help-block-error"></span>
                                                                    <!-- /input-group -->
                                                                    
                                                                </div>
                                                            </div> 

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Email <span class="required" aria-required="true"> * </span> </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Email_Id" class="form-control" value="" placeholder="Email" autocomplete="nope">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                               <label class="col-md-3 control-label">Password <span class="required" aria-required="true"> * </span></label>
                                                               <div class="col-md-4">
                                                                  <input type="password" name="Password" class="form-control" placeholder="Password" autocomplete="new-password">
                                                                  <div style="color:red; font-weight:bold;"></div>
                                                               </div>
                                                            </div>
                                                            

                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Company Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Company_Name" class="form-control" value="" placeholder="Company Name">
                                                                </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">Mobile </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Phone_No" class="form-control" value="" placeholder="Mobile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Alt Mobile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Alt_Phone_No" class="form-control" value="" placeholder="Alt Mobile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">GSTN </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Gstn" class="form-control" value="" placeholder="GSTN">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Aadhar Number </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Aadhar_No" class="form-control" value="" placeholder="Aadhar Number">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Pan Number </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Pan_Number" class="form-control" value="" placeholder="Pan Number">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Skype ID </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Skype_Id" class="form-control" value="" placeholder="Skype ID">
                                                                </div>
                                                            </div>

                                                              <div class="form-group">
                                                                <label class="col-md-3 control-label">Facebook Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Facebook_Profile" class="form-control" value="" placeholder="Facebook Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Linked in Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Linked_In_Profile" class="form-control" value="" placeholder="Linked in Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Twitter Handle</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Twitter_Handle" class="form-control" value="" placeholder="Twitter Handle">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">User Paid Status</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="Paid_Status">
                                                                        <option value="1">Paid</option>
                                                                        <option value="0">UnPaid</option>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            
                                                           
                                                             
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-offset-3 col-md-4">
                                                                    <button type="submit" class="btn green">Submit</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                    <!-- END FORM-->
                                                </div>
                                            </div>
                            </div>
                           
                        </div>
                        
                            
                        
                        
                       
                                
                              
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
                
                
            <!-- END CONTAINER -->
           
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        
        <?php // END CORE PLUGINS ?>
        <?php //  BEGIN PAGE LEVEL PLUGINS ?>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        
        <script>
        $("#form_sample_1").validate({
                errorElement: "span",
                errorClass: "help-block",
                focusInvalid: false,
                rules: {
                   
                    First_Name: {
                        
                        maxlength:50
                    },                    
                    Phone_No: {                        
                        minlength:10,
                        maxlength:10,
                        number: true
                    },
                    Alt_Phone_No: {                        
                        minlength:10,
                        maxlength:10,
                        number: true
                    },                   
                    Aadhar_No: {                        
                        minlength:12,
                        maxlength:12,
                        number: true
                    },
                    Pan_Number: {                        
                        minlength:10,
                        maxlength:10
                    }, 
                    Email_Id: {
                        required: !0,
                        email: !0
                    },
                    Password: {
                        required: !0,
                        minlength:8
                    },
                    Company_Name: {                        
                        maxlength:50
                    },
                    Gstn: {                                            
                        maxlength:50,
                        number: true
                    } 
                },
                messages: {
                   
                },
                errorPlacement: function (error, element) {            
                    var cont = $(element).parent('.input-group');

                     if (element.attr("type") == "radio") {
                        console.log(element.attr("name"));
                        console.log($(element).parent().parent().parent().attr('class'));
                        $(element).parent().append(error);                
                    }
                   
                    else {
                        element.after(error);
                    }
                },
                highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
            
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                        $(e).remove();
                    },  
            
                    submitHandler: function (form) {
                            form.submit();                      
                    },
                    invalidHandler: function (form) {
                    }
            }), $("#login_form input").keypress(function(e) {
                if (13 == e.which) return $("#form_sample_1").validate().form() && $("#form_sample_1").submit(), !1
            }); 
        </script>