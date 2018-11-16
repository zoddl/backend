 <style>
.loader {
  margin-left: 400px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 120px;
  height: 120px;
  -webkit-animation: spin 2s linear infinite; /* Safari */
  animation: spin 2s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
table tr:first-child td {
    font-weight: bold;
    font-size: 16px;
    background: #fff !important;
    padding: 10px 2px;
    -webkit-print-color-adjust: exact;
  }
}
</style>
 <div class="page-content-wrapper">
                    <!-- BEGIN CONTENT BODY -->
                    <div class="page-content">
                        <!-- BEGIN PAGE HEADER-->
                        <!-- BEGIN THEME PANEL -->
                        
                        <!-- END THEME PANEL -->
                         <!-- BEGIN PAGE TITLE-->
                        <h1 class="page-title"> Managed Report </h1>
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
                                    <span>Report</span>
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
                                              <div class="table-scrollable">
                                            <table class="table">
                                                <thead>

                                                  <tr> </tr>
                                                  
                                                    <tr>

                                                        <div class="icheck-inline"  onclick="getvalue();">
                                                           <td><lable>
                                                            <input type="checkbox" class="icheck test" data-checkbox="icheckbox_line-grey" data-label="Year" Tag-Type="master" Tag-Id="year" Source-Type="universal">
                                                       </lable></td>
                                                       <td><lable>
                                                            <input type="checkbox" class="icheck test" data-checkbox="icheckbox_line-grey" data-label="Month" Tag-Type="master" Tag-Id="month" Source-Type="universal">
                                                       </lable></td>
                                                       <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="Amount" Tag-Type="master" Tag-Id="amount" Source-Type="universal">
                                                       </lable></td>
                                                       <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="Cash+" Tag-Type="master" Tag-Id="cash+" Source-Type="universal">
                                                       </lable></td>
                                                       <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="Cash-" Tag-Type="master" Tag-Id="cash-" Source-Type="universal">
                                                       </lable></td>
                                                       <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="Bank+" Tag-Type="master" Tag-Id="bank+" Source-Type="universal">
                                                       </lable></td>
                                                        <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="Bank-" Tag-Type="master" Tag-Id="bank-" Source-Type="universal">
                                                       </lable></td>
                                                       <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="Other" Tag-Type="master" Tag-Id="other" Source-Type="universal">
                                                       </lable></td>
                                                         <?php
                                                         if(!empty($CustomerPrimaryTag))
                                                         {
                                                            foreach($CustomerPrimaryTag as $cpt)
                                                            {
                                                         ?>
                                                        <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="<?php echo $cpt['Prime_Name']; ?>" Tag-Type="<?php echo $cpt['Tag_Type']; ?>" Tag-Id="<?php echo $cpt['Id']; ?>" Source-Type="<?php echo $cpt['Source_Type']; ?>">
                                                       </lable></td>
                                                        
                                                        <?php }} else { echo "<th> Not Available!</th>";}?>
                                                         </div>
                                                    </tr>
                                                     <tr></br> </tr>
                                               
                                                    <tr>
                                                       <div class="icheck-inline">
                                                           <?php
                                                         if(!empty($CustomerSecondaryTag))
                                                         {
                                                            foreach($CustomerSecondaryTag as $cst)
                                                            {
                                                         ?>
                                                        <td><lable>
                                                            <input type="checkbox" class="icheck" data-checkbox="icheckbox_line-grey" data-label="<?php echo $cst['Secondary_Name']; ?>" Tag-Type="<?php echo $cst['Tag_Type']; ?>" Tag-Id="<?php echo $cst['Id']; ?>" Source-Type="<?php echo $cst['Source_Type']; ?>">
                                                       </lable></td>
                                                        
                                                        
                                                        <?php }}else { echo "<th> Not Available!</th>";} ?>
                                                        
                                                       </div>
                                                    </tr>
                                                 
                                                </thead>
                                               
                                            </table>

                                        </div> 
                                                
                                            </div>
                                        </div>
                                       
                                    </div>
                                </div>
                                <!-- END EXAMPLE TABLE PORTLET-->
                            </div>
                        </div>

                        <div class="row">
                          <div class="loader" style="display:none;"></div>

                          <div id="displayData"> </div>

                        </div>

                        
                    </div>
                    <!-- END CONTENT BODY -->
                </div>
                <!-- END CONTENT -->
            </div>
            <!-- END CONTAINER -->
            <script src="<?php echo base_url('backend/assets/jquery/jquery-2.1.4.min.js')?>"></script>
            <script>

            var BASE_URL = '<?php echo base_url(); ?>';
               $(document).ready(function(){

                var start= '[{';

                var end= '}]';

                var ReportUrl = '<?php echo base_url("Report_Api/report/"); ?>';

                var Tag_Type;
                var Id;
                var Source_Type;
                var reportjson;
                var Authtoken = '<?php echo $Authtoken; ?>';
                $('.icheck').on('ifChecked', function(event){
                  
                   selected = new Array();
                   var i = 0;
                   $("input[type=checkbox]:checked").each(function() {

                       i++;

                       Tag_Type =  $(this).attr('tag-type');
                       Id =  $(this).attr('tag-id');
                       Source_Type =  $(this).attr('source-type');                      

                       var stringData = '"column'+i+'" : {"Tag_Type" : "'+Tag_Type+'", "Id" : "'+Id+'", "Source_Type" : "'+Source_Type+'" }'; 
                       selected.push(stringData);
                  });
                   reportjson = start + selected + end; 


                   $.ajax({
                        type: 'POST',
                        contentType: "application/x-www-form-urlencoded; charset=UTF-8",                                    
                        url: ReportUrl,
                        data: { Authtoken:Authtoken, reportjson:reportjson },
                        dataType: 'json',
                        beforeSend: function () {
                            $('.loader').show();
                        },
                        complete: function () {
                        },
                        success: function (data) { 
                          $('.loader').hide();
                            //var codestatus = data["code"]; 
                                var htmlData = data['Payload'][0].html;
                                $('#displayData').html(htmlData);
                            }
                        
               });                  
                
                });


                $('.icheck').on('ifUnchecked', function(event){                 
                  
                  selected = new Array();
                   var i = 0;
                   $("input[type=checkbox]:checked").each(function() {
                       i++;

                         Tag_Type =  $(this).attr('tag-type');
                         Id =  $(this).attr('tag-id');
                         Source_Type =  $(this).attr('source-type');                       
                         var stringData = '"column'+i+'" : {"Tag_Type" : "'+Tag_Type+'", "Id" : "'+Id+'", "Source_Type" : "'+Source_Type+'" }'; 
                         selected.push(stringData);
                  });

                   reportjson = start + selected + end;

                    $.ajax({
                        type: 'POST',
                        contentType: "application/x-www-form-urlencoded; charset=UTF-8",                                    
                        url: ReportUrl,
                        data: { Authtoken:Authtoken, reportjson:reportjson },
                        dataType: 'json',
                        beforeSend: function () {
                            $('.loader').show();
                        },
                        complete: function () {
                        },
                        success: function (data) { 
                           $('.loader').hide();
                             
                                var htmlData = data['Payload'][0].html;
                                $('#displayData').html(htmlData);
                            }
                        
               });    

                 
                    
                });

                


               });

               
           </script>
