 <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN THEME PANEL -->
                        
                        <!-- END THEME PANEL -->
                         <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Managed General customer </h1>
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo base_url();?>">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                
                                <li>
                                    <span>General customer</span>
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
                                               
                                                <?php $this->load->view('admin/template/search-user');?>
                                                
                                            </div>
                                        </div>
                                        
                                        <table id="example" class="table table-striped table-bordered table-hover table-checkable order-column">
                                            <thead>
                                                
                                                <tr>
                                                <th>
                                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                            <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                                            <span></span>
                                                        </label>
                                                </th>
                                                 

                                                                                                    
                                                    <th> Full Name </th>                                                                                                    
                                                

                                                                                                    
                                                    <th> Email </th>                                                                                                    
                                               
                                                 
                                                                                                   
                                                    <th> Phone </th>                                                                                                    
                                                
                                                
                                                                                                  
                                                    <th> Social Profile </th>                                                                                                    
                                                
                                                     <th> Status </th>   
                                                                                                  
                                                    <th style="width:100%;"> Action </th>                                                                                                    
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
                            
                        table = $('#example').DataTable({
                        "lengthMenu": [[30, 60, 120, -1], [30, 60, 120, "All"]],          
                        "bSort": false,     "bFilter": true,
                        "processing": true, //Feature control the processing indicator.
                        "serverSide": true, //Feature control DataTables' server-side processing mode.
                        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {    
                            var rowClass = aData[7]
                             $(nRow).addClass(rowClass);               
                            },      
                        "ajax": {
                            "url": "<?php if($searchkeyword != '') { echo base_url('appcustomer/appcustomerdata/'.$searchkeyword);} else { echo base_url('appcustomer/appcustomerdata'); }?>",
                            "type": "POST"
                        },
                        "columnDefs": [
                        { 
                          "targets": [ -1 ], //last column
                          "orderable": false, //set not orderable
                        },
                            {
                                "targets": [7],
                                "visible": false
                            },  
                        ],
                      });            

                        $('#searchbytext').click(function(){
                            
                           var searchkeyword = $('#searchkeyword').val().trim();


                           if(searchkeyword == '')
                           {
                                 
                                alert("Please enter Name/Email/Phone!");
                                 
                           }
                           else if( $.isNumeric(searchkeyword) )
                           {

                              alert("Please enter only character type value! Ex: Adam");
                           }
                           else
                           {
                               var sendurl = "<?php echo base_url('appcustomer/search/'); ?>" +searchkeyword;
                                
                               window.location.href = sendurl; 

                           }
                        });

                        $('#userstatus').change(function(){
                                var value = $(this).val();
                            if(value != '')
                               {
                                    

                                     var sendurl = "<?php echo base_url('appcustomer/search/'); ?>" +value;
                                    
                                   window.location.href = sendurl;
                               }
                            else
                               {
                                    alert("Please choose one!");

                               }                                

                        });

                    });

 function delete_customer(id)
 {     
     var result = confirm("Are you sure want to delete?");
     if(result){
        // return false;
         $.ajax({
            type: "GET",
            url: "<?php echo base_url('customer/delete' ); ?>/" + id,
            success: function(msg)
            {
                if( msg == 'deleted' )
                {
                    //$('.delete' + id).fadeOut('normal');
                    alert("Deleted Successfully!'");
                    location.reload();
                }
            }

        });
     }
 }

function status_customer(id,status)
         {     
             var result = confirm("Are you sure want to Change Status?");
             if(result){
                // return false;
                 $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('customer/changestatus' ); ?>/" + id + '/' + status,
                    success: function(msg)
                    {
                        if( msg == 'success' )
                        {
                            alert("Status Change Successfully!'");
                            location.reload();
                        }
                    }

                });
             }
         }
        
  </script>