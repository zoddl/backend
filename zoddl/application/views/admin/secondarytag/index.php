 <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN THEME PANEL -->
                        
                        <!-- END THEME PANEL -->
                         <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Managed Secondary Tag </h1>
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo base_url();?>">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <a href="<?php echo base_url('tag');?>">Tag</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <li>
                                    <span>Secondary Tag</span>
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
                                                    <th style="width:100%"> Customer </th>
                                                    <th> Secondary Tag </th>                                                   
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
                            var rowClass = aData[2]
                             $(nRow).addClass(rowClass);               
                            },      
                        "ajax": {
                            "url": "<?php echo base_url('secondarytag/secondarytagdata')?>",
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
           
  </script>