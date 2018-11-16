             <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> View User Detail
                            
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
                                    <span>View User Detail</span>
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
                                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover">
                                               
                                                    <tr>
                                                        
                                                        <th> Full Name </th>
                                                         <td> <?php echo $customer->name;?> </td>
                                                        
                                                    </tr>
                                               
                                               
                                                     <tr>
                                                        
                                                        <th> Email </th>
                                                         <td> <?php echo $customer->email;?> </td>
                                                        
                                                    </tr>
                                                     <tr>
                                                        
                                                        <th> Mobile </th>
                                                         <td> <?php echo $customer->phone_number;?> </td>
                                                        
                                                    </tr>

                                                     <tr>
                                                        
                                                        <th> Alt Mobile </th>
                                                        <td> <?php echo $customer->alt_phone_number;?> </td>
                                                        
                                                    </tr>
                                                     <tr>
                                                        
                                                        <th> Aadhar Number </th>
                                                         <td> <?php echo $customer->aadhar_no;?> </td>
                                                        
                                                    </tr>
                                                      <tr>
                                                        
                                                        <th> Skype ID </th>
                                                         <td> <?php echo $customer->skype_id;?> </td>
                                                        
                                                    </tr>
                                                      <tr>
                                                        
                                                        <th> Facebook Profile </th>
                                                         <td> <?php echo $customer->facebook_profile;?> </td>
                                                        
                                                    </tr>
                                                      <tr>
                                                        
                                                        <th> Linked in Profile </th>
                                                         <td> <?php echo $customer->linked_in_profile;?> </td>
                                                        
                                                    </tr>

                                                    <tr>
                                                        
                                                        <th> Twitter Handle </th>
                                                         <td> <?php echo $customer->twitter_handle;?> </td>
                                                        
                                                    </tr>
                                                    
                                               
                                            </table>
                                        </div>
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
           
        