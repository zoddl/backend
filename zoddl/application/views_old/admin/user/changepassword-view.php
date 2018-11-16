             <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Admin Change Password
                            
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
                                    <span>Change Password</span>
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
                                                    <form class="form-horizontal" role="form" method="post" action="<?php echo base_url('dashboard/changepassword');?>" enctype="multipart/form-data">
							 <?php if($this->session->flashdata('flashSuccess5')){?>
							<div class="form-group">
								<p class='flashMsg forgetSuccess' style="color:#F00; margin-left:26%">
                                <?php echo $this->session->flashdata('flashSuccess5'); ?></p>
                            </div>
								 <?php }?>
                                                        <div class="form-body">
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Old Password</label>
                                                                <div class="col-md-4">
                                                                    <input type="password" name="opassword" class="form-control" placeholder="Old Password">
					<div style="color:red; font-weight:bold;"><?php  echo form_error('opassword'); ?></div>
									</div>
                                                                    
                                                                </div>
	
                                                            
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">New Password</label>
                                                                <div class="col-md-4">
                                                                    
                                                                                                                                              <input type="password" name="password" class="form-control" placeholder="New Password">
<div style="color:red; font-weight:bold;"><?php  echo form_error('password'); ?></div>
 </div>

                                                                
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="col-md-3 control-label">Confirm Password</label>
                                                                <div class="col-md-4">
                                                                    
                                                                        <input type="password" name="cpassword" class="form-control" placeholder="Confirm Password">
<div style="color:red; font-weight:bold;"><?php  echo form_error('cpassword'); ?>
                                                                      
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                             
                                                        <div class="form-actions">
                                                            <div class="row">
                                                                <div class="col-md-offset-3 col-md-4">
                                                                    <button type="submit" class="btn green">Submit</button>
                                                                    <button type="button" class="btn default">Cancel</button>
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
           
