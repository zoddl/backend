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

            <!-- /.modal -->
          <div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Re-send Verification Email</h4>
                      </div>
                      <form id="form_sample_1" class="form-horizontal" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="scroller" style="height:200px" data-always-visible="1" data-rail-visible1="1">
                                <div class="row">

                                    <div class="col-md-12">
                                        
                                       
                                        
                                          <input type="hidden" name="customerid" id="customerid">
                                              <div class="form-body"> 

                                                    <div class="alert alert-block alert-success fade in" style="padding:5px; display:none;" id="ajax">
                                                      <button type="button" class="close" data-dismiss="alert"></button>
                                                      <h5 class="alert-heading">Verification Mail Send Successfully.</h5>
                                                      
                                                  </div>                                               
                                                  
                                                  <div class="form-group  margin-top-20">
                                                      <label class="control-label col-md-3">Message
                                                          <span class="required"> * </span>
                                                      </label>
                                                      <div class="col-md-9">
                                                          <div class="input-icon right">
                                                              <i class="fa"></i>
                                                               <textarea class="form-control" rows="5" name="message" id="message"></textarea> </div>
                                                      </div>
                                                  </div>
                                                  
                                                  
                                              </div>
                                              
                                          
                                        
                                        
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn dark btn-outline">Close</button>
                            <button type="submit" id="submit" class="btn green">Send</button>
                        </div>
                      </form>
                  </div>
              </div>
          </div>

<script src="<?php echo base_url('backend/assets/jquery/jquery-2.1.4.min.js')?>"></script> 

<script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        
        <script>
        $("#form_sample_1").validate({
                errorElement: "span",
                errorClass: "help-block",
                focusInvalid: false,
                rules: {
                   
                    message: {
                        required: !0,
                        minlength:10, 
                        maxlength:500
                    } 
                },
                messages: {
                   
                },
                errorPlacement: function (error, element) {            
                    var cont = $(element).parent('.input-group');

                     if (element.attr("type") == "radio") {
                        console.log(element.attr("name"));
                        console.log($(element).parent().parent().parent().attr('class'));
                        $(element).parent().append(error);                
                    }
                   
                    else {
                        element.after(error);
                    }
                },
                highlight: function (e) {
                        $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                    },
            
                    success: function (e) {
                        $(e).closest('.form-group').removeClass('has-error');//.addClass('has-info');
                        $(e).remove();
                    },  
            
                    submitHandler: function (form) {


                        $.ajax({
                                  
                                  cache: false,
                                  type: "POST",
                                  url: "<?php echo base_url('appcustomer/sendverificationmail/'); ?>",
                                  data: $("#form_sample_1").serialize(),
                                  beforeSend : function(msg){ 
                                    $("#ajax").hide();
                                    $("#submit").html('<img src="<?php echo base_url('backend/assets/global/img/loading.gif'); ?>" />');

                                  },
                                  success: function(msg)
                                  {
                                     
                                     
                                      if(msg == 1)
                                      {
                                          $('#message').val('');
                                          $("#ajax").show(); 
                                          $("#submit").html('<button type="submit" id="submit" class="btn green">Send</button>');
                                      }
                                      else
                                      { 
                                          $('#message').val('');
                                          $("#ajax").show();
                                          $("#submit").html('<button type="submit" id="submit" class="btn green">Send</button>'); 
                                      }
                                  }

                            });

                                              
                    },
                    invalidHandler: function (form) {
                    }
            }), $("#login_form input").keypress(function(e) {
                if (13 == e.which) return $("#form_sample_1").validate().form() && $("#form_sample_1").submit(), !1
            }); 
        </script>

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
 function deletemultiplecustomer()
    {
        var result = confirm("Are you sure want to delete?");
        var checkboxvalue = getValueUsingClass();
        var data = 'checkboxvalue='+ checkboxvalue; 
        if(result == true){
             $.ajax({
                type: "POST",
                url: "<?php echo base_url('customer/multipledelete' ); ?>",
                data:data,
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
 function getValueUsingClass(){
    
    var chkArray = [];
    $(".checkboxes:checked").each(function() {
        chkArray.push($(this).val());
    });
    var selected;
    selected = chkArray.join(',') ;    
    
    if(selected.length >= 1){
        return selected;
    }else{
        
        alert("Please at least one of the checkbox");
        return false;   
    }
  }

  function sendverificationmail(id)
  {

      $('#customerid').val(id);

  }
        
  </script>