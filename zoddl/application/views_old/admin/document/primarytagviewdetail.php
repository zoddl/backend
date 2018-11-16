 <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN THEME PANEL -->
                        
                        <!-- END THEME PANEL -->
                         <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Managed Document Gallery </h1>
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

                                    <a href="<?php echo base_url('document/customerdocument/'.$usertypeid.'/'.$customerid); ?>">Document Gallery</a>
                                    <i class="fa fa-angle-right"></i>
                                </li>

                                <?php
                                    if($Secondary_Name != '')
                                    { ?>

                                        <li>
                                            <a href="<?php echo base_url('document/searchbysecondarytag/'.$usertypeid.'/'.$customerid.'/'.$secondarytagid); ?>"><?php echo $Secondary_Name; ?></a>
                                            <i class="fa fa-angle-right"></i>
                                        </li>

                                    <?php }
                                ?>

                                <li>
                                    <span><?php echo $primaryName; ?></span>
                                </li>
                            </ul>
                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light bordered">
                                    
                                    <div class="portlet-body">
                                        <div class="table-toolbar">
                                            <div class="row">
                                              <div class="table-responsive">
                                            <table class="table-hover">
                                                <thead>
                                                    <tr>
                                                         <h4>Primary Tag</h4>
                                                         <?php
                                                         if(!empty($CustomerPrimaryTag))
                                                         {
                                                            foreach($CustomerPrimaryTag as $cpt)
                                                            {
                                                         ?>
                                                        <th> <a href="<?php echo base_url('document/searchbyprimarytag/'.$usertypeid.'/'.$cpt->Customer_Id.'/'.$cpt->Id); ?>" class="btn default" style="margin:5px;"> <?php echo $cpt->Prime_Name; ?> </a> </th>
                                                        
                                                        <?php }} else { echo "<th> Not Available!</th>";}?>
                                                    </tr>
                                                </thead>
                                               
                                            </table>

                                            <table class="table-hover">
                                                <thead>
                                                    <tr>
                                                       <h4>Secondry Tag</h4>
                                                           <?php
                                                         if(!empty($CustomerSecondaryTag))
                                                         {
                                                            foreach($CustomerSecondaryTag as $cst)
                                                            {
                                                         ?>
                                                        <th> <a href="<?php echo base_url('document/searchbysecondarytag/'.$usertypeid.'/'.$cst->Customer_Id.'/'.$cst->Id); ?>" class="btn default" style="margin:5px;"> <?php echo $cst->Secondary_Name; ?> </a> </th>
                                                        
                                                        <?php }}else { echo "<th> Not Available!</th>";} ?>
                                                        
                                                       
                                                    </tr>
                                                </thead>
                                               
                                            </table>

                                        </div> 
                                                
                                            </div>
                                        </div>
                                        <table id="example" class="table table-striped table-bordered table-hover table-checkable order-column">
                                            <thead>
                                                <tr>
                                                    <th> <?php echo $primaryName; ?> </th>
                                                    <th> Images </th>
                                                                                                  
                                                </tr>
                                            </thead>
                                            <tbody>
                                              
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        </div>

                        
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
<script src="<?php echo base_url('backend/assets/jquery/jquery-2.1.4.min.js')?>"></script> 
  <script>
            $(document).ready(function(){

                //$.noConflict();     
                      table = $('#example').DataTable({
                        "lengthMenu": [[30, 60, 120, -1], [30, 60, 120, "All"]],          
                        "bSort": false,     "bFilter": false,
                        "processing": true, //Feature control the processing indicator.
                        "serverSide": true, //Feature control DataTables' server-side processing mode.
                        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {    
                            var rowClass = aData[2]
                             $(nRow).addClass(rowClass);               
                            },      
                        "ajax": {
                            "url": "<?php echo base_url('document/primarytagviewdata/'.$usertypeid.'/'.$customerid.'/'.$primarytagid.'/'.$secondarytagid)?>",
                            "type": "POST"
                        },
                        "columnDefs": [
                        { 
                          "targets": [ -1 ], //last column
                          "orderable": false, //set not orderable
                        },
                            {
                                "targets": [2],
                                "visible": false
                            },  
                        ],
                      });

                    });
        function status_tag(id,status)
         {    
         
             var result = confirm("Are you sure want to Change Status?");
             if(result){
                // return false;
                 $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('document/changestatus' ); ?>/" + id + '/' + status,
                    success: function(msg)
                    {
                        if( msg == 'success' )
                        {
                            alert("Status Change successfully!'");
                            location.reload();
                        }
                    }

                });
             }
         }
  </script>