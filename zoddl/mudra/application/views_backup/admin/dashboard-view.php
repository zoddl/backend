<?php
$TagId = $this->db->query("select Id from tbl_tag where 1= 1")->row();

$DoctagId = $this->db->query("select Id from tbl_doc_tag where 1= 1")->row();
?>
             <!-- BEGIN CONTENT -->
                <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        
                        <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Admin Dashboard
                            
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
                                    <span>Dashboard</span>
                                </li>
                            </ul>
                            
                        </div>
                        <!-- END PAGE BAR -->
                        <!-- END PAGE HEADER-->
                        <div class="row widget-row">
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <a href="<?php echo base_url('staffcustomer');?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">Staff Users</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-green icon-users"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">Total</span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($susers); ?>">0</span>
                                        </div>
                                    </div>
                                </div> 
                            </a>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                 <a href="<?php echo base_url('appcustomer');?>" style="text-decoration:none;">
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading">General Users</h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-red icon-users"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">Total</span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($gusers); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                                <!-- END WIDGET THUMB -->
                            </div>
                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                 <a href="<?php echo base_url('companycustomer');?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Company Users</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-purple icon-users"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($cusers); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <!-- END WIDGET THUMB -->
                            </div>

                             <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                 <a href="<?php echo base_url('primarytag');?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Primary Tag</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-red icon-tag"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($ptag); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <!-- END WIDGET THUMB -->
                            </div>

                        </div>

                        <div class="row widget-row">

                               <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                 <a href="<?php echo base_url('secondarytag');?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Secondary Tag</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-purple icon-tag"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($stag); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <!-- END WIDGET THUMB -->
                            </div>

                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                 <a href="<?php echo base_url('imagegallery/image_data_entry/'.$TagId->Id);?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Image Data Entry</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-blue icon-picture"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($images); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <!-- END WIDGET THUMB -->
                            </div>

                            
                        </div>
                        
                            
                        
                        
                       
                                
                              
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
                
                
            <!-- END CONTAINER -->
           
