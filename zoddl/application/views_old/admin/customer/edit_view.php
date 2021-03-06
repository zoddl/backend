             <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Edit User Detail
                            
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
                                    <span>Edit User Detail</span>
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
                                                    <form class="form-horizontal" method="post" action="<?php echo base_url('customer/edit_process/'.$usertypeid.'/'.$customerid);?>" enctype="multipart/form-data" id="form_sample_1">
							 <?php if($this->session->flashdata('message')){?>
							<div class="form-group">
								<p class='flashMsg forgetSuccess' style="color:#F00; margin-left:26%">
                                <?php echo $this->session->flashdata('message'); ?></p>
                            </div>
								 <?php }?>
                                                        <div class="form-body">
                                                            
	                                                           
                                                            
                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">First Name  </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" data-required="1" name="First_Name" class="form-control" value="<?php echo $customer->First_Name; ?>" placeholder="First Name">
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Last Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" data-required="1" name="Last_Name" class="form-control" value="<?php echo $customer->Last_Name; ?>" placeholder="Last Name">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Gender  </label>
                                                                  <div class="input-icon col-md-4">
                                                                  
                                                                   <input type="radio"  name="Gender" value="Male"  <?php if ($customer->Gender == 'Male') { echo "checked"; } ?> >
                                                                   <label for="radio1">Male</label>

                                                                   <input type="radio"  name="Gender" value="Female" <?php if ($customer->Gender == 'Female') { echo "checked"; } ?>>
                                                                   <label for="radio1">Female</label>

                                                                    
                                                                  </div>
                                                            </div> 

                                                            

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Email  </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Email_Id" class="form-control" value="<?php echo $customer->Email_Id; ?>" placeholder="Email" readonly>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">DOB </label>
                                                                <div class="col-md-4">
                                                                    <div class="input-group date date-picker1" data-date-format="yyyy-mm-dd">
                                                                        <input type="text" class="form-control" readonly="" name="Dob" aria-required="true" aria-invalid="false" aria-describedby="datepicker-error" value="<?php echo $customer->Dob; ?>">
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
                                                                <label class="col-md-3 control-label">Company Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Company_Name" class="form-control" value="<?php echo $customer->Company_Name; ?>" placeholder="Company Name">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Mobile </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Phone_No" class="form-control" value="<?php echo $customer->Phone_No; ?>" placeholder="Mobile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Alt Mobile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Alt_Phone_No" class="form-control" value="<?php echo $customer->Alt_Phone_No; ?>" placeholder="Alt Mobile">
                                                                </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">GSTN </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Gstn" class="form-control" value="<?php echo $customer->Gstn; ?>" placeholder="GSTN">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Aadhar Number </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Aadhar_No" class="form-control" value="<?php echo $customer->Aadhar_No; ?>" placeholder="Aadhar Number">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Pan Number </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Pan_Number" class="form-control" value="<?php echo $customer->Pan_Number; ?>" placeholder="Pan Number">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Skype ID  </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Skype_Id" class="form-control" value="<?php echo $customer->Skype_Id; ?>" placeholder="Skype ID">
                                                                </div>
                                                            </div>

                                                              <div class="form-group">
                                                                <label class="col-md-3 control-label">Facebook Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Facebook_Profile" class="form-control" value="<?php echo $customer->Facebook_Profile; ?>" placeholder="Facebook Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Linked in Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Linked_In_Profile" class="form-control" value="<?php echo $customer->Linked_In_Profile; ?>" placeholder="Linked in Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Twitter Handle</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="Twitter_Handle" class="form-control" value="<?php echo $customer->Twitter_Handle; ?>" placeholder="Twitter Handle">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">User Paid Status</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="Paid_Status">
                                                                        <option value="1" <?php if($customer->Paid_Status == 1) echo "selected"; ?> >Paid</option>
                                                                        <option value="0" <?php if($customer->Paid_Status == 0) echo "selected"; ?> >UnPaid</option>
                                                                        
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