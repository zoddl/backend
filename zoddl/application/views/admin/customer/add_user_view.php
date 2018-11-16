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
                                                    <form class="form-horizontal" method="post" action="<?php echo base_url('customer/add_user_process/'.$usertypeid);?>" enctype="multipart/form-data" autocomplete="off"  id="form_sample_1">
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
                                                                    <input type="text" data-required="1" name="first_name" class="form-control" value="" placeholder="First Name">
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Last Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" data-required="1" name="last_name" class="form-control" value="" placeholder="Last Name">
                                                                </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">User Type</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="user_type" readonly>

                                                                        <?php if($usertype == 'staffcustomer')
                                                                               {
                                                                            ?>
                                                                       
                                                                        <option value="2">Staff User</option>
                                                                        <?php } else { ?>
                                                                        <option value="3">Company User</option>
                                                                        <?php } ?>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">User Role</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="user_role" readonly>
                                                                        
                                                                        <?php if($usertype == 'staffcustomer')
                                                                               {
                                                                            ?>
                                                                        <option value="2">Staff Role</option>
                                                                        <?php } else { ?>
                                                                        <option value="3">Company Role</option>
                                                                         <?php } ?>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Email <span class="required" aria-required="true"> * </span> </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="email" onblur="checkuser(this.value);" class="form-control" value="" placeholder="email" autocomplete="nope">
                                                                </div>
                                                                <div id="avstatus"> </div>
                                                            </div>

                                                            <div class="form-group">
                                                               <label class="col-md-3 control-label">Password <span class="required" aria-required="true"> * </span></label>
                                                               <div class="col-md-4">
                                                                  <input type="password" name="password" class="form-control" placeholder="password" autocomplete="new-password">
                                                                  <div style="color:red; font-weight:bold;"></div>
                                                               </div>
                                                            </div>
                                                            

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">Mobile </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="phone_number" class="form-control" value="" placeholder="Mobile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Alt Mobile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="alt_phone_number" class="form-control" value="" placeholder="Alt Mobile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Aadhar Number </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="aadhar_no" class="form-control" value="" placeholder="Aadhar Number">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Company Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="company_name" class="form-control" value="" placeholder="Company Name">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">GSTN </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="gstn" class="form-control" value="" placeholder="GSTN">
                                                                </div>
                                                            </div>
                                                            

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Skype ID </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="skype_id" class="form-control" value="" placeholder="Skype ID">
                                                                </div>
                                                            </div>

                                                              <div class="form-group">
                                                                <label class="col-md-3 control-label">Facebook Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="facebook_profile" class="form-control" value="" placeholder="Facebook Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Linked in Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="linked_in_profile" class="form-control" value="" placeholder="Linked in Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Twitter Handle</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="twitter_handle" class="form-control" value="" placeholder="Twitter Handle">
                                                                </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">Country</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="country" id="country" onchange="getcountrybystate(this.value);">
                                                                        <option value="">Select</option>
                                                                        <?php
                                                                            if(!empty($country))
                                                                            {

                                                                                foreach($country as $country)
                                                                                { ?>


                                                                                       <option value="<?php echo $country->id; ?>"><?php echo $country->name; ?></option>

                                                                               <?php }
                                                                            }

                                                                        ?>
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">State</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="state" id="state" onchange="getstatebycity(this.value);">
                                                                        <option value="">Select</option>                                                                       
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">City</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="city" id="city">
                                                                        <option value="">Select</option>                                                                       
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                           
                                                             
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-offset-3 col-md-4">
                                                                    <button type="submit" class="btn green">Save & Send Email</button>
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
                   
                    first_name: {
                        
                        maxlength:50
                    },                    
                    phone_number: {                        
                        minlength:10,
                        maxlength:10,
                        number: true
                    },
                    alt_phone_number: {                        
                        minlength:10,
                        maxlength:10,
                        number: true
                    },                   
                    aadhar_no: {                        
                        minlength:12,
                        maxlength:12,
                        number: true
                    },                    
                    email: {
                        required: !0,
                        email: !0
                    },
                    password: {
                        required: !0,
                        minlength:8
                    },
                    company_name: {                        
                        maxlength:50
                    },
                    gstn: {                                            
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

        <script>

        function getcountrybystate(countryid)
        {
  
                var data = 'countryid='+ countryid; 
                 $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('customer/get_state' ); ?>",
                    data:data,
                    success: function(data)
                    {
                      
                      $('#state').html(data);
                        
                    }

                });
            

        }

        function getstatebycity(stateid)
        {

            var data = 'stateid='+ stateid; 
                 $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('customer/get_city' ); ?>",
                    data:data,
                    success: function(data)
                    {
                      
                      $('#city').html(data);
                        
                    }

                });
        }

        function checkuser(value)
            {
                  
                if(value != '')
                {
                      $.ajax({

                            type: 'POST',
                            url: '<?php echo base_url("customer/is_unique_user"); ?>',
                            data: 'email=' + value + '&part=is_unique_user',
                            dataType: 'html',
                            beforeSend: function () {
                                $('#loading_excel').show();
                            },
                            complete: function () {

                            },
                            success: function (responseData, status, XMLHttpRequest) {

                                $("#loading_excel").hide();

                                if (responseData == 1)
                                {
                                    $('#avstatus').html('<span class="statusdata" style="color:green;"> <i class="fa fa-check" aria-hidden="true"></i>Available </span>');
                                    
                                } else
                                {
                                    $('#avstatus').html('<span class="statusdata" style="color:red;"> <i class="fa fa-times" aria-hidden="true"></i>Not Available </span>');
                                    //$('#').val('');
                                }
                            }
                        });
               }

            }

        </script>