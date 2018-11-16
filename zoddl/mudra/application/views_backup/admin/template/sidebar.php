<!-- BEGIN SIDEBAR -->
<?php
$TagId = $this->db->query("select Id from tbl_tag where 1= 1")->row();

$DoctagId = $this->db->query("select Id from tbl_doc_tag where 1= 1")->row();
?>
                <div class="page-sidebar-wrapper">
                    
                    <div class="page-sidebar navbar-collapse collapse">
                                   <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
                                                 
                            <li class="active">
                                <a href="<?php echo base_url('dashboard');?>">
                                    <i class="icon-home"></i>
                                    <span class="title">Dashboard</span>
                                    
                                </a>
                                
                            </li>

                           

                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="fa fa-user"></i>
                                    <span class="title">User Managment</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                    <li class="nav-item">
                                        <a href="<?php echo base_url('staffcustomer');?>" class="nav-link ">
                                            <span class="title">Staff Users</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="<?php echo base_url('appcustomer');?>" class="nav-link ">
                                            <span class="title">General Users</span>
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a href="<?php echo base_url('companycustomer');?>" class="nav-link ">
                                            <span class="title">Comapny Users</span>
                                        </a>
                                    </li>
                                   
                                </ul>
                            </li>

                            <li class="">
                                <a href="<?php echo base_url('imagegallery/image_data_entry/'.$TagId->Id);?>">
                                    <i class="icon-social-dribbble"></i>
                                    <span class="title">Image Data Entry</span>
                                    
                                </a>
                                
                            </li>
                            
                            <li class="heading">
                                <h3 class="uppercase">Tag Managment</h3>
                            </li>
                            <li class="nav-item  ">
                                <a href="javascript:;" class="nav-link nav-toggle">
                                    <i class="fa fa-tags"></i>
                                    <span class="title">Tags</span>
                                    <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">

                                   <!-- <li class="nav-item">
                                        <a href="<?php echo base_url('tag');?>" class="nav-link ">
                                            <span class="title">Image Gallery</span>
                                        </a>
                                    </li> -->

                                    <li class="nav-item">
                                        <a href="<?php echo base_url('primarytag');?>" class="nav-link ">
                                            <span class="title">Primary Tag</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?php echo base_url('secondarytag'); ?>" class="nav-link ">
                                            <span class="title">Secondary Tag</span>
                                        </a>
                                    </li>
                                    
                                   
                                </ul>
                            </li>
                            
                            
                                </ul>
                            </li>
                        </ul>
                        <!-- END SIDEBAR MENU -->
                        <!-- END SIDEBAR MENU -->
                    </div>
                    <!-- END SIDEBAR -->
                </div>
                <!-- END SIDEBAR -->
