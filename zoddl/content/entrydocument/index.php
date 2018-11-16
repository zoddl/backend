 <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN THEME PANEL -->
                        
                        <!-- END THEME PANEL -->
                         <!-- BEGIN PAGE TITLE-->
                       <!--  <h1 class="page-title"> Add manual entry </h1> -->
                        <!-- BEGIN PAGE BAR -->
                        <div class="page-bar">
                            <ul class="page-breadcrumb">
                                <li>
                                    <a href="<?php echo base_url();?>">Home</a>
                                    <i class="fa fa-circle"></i>
                                </li>

                                 <li>
                                    <a href="<?php echo base_url('appcustomer');?>">General customer</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                
                                <?php

                                if(!empty($entryid))
                                { ?>

                                <li>
                                    <a href="<?php echo base_url('entrydocument/index/'.$customerid);?>">Entry</a>
                                    <i class="fa fa-circle"></i>
                                </li>
                                <?php }  ?>

                                <li>
                                    <span>Document Manual Entry</span>
                                </li>
                            </ul>
                           
                        </div>

                        <div class="row">
                             <div class="col-md-12">
                                

                                 <?php
                                   if(!empty($customerinfo->Id) && !empty($customerinfo->Customer_Name) && !empty($customerinfo->Email_Id))
                                   {

                                          $customer = "(".$customerinfo->Id." , ".$customerinfo->Customer_Name. " ( ".$customerinfo->Email_Id.") )";
                                   }
                                   else
                                   {
                                          $customer = "(".$customerinfo->Id." , ".$customerinfo->Email_Id.")";

                                   }
                                  ?>
                                      <!-- Start -->

                                      <div class="portlet box red">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-gift"></i><?php echo $page_title; ?> Document Manual Entry : User : <?php echo $customer; ?></div>                                
                            </div>
                            <div class="portlet-body form">
                                <div class="form-body">
                                    <div class="form-group">
                                        <form action="<?php echo base_url('entrydocument/addmanual/'.$customerid); ?>" method="post" enctype="multipart/form-data" class="mt-repeater form-horizontal" id="addmanualentryform" novalidate="novalidate">
                                            
                                             <input type="hidden" name="Customer_Id" id="Customer_Id" value="<?php if(!empty($customerid)) { echo $customerid; }?>" />
                                             <input type="hidden" name="id" id="id" value="<?php if(!empty($info->Id)) { echo $info->Id; }?>" />
                                               

                                            <div data-repeater-list="group-a">
                                                <div data-repeater-item="">
                                                    <!-- jQuery Repeater Container -->
                                                    

                                                    <div class="mt-repeater-input">
                                                        <label class="control-label">E.Type</label>
                                                        <br>
                                                       
                                                         
                                                            <label class="mt-radio">
                                                             <input type="radio"  name="Tag_Type" value="cash+" title="cash+" <?php if (!empty($info->Tag_Type) && $info->Tag_Type == 'cash+') { echo "checked"; } ?>>
                                                             <label for="radio1">C+</label>
                                                              <span></span>
                                                            </label>

                                                             <label class="mt-radio">
                                                               <input type="radio"  name="Tag_Type" value="cash-" title="cash-" <?php if ( !empty($info->Tag_Type)  && $info->Tag_Type == 'cash-' ) { echo "checked"; } ?>>
                                                               <label for="radio1">C-</label>
                                                                <span></span>
                                                             </label>

                                                             <label class="mt-radio">
                                                            <input type="radio"  name="Tag_Type" value="bank+"  title="bank+" <?php if (!empty($info->Tag_Type) && $info->Tag_Type == 'bank+') { echo "checked"; } ?>>
                                                           <label for="radio1">B+</label>
                                                            <span></span>
                                                             </label>

                                                           <label class="mt-radio">
                                                            <input type="radio"  name="Tag_Type" value="bank-" title="bank-" <?php if (!empty($info->Tag_Type) && $info->Tag_Type == 'bank-') { echo "checked"; } ?>>
                                                           <label for="radio1">B-</label>
                                                            <span></span>
                                                             </label>

                                                           <label class="mt-radio">
                                                           <input type="radio"  name="Tag_Type" value="other" title="other" <?php if (!empty($info->Tag_Type) && $info->Tag_Type == 'other') { echo "checked"; } ?>>
                                                           <label for="radio1">O</label>
                                                            <span></span>
                                                             </label>
                                                          
                                                    </div>


                                                    <div class="mt-repeater-input">
                                                        <label class="control-label">Date</label>
                                                        <br>
                                                        <input class="input-group form-control form-control-inline date date-picker" size="16" type="text" value="<?php if(!empty($info->Tag_Send_Date)) { echo $info->Tag_Send_Date; }?>" name="Tag_Send_Date" data-date-format="yyyy-mm-dd"> 

                                                      </div>

                                                    <div class="mt-repeater-input">
                                                        <label class="control-label">Amount</label>
                                                        <br>
                                                        <input type="text" name="Amount" class="form-control btn-sm" value = "<?php if(!empty($info->Amount)) { echo $info->Amount; }?>" /> 
                                                    </div>
                                                    
                                                    <div class="mt-repeater-input mt-radio-inline">
                                                        <label class="control-label">P.Tag</label>
                                                        <br>
                                                       <input id = "Primary_Tag" type="text" name="Primary_Tag" class="form-control btn-sm pptagg" value = "<?php if(!empty($info->Prime_Name)) { echo $info->Prime_Name; }?>" />
                                                         <div id="suggesstion-box"></div>
                                                    </div>

                                                    <div class="mt-repeater-input mt-radio-inline">
                                                        <label class="control-label">S.Tag</label>
                                                        <br>
                                                        <input id = "Secondry_Tag" type="text" name="Secondry_Tag" class="form-control btn-sm pptagg" value = "<?php if(!empty($info->Secondary_Name)) { echo $info->Secondary_Name; }?>" />
                                                        <div id="suggesstion-box-s"></div>
                                                    </div>

                                                    <div class="mt-repeater-input">
                                                        <label class="control-label">Description</label>
                                                        <br>
                                                        <input type="text" name="Description" class="form-control btn-sm" value = "<?php if(!empty($info->Description)) { echo $info->Description; }?>" /> 
                                                    </div>

                                                     <div class="mt-repeater-input">
                                                        <label class="control-label">Status</label>
                                                        <br>
                                                       <select name ="Tag_Status" class="form-control select2">
                                                            <option value = "" >Select Status</option>                                                                      
                                                            <option value="1" <?php if( !empty($info->Tag_Status) && $info->Tag_Status == '1') { ?> selected="selected"<?php } ?>>Approved</option>
                                                            <option value="2" <?php if( !empty($info->Tag_Status) &&  $info->Tag_Status == '2') { ?> selected="selected"<?php } ?>>Pending</option>
                                                            <option value="3" <?php if( !empty($info->Tag_Status) &&  $info->Tag_Status == '3') { ?> selected="selected"<?php } ?>>Decline</option>
                                                            <option value="4" <?php if( !empty($info->Tag_Status) &&  $info->Tag_Status == '4') { ?> selected="selected"<?php } ?>>Decline Blur</option>                       
                                                      </select>
                                                    </div>

                                                     <div class="mt-repeater-input" >
                                                            <br>
                                                            <a class="btn btn-success mt-repeater-add" id="togglebutton"> <i class="fa fa-plus"></i> D</a>
                                                            &nbsp;&nbsp;
                                                            <button class="btn btn-success mt-repeater-add" type="submit"> Submit</button>
                                                     </div>
                                                    
                                                </div>
                                            </div>


                                            <div id="toggle" style="display:none;">
                                           
                                            <div data-repeater-list="group-a">
                                                <div data-repeater-item="">
                                                    <!-- jQuery Repeater Container -->
                                                    

                                                     <div class="mt-repeater-input">
                                                        <label class="control-label">Sub Description</label>
                                                        <br>
                                                        <input type="text" name="Sub_Description" class="form-control btn-sm" value = "<?php if(!empty($info->Sub_Description)) { echo $info->Sub_Description; }?>" /> 
                                                    </div>

                                                    <div class="mt-repeater-input">
                                                        <label class="control-label">CGST</label>
                                                        <br>
                                                        <input type="text" name="CGST" class="form-control btn-sm" value = "<?php if(!empty($info->CGST)) { echo $info->CGST; }?>" /> 
                                                    </div>
                                                    <div class="mt-repeater-input">
                                                        <label class="control-label">SGST</label>
                                                        <br>
                                                        <input type="text" name="SGST" class="form-control btn-sm" value = "<?php if(!empty($info->SGST)) { echo $info->SGST; }?>" /> 
                                                    </div>
                                                    
                                                   <div class="mt-repeater-input">
                                                        <label class="control-label">IGST</label>
                                                        <br>
                                                        <input type="text" name="IGST" class="form-control btn-sm" value = "<?php if(!empty($info->IGST)) { echo $info->IGST; }?>" /> 
                                                    </div>

                                                    <div class="mt-repeater-input">
                                                        <label class="controlCustomer_Id-label">Invoice/Cheque No</label>
                                                        <br>
                                                        <input type="text" name="Invoice_Check_No" class="form-control btn-sm" value = "<?php if(!empty($info->Invoice_Check_No)) { echo $info->Invoice_Check_No; }?>" /> 
                                                    </div>

                                                   
                                                    
                                                </div>
                                            </div>

                                          </div>

                                            

                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                                      <!-- End -->


                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                                <div class="portlet light portlet-fit portlet-datatable bordered">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="icon-settings font-dark"></i>
                                            <span class="caption-subject font-dark sbold uppercase">Entry Listing</span>
                                        </div>
                                      
                                    </div>
                                    <div class="portlet-body">
                                        <div class="table-container">
                                           
                                            <table class="table table-striped table-bordered table-hover table-checkable" id="datatable_ajax">
                                                <thead>
                                                    <tr role="row" class="heading">
                                                       
                                                        <th width="5%"> Record&nbsp;# </th>
                                                        <th width="200"> Date </th>
                                                        <th width="10%"> Primary Tag </th>
                                                        <th width="10%"> Secondary Tag </th>
                                                        <th width="10%"> Tag Type</th>
                                                        <th width="15%"> Amount </th>
                                                        <th width="10%"> Description </th>
                                                        <th width="10%"> Status </th>
                                                        <th width="10%"> Actions </th>
                                                    </tr>
                                                    <tr role="row" class="filter">
                                                        <td> </td>
                                                        
                                                        

                                                        <td>
                                                             <div class="input-group date date-picker margin-bottom-5" data-date-format="yyyy-mm-dd">
                                                                <input type="text" class="form-control form-filter input-sm" readonly name="date" placeholder="Date">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-sm default" type="button">
                                                                        <i class="fa fa-calendar"></i>
                                                                    </button>
                                                                </span>
                                                            </div>
                                                                                      </td>

                                                        <td>
                                                            <input type="text" class="form-control form-filter input-sm" name="primary"></td>
                                                        <td>
                                                            <input type="text" class="form-control form-filter input-sm" name="secondary"> </td>

                                                        <td>
                                                            <input type="text" class="form-control form-filter input-sm" name="tag"> </td>

                                                            <td>
                                                            <input type="text" class="form-control form-filter input-sm" name="amount">
                                                        </td>
                                                        
                                                            <td>
                                                            <input type="text" class="form-control form-filter input-sm" name="description"> </td>
                                                        <td>
                                                            <select name="status" class="form-control form-filter input-sm">
                                                                <option value="">Select...</option>
                                                                
                                                                  <option value="1">Approved</option>
                                                                  <option value="2">Pending</option>
                                                                  <option value="3">Decline</option>
                                                                  <option value="4">Decline Blur</option> 
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <div class="margin-bottom-5">
                                                                <button class="btn btn-sm green btn-outline filter-submit margin-bottom">
                                                                    <i class="fa fa-search"></i> Search</button>
                                                            </div>
                                                            <button class="btn btn-sm red btn-outline filter-cancel">
                                                                <i class="fa fa-times"></i> Reset</button>
                                                        </td>
                                                    </tr>
                                                </thead>
                                                <tbody> </tbody>
                                            </table>
                                        </div>
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

            <style>
            .autocomplete {  
  position: relative;
  display: inline-block;
}
    
.autocomplete-items {  
  z-index: 99;  
  top: 100%;
  left: 0;
  right: 0;
  box-shadow: 5px 5px rgba(102,102,102,.1);
  min-width: 175px;
  width: 100%;
  float: left;
    list-style: none;
    text-shadow: none;
    padding: 0;
    margin: 0;
    background-color: #fff;
    border: 1px solid #eee;
    font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -ms-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
}

.autocomplete-items li {
    text-decoration: none;
    background-image: none;
    color: #555;
    filter: none;
    padding: 8px 16px;
    cursor: pointer;
}

.autocomplete-items li:hover{
    background-color: #f6f6f6;
}

.autocomplete-items div {
  padding: 10px;
  cursor: pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}
.autocomplete-items div:hover { 
  background-color: #e9e9e9; 
}

.autocomplete-active { 
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
#country-list{float:left;list-style:none;margin-top:0px;padding:0;width:142px;position: absolute;}
#country-list li{padding: 5px; background: #f0f0f0; border-bottom: #bbb9b9 1px solid;}
#country-list li:hover{background:#ece3d2;cursor: pointer;}

.error { color: red;}
            </style>
            
        <script src="<?php echo base_url('backend/assets/jquery/jquery-2.1.4.min.js')?>"></script> 
        

        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script>
            var BASE_URL = '<?php echo base_url('entrydocument/dataresult/'.$customerid); ?>';
        </script>

        <script>
         $('#addmanualentryform').validate({
            errorElement: 'span', //default input error message container
            errorClass: 'error', // default input error message class
            rules: {
                             
                Tag_Type : {

                  required: true
                },
                Tag_Send_Date : {

                  required: true
                },
                Amount: {
                      required: true,
                      digits: true
                },
                Tag_Status : {

                  required: true

                }
            },

            messages: {
               
            },

            errorPlacement: function (error, element) {
                element.siblings('.error-msg').hide();
                if (element.attr("type") == "checkbox") {
                    $(element).parent('div').parent('div').append(error);
                    //error.insertAfter($(element).parents('ul').prev($('.question')));
                } else if (element.attr("type") == "radio") {
                    $(element).parent('div').parent('div').parent('div').parent('div').append(error);
                    //error.insertAfter($(element).parents('ul').prev($('.question')));
                } else if (element.attr("name") == "enterprise_industry") {
                    $(element).parent('div').append(error);
                    //error.insertAfter($(element).parents('ul').prev($('.question')));
                } else {
                    error.insertAfter($(element));
                }
            },

            submitHandler: function (form) {
                //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                  form.submit();
                
            }
        });
    </script>
         
     <script>
    $(document).ready(function(){
      $("#Primary_Tag").keyup(function(){
        
        var val = $(this).val();
        var cid = $('#Customer_Id').val();
        $.ajax({
        type: "POST",
        url: "<?php echo base_url('entrydocument/getprimarytag' ); ?>",
        data:{ val: val, cid: cid},
        beforeSend: function(){
          $("#Primary_Tag").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
          $("#suggesstion-box").show();
          $("#suggesstion-box").html(data);
          $("#Primary_Tag").css("background","#FFF");
        }
        });
      });
      
      $("#Secondry_Tag").keyup(function(){
        
        var val = $(this).val();
        var cid = $('#Customer_Id').val();
        $.ajax({
        type: "POST",
        url: "<?php echo base_url('entrydocument/getsecondarytag' ); ?>",
        data:{ val: val, cid: cid},
        beforeSend: function(){
          $("#Secondry_Tag").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
        },
        success: function(data){
          $("#suggesstion-box-s").show();
          $("#suggesstion-box-s").html(data);
          $("#Secondry_Tag").css("background","#FFF");
        }
        });
      });

       $("#togglebutton").click(function(){
                $("#toggle").toggle();
            });

    });

    function selectCountry(val) {
    $("#Primary_Tag").val(val);
    $("#suggesstion-box").hide();
    }
    function selectCountrys(val) {
    $("#Secondry_Tag").val(val);
    $("#suggesstion-box-s").hide();
    }
</script>



 

  