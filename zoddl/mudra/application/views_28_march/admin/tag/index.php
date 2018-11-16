 <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN THEME PANEL -->
                        
                        <!-- END THEME PANEL -->
                         <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Managed Image Gallery </h1>
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo base_url();?>">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                
                                <li>
                                    <span>Image Gallery</span>
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
                                               
                                                
                                            </div>
                                        </div>
                                        <table id="example" class="table table-striped table-bordered table-hover table-checkable order-column">
                                            <thead>
                                                <tr>                                                    
                                                    <th style="width:300px !important;"> Customer </th>
                                                    <th> Primary Tag </th>
                                                    <th> Secondry_Tag </th>                                                   
                                                    <th>Status</th>
                                                    <th>Action</th>                                                   
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
                        "bSort": false,     "bFilter": true,
                        "processing": true, //Feature control the processing indicator.
                        "serverSide": true, //Feature control DataTables' server-side processing mode.
                        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {    
                            var rowClass = aData[5]
                             $(nRow).addClass(rowClass);               
                            },      
                        "ajax": {
                            "url": "<?php echo base_url('tag/tagdata')?>",
                            "type": "POST"
                        },
                        "columnDefs": [
                        { 
                          "targets": [ -1 ], //last column
                          "orderable": false, //set not orderable
                        },
                            {
                                "targets": [5],
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
                    url: "<?php echo base_url('tag/changestatus' ); ?>/" + id + '/' + status,
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