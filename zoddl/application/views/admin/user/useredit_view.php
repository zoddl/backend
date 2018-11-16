             <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Edit Profile Detail
                            
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
                                    <span>Edit Profile Detail</span>
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
                                                    <form class="form-horizontal" method="post" action="<?php echo base_url('dashboard/profileedit_process');?>" enctype="multipart/form-data" id="form_sample_1">
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
                                                                    <input type="text" data-required="1" name="first_name" class="form-control" value="<?php echo $user->first_name; ?>" placeholder="First Name">
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="control-label col-md-3">Last Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" data-required="1" name="last_name" class="form-control" value="<?php echo $user->last_name; ?>" placeholder="Last Name">
                                                                </div>
                                                            </div>

                                                          

                                                            

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Email  </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="email" class="form-control" value="<?php echo $user->email; ?>" placeholder="Email" readonly>
                                                                </div>
                                                            </div>

                                                           

                                                             
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Mobile </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="phone_number" class="form-control" value="<?php echo $user->phone_number; ?>" placeholder="Mobile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Alt Mobile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="alt_phone_number" class="form-control" value="<?php echo $user->alt_phone_number; ?>" placeholder="Alt Mobile">
                                                                </div>
                                                            </div>

                                                            

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Aadhar Number </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="aadhar_no" class="form-control" value="<?php echo $user->aadhar_no; ?>" placeholder="Aadhar Number">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Company Name </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="company_name" class="form-control" value="<?php echo $user->company_name; ?>" placeholder="Company Name">
                                                                </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">GSTN </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="gstn" class="form-control" value="<?php echo $user->gstn; ?>" placeholder="GSTN">
                                                                </div>
                                                            </div>


                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Skype ID  </label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="skype_id" class="form-control" value="<?php echo $user->skype_id; ?>" placeholder="Skype ID">
                                                                </div>
                                                            </div>

                                                              <div class="form-group">
                                                                <label class="col-md-3 control-label">Facebook Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="facebook_profile" class="form-control" value="<?php echo $user->facebook_profile; ?>" placeholder="Facebook Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Linked in Profile</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="linked_in_profile" class="form-control" value="<?php echo $user->linked_in_profile; ?>" placeholder="Linked in Profile">
                                                                </div>
                                                            </div>

                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Twitter Handle</label>
                                                                <div class="col-md-4">
                                                                    <input type="text" name="twitter_handle" class="form-control" value="<?php echo $user->twitter_handle; ?>" placeholder="Twitter Handle">
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


                                                                                       <option value="<?php echo $country->id; ?>" <?php if($user->country == $country->id) echo "selected"; ?> ><?php echo $country->name; ?></option>

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
                                                                        <?php
                                                                            if(!empty($state))
                                                                            {

                                                                                foreach($state as $state)
                                                                                { ?>


                                                                                       <option value="<?php echo $state->id; ?>" <?php if($user->state == $state->id) echo "selected"; ?> ><?php echo $state->name; ?></option>

                                                                               <?php }
                                                                            }

                                                                        ?>                                                                       
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>

                                                             <div class="form-group">
                                                                <label class="col-md-3 control-label">City</label>
                                                                <div class="col-md-4">
                                                                    <select class="form-control" name="city" id="city">
                                                                        <option value="">Select</option> 
                                                                        <?php
                                                                            if(!empty($city))
                                                                            {

                                                                                foreach($city as $city)
                                                                                { ?>


                                                                                       <option value="<?php echo $city->id; ?>" <?php if($user->city == $city->id) echo "selected"; ?> ><?php echo $city->name; ?></option>

                                                                               <?php }
                                                                            }

                                                                        ?>                                                                      
                                                                        
                                                                    </select>
                                                                </div>
                                                            </div>
                                                           
                                                             
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-offset-3 col-md-4">
                                                                    <button type="submit" class="btn green">Update</button>
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

        </script>