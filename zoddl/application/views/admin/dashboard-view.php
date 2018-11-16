                <?php
                $TagId = $this->db->query("select Id from tbl_tag where 1= 1")->row();

                $DoctagId = $this->db->query("select Id from tbl_doc_tag where 1= 1")->row();
                ?>




                <?php 

                     $userType = $this->session->userdata('user_type');

                     $userRole = $this->session->userdata('user_role');

                     if( ($userType == 1 || $userType == 3) && ($userRole == 1 || $userRole == 3) )
                        {

                ?>
            
                <div class="page-content-wrapper">
                    
                    <div class="page-content">
                        
                        <h1 class="page-title"> Dashboard
                            
                        </h1>
                        
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
                       
                        <div class="row widget-row">
                            <div class="col-md-3">
                                
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
                                
                            </div>
                            <div class="col-md-3">
                                
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
                               
                            </div>

                        <?php
                         
                         if( $userType == 1 && $userRole == 1 )
                            {
                        ?>
                            <div class="col-md-3">
                                
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
                               
                            </div>

                        <?php } ?>

                             <div class="col-md-3">
                                
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
                                
                            </div>

                        </div>

                        <div class="row widget-row">

                               <div class="col-md-3">
                                
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
                                
                            </div>

                            <div class="col-md-3">
                                
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
                                
                            </div>

                            <div class="col-md-3">
                               
                                <a href="<?php echo base_url('imagedocument/image_data_entry/'.$DoctagId->Id);?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Document Data Entry</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-green icon-folder"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($documents); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                            </div>

                             <div class="col-md-3">                               
                                
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading"> Pending Image Data Entry</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-green icon-folder"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($pendingImageDataEntry); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                
                                
                            </div>

                        </div>

                        <div class="row widget-row">

                               <div class="col-md-3">
                                
                                 <a href="<?php echo base_url('secondarytag');?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Pending Document Data Entry</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-purple icon-tag"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($pendingDocumentDataEntry); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                            </div>

                            <div class="col-md-3">
                                
                                 <a href="<?php echo base_url('imagegallery/image_data_entry/'.$TagId->Id);?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">New Image Data Entry</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-blue icon-picture"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($newImageDataEntry); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                            </div>

                            <div class="col-md-3">                               
                                <a href="<?php echo base_url('imagedocument/image_data_entry/'.$DoctagId->Id);?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">New Document Data Entry</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-green icon-folder"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($newDocumentDataEntry); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                            </div>

                            <div class="col-md-3">                               
                                
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading"> New App User</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-green icon-folder"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($newUser); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                
                                
                            </div>


                        </div>

                        <!-- Start -->

                        <div class="row widget-row">

                               <div class="col-md-3">
                                
                                 <a href="<?php echo base_url('secondarytag');?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Paid User</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-purple icon-tag"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($paidUser); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                            </div>

                            <div class="col-md-3">
                                
                                 <a href="<?php echo base_url('imagegallery/image_data_entry/'.$TagId->Id);?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Free User</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-blue icon-picture"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($freeUser); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                            </div>


                        </div>

                         <hr>

                       <h1 class="page-title"> Summary API Hits Numbers. </h1>

                       <div class="row widget-row">

                                <?php 
                                        if(!empty($api_report))
                                        {

                                            foreach($api_report as $ar)
                                            {
                                ?>
                            

                            <div class="col-md-3">
                                <!-- BEGIN WIDGET THUMB -->
                                <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                    <h4 class="widget-thumb-heading"><?php echo $ar->Api_Name; ?></h4>
                                    <div class="widget-thumb-wrap">
                                        <i class="widget-thumb-icon bg-green icon-bulb"></i>
                                        <div class="widget-thumb-body">
                                            <span class="widget-thumb-subtitle">Total</span>
                                            <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($ar->Api_Count); ?>">0</span>
                                        </div>
                                    </div>
                                </div>
                                <!-- END WIDGET THUMB -->
                            </div>

                            <?php } } else{

                                   echo "<strong>No Summary Available.</strong>";
                            }?>
                           
                          
                           
                        </div>

                    </div>
                   
                </div>

                <?php } else { ?>


                     <div class="page-content-wrapper">
                    
                    <div class="page-content">
                        
                        <h1 class="page-title"> Dashboard
                            
                        </h1>
                        
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
                       
                        <div class="row widget-row">
                            
                            

                            <div class="col-md-3">
                                
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
                                
                            </div>

                            <div class="col-md-3">
                               
                                <a href="<?php echo base_url('imagedocument/image_data_entry/'.$DoctagId->Id);?>" style="text-decoration:none;">
                                    <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                                        <h4 class="widget-thumb-heading">Document Data Entry</h4>
                                        <div class="widget-thumb-wrap">
                                            <i class="widget-thumb-icon bg-green icon-folder"></i>
                                            <div class="widget-thumb-body">
                                                <span class="widget-thumb-subtitle">Total</span>
                                                <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?php echo number_format($documents); ?>">0</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                
                            </div>
                        </div>
                    </div>
                   
                </div>



               <?php } ?>
               