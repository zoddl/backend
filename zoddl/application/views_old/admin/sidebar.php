<?php               
               
    $imagetagQuery = $this->db->query("SELECT * FROM tbl_tag WHERE 1 = 1 AND (Tag_Type = '' or Tag_Send_Date = '' or Amount = 0 or Primary_Tag = 0 )");

    if($imagetagQuery->num_rows() > 0)
    {
        $TagId = $imagetagQuery->row()->Id;

        $imageTagUrl = base_url('imagegallery/image_data_entry/'.$TagId);

    }
    else
    {
       $TagId = '';
       $imageTagUrl = base_url('imagegallery/not_found'); 
    }

    $documenttagQuery = $this->db->query("SELECT * FROM tbl_doc_tag WHERE 1 = 1 AND (Tag_Type = '' or Tag_Send_Date = '' or Amount = 0 or Primary_Tag = 0 )");

    if($documenttagQuery->num_rows() > 0)
    {
        $DoctagId = $documenttagQuery->row()->Id;

        $docTagUrl = base_url('imagedocument/image_data_entry/'.$DoctagId);

    }
    else
    {

       $DoctagId = '';
       $docTagUrl = base_url('imagedocument/not_found'); 
    }

?>
    <div class="page-sidebar-wrapper">

        <div class="page-sidebar navbar-collapse collapse">
            
            <?php

                $userType = $this->session->userdata('user_type');

                $userRole = $this->session->userdata('user_role');

                if( ($userType == 1 || $userType == 3) && ($userRole == 1 || $userRole == 3) )
                    {

            ?>
            <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                
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

                        <?php
                         
                         if( $userType == 1 && $userRole == 1 )
                            {
                        ?>

                        <li class="nav-item">
                            <a href="<?php echo base_url('companycustomer');?>" class="nav-link ">
                                <span class="title">Company Users</span>
                            </a>
                        </li>

                        <?php } ?>

                    </ul>
                </li>

                <li class="">
                    <a href="<?php echo $imageTagUrl; ?>">
                        <i class="icon-social-dribbble"></i>
                        <span class="title">Image Data Entry</span>

                    </a>

                </li>

                <li class="">
                    <a href="<?php echo $docTagUrl; ?>">
                        <i class="icon-social-dribbble"></i>
                        <span class="title">Document Data Entry</span>

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

                <li class="heading">
                    <h3 class="uppercase">Notification Managment</h3>
                </li>
                <li class="">
                    <a href="<?php echo base_url('notification');?>">
                        <i class="fa fa-flag"></i>
                        <span class="title">Notification</span>

                    </a>
                </li>

            </ul>

            <?php } else { ?> 

                 <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200" style="padding-top: 20px">
                    
                        
                         <li class="active">
                            <a href="<?php echo base_url('dashboard');?>">
                                <i class="icon-home"></i>
                                <span class="title">Dashboard</span>

                            </a>

                        </li>

                        <li class="">
                            <a href="<?php echo $imageTagUrl; ?>">
                                <i class="icon-social-dribbble"></i>
                                <span class="title">Image Data Entry</span>
                            </a>
                        </li>

                        <li class="">
                            <a href="<?php echo $docTagUrl; ?>">
                                <i class="icon-social-dribbble"></i>
                                <span class="title">Document Data Entry</span>
                            </a>
                        </li>

                 </ul>

                <?php } ?>
            
        </div>

    </div>