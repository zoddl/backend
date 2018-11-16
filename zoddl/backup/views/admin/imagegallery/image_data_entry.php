

        
  <div class="page-content-wrapper">
  <!-- BEGIN CONTENT BODY -->
  <div class="page-content">
  <!-- BEGIN PAGE HEADER-->
  <!-- BEGIN THEME PANEL -->

  <!-- END THEME PANEL -->
  <!-- BEGIN PAGE TITLE-->
  <h1 class="page-title">Image Data Entry </h1>
  <!-- BEGIN PAGE BAR -->
  <div class="page-bar">
  <ul class="page-breadcrumb">
  <li>
      <a href="<?php echo base_url();?>">Home</a>
      <i class="fa fa-circle"></i>
  </li>

  <li>
      <span>Image Data Entry</span>
  </li>
  </ul>

  </div>

  <div class="row">
  <div class="col-md-12">
  <!-- BEGIN EXAMPLE TABLE PORTLET-->
  <div class="portlet light bordered">
      
      <div class="portlet-body">
          <div class="table-toolbar">
              </div>
                  <div class="tab-pane" id="tab_2">
                      <div class="row">

                          <div class="col-md-7 col-sm-12 responsive-1024">
                              <h4>Image</h4>
                              <br/>
                              <?php

                                  if($tagData->Image_Url_Thumb)
                                  {
                                    $imgUrl = $tagData->Image_Url_Thumb;
                                  }
                                  else
                                  {
                                    $imgUrl = base_url('backend/assets/global/plugins/images/noimage.jpeg');
                                  }
                              ?>

                              <?php 

                                  $uri_segment = $this->uri->segment('3');  
                                  if($uri_segment)
                                  {
                                    $next_idData = $this->Gallery_model->get_tag_nxt_id($uri_segment, $customerId); 
                                    $previous_idData = $this->Gallery_model->get_tag_previous_id($uri_segment);

                                      if($next_idData)
                                      {
                                         if($customerId != '')
                                          {

                                            $nextId = base_url('Imagegallery/image_data_entry/'.$next_idData[0]->Id.'/'.$customerId);
                                          }
                                          else
                                          {

                                            $nextId = base_url('Imagegallery/image_data_entry/'.$next_idData[0]->Id);

                                          }
                                      }
                                      else
                                      {
                                         $nextId = '#';
                                      }

                                      if($previous_idData)
                                      {
                                         $previousId = base_url('Imagegallery/image_data_entry/'.$previous_idData[0]->Id);
                                      }
                                      else
                                      {
                                         $previousId = '#';
                                      }
                                  }
                              ?>
                              <!--<a href="<?php //echo $previousId; ?>" class="previous round">Previous&#8249;</a>-->
                              
                              <img src="<?php echo $imgUrl ; ?>" id="demo2" alt="Jcrop Example" style = "width: 500px; height: 400px;" class="img-responsive" /> 

                          </div>
                          <div class="col-md-5 col-sm-12 responsive-1024">
                              
                           <h3 align="right"><b><a href="<?php echo $nextId; ?>" id="buttonNext" class="btn btn-success" style = "text-decoration: none;color: light green;cursor: pointer;" >Next</a></b></h3>
                            
                              <input type="radio" id="radio1" name="radios" value="all" checked>
                             <label for="radio1">Single Entry</label>

                              <input type="radio" id="radio2" name="radios"value="false">
                             <label for="radio3">Multiple Entry</label>
                             

                              <form id="single_entry" class="coords form-inline">
                                <div id="ajax"><?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');}?></div>
                                  <table class="table table-bordered">
                                      <tr>
                                          <td>
                                              <label class="control-label">E.Type</label>
                                          </td>
                                          <td>
                                             <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="etypesingle" class="form-control btn-sm" value = "<?php echo $tagData->Tag_Type; ?>" />
                                              </div>
                                              </div> 
                                            </td>
                                          
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">Date</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" id = "date" name="datesingle" class="form-control btn-sm" value = "<?php echo $tagData->Tag_Send_Date; ?>" /> 
                                            </div>
                                          </div>
                                            </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">Amount</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="amountsingle" class="form-control btn-sm" value = "<?php echo $tagData->Amount; ?>" /> 
                                            </div>
                                          </div>
                                            </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">P.Tag</label>
                                          </td>
                                          <td>


                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input id = "" type="text" name="ptagsingle" class="form-control btn-sm pptagg" 
                                              value = "<?php $ptagName =  $this->Gallery_model->get_record_by_id('tbl_primary_tag',array('Id'=>$tagData->Primary_Tag));
                                                        if($ptagName) echo $ptagName->Prime_Name ; else echo 'Untagged'; ?>" />
                                                </div>
                                              </div>
                                          </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">S.Tag</label>
                                          </td>
                                          <td>
                                            <div class="form-group tag-input">

                                             

                                               <div class="input-icon tag-select">
                                             <input type="tags" id = "mySingleFieldTags"  name="stagsingle" class="form-control btn-sm sstagg" data-role="tagsinput"
                                              value = "<?php 
                                                        if($finalsecondory_tagsArray) echo $finalsecondory_tagsArray ; else echo 'Untagged'; 
                                                        ?>" required />
                                              
                                              </div> 
                                            </div>
                                          </td>
                                      </tr>

                                      <tr>
                                          <td>
                                              <label class="control-label">Description</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="descriptionsingle" class="form-control btn-sm" value = "<?php echo $tagData->Description; ?>" /> 
                                            </div>
                                          </div>
                                        </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">Sub Description</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="subdescriptionsingle" class="form-control btn-sm" value = "<?php echo $tagData->Sub_Description; ?>" /> 
                                                <div>
                                              </div>
                                            </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">CGST</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="cgstsingle" class="form-control btn-sm" value = "<?php echo $tagData->CGST; ?>" /> 
                                            </div>
                                          </div>
                                        </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">SGST</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="sgstsingle" class="form-control btn-sm" value = "<?php echo $tagData->SGST; ?>" /> 
                                            </div>
                                          </div>
                                        </td>
                                      </tr>
                                         <tr>
                                          <td>
                                              <label class="control-label">IGST</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="igstsingle" class="form-control btn-sm" value = "<?php echo $tagData->IGST; ?>" /> 
                                            </div>
                                          </div>
                                        </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <label class="control-label">Invoice/Cheque No.</label>
                                          </td>
                                          <td>
                                             <div class="form-group">
                                              <div class="input-icon">
                                              <input type="text" name="invoice_nosingle" class="form-control btn-sm" value = "<?php echo $tagData->Invoice_Check_No; ?>" /> 
                                              </div>
                                            </div>
                                          </td>
                                      </tr>
                                       <tr>
                                          <td>
                                              <label class="control-label">Status</label>
                                          </td>
                                          <td>
                                            <div class="form-group">
                                              <div class="input-icon">
                                                <select id="single" name = "statussingle" class="form-control select2">
                                                <option value = "" >Select Status</option>
                                                <option value="1" <?php if($tagData->Tag_Status == '1') { ?> selected="selected"<?php } ?>>Approved</option>
                                                <option value="2" <?php if($tagData->Tag_Status == '2') { ?> selected="selected"<?php } ?>>Pending</option>
                                                <option value="3" <?php if($tagData->Tag_Status == '3') { ?> selected="selected"<?php } ?>>Decline</option>
                                                <option value="4" <?php if($tagData->Tag_Status == '4') { ?> selected="selected"<?php } ?>>Decline Blur</option>                        
                                              </select>
                                            </div>
                                          </div>
                                         </td>
                                      </tr>

                                  </table>
                                <div class="col-md-1 " >
                                  <button type="submit" id= "submitsingleentry" class="btn btn-success" name="submit">Save</button>
                                </div>
                              </form>
                            </div>
                          </div>
                        </div><!--tabpane clos-->
                      </div><!--portal body close-->
                    </div>
                  </div>
                </div>

                <div class="row" style="display: none;" >
                  <div class="col-md-12">
                      <!-- BEGIN EXAMPLE TABLE PORTLET-->
                      <div class="portlet-body ">

                          <form id="multiple_entry">                       
                              <div class="col-sm-12 control-label more_fields">                         
                                                               
                                <div class="col-md-12 clearfix form-group">
                                 
                                  <div class="col-md-3">
                                    <label class="">E.Type</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "etype[]" class = "form-control" Placeholder="E.Type">
                                      </div>
                                    </div>
                                  </div>

                                  
                                  <div class="col-md-3">
                                    <label class="">Status</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                        <select id="single" name = "status[]" class="form-control select2">
                                         <option value = "" >Select Status</option>
                                          <option value="1">Approved</option>
                                          <option value="2">Pending</option>
                                          <option value="3">Decline</option> 
                                          <option value="4">Decline Blur</option>                        
                                      </select>
                                      
                                    </div>
                                  </div>
                                  </div>

                                 
                                  <div class="col-md-3">
                                    <label class="">Date</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" id = "date2" name = "date[]" class = "form-control" placeholder="Date">
                                    </div>
                                  </div>
                                  </div>

                                  
                                  <div class="col-md-3">
                                    <label class="">Amount</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "amount[]" class = "form-control" placeholder="Amount">
                                    </div>
                                  </div>
                                  </div>

                                </div>

                                 <div class="col-md-12 clearfix form-group">
                                 
                                  <div class="col-md-3">
                                    <label class="">P.Tag</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "ptag[]" class = "form-control pptagg" Placeholder="P.Tag">
                                    </div>
                                  </div>
                                  </div>

                                  
                                  <div class="col-md-3">
                                    <label class="">S.Tag</label>
                                    <div class="form-group tag-input">
                                      <div class="input-icon">
                                      <input id = "myFieldTags" type="tags" name = "stag[]" class = "form-control sstagg" placeholder="S.Tag" style="display:block" data-role="tagsinput" required>
                                    </div>
                                  </div>
                                  </div>

                                 
                                  <div class="col-md-3">
                                    <label class="">Description</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "description[]" class = "form-control" placeholder="Description">
                                    </div>
                                  </div>
                                  </div>

                                  
                                  <div class="col-md-3">
                                    <label class="">Sub Description</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "subdescription[]" class = "form-control" placeholder="Sub Description">
                                    </div>
                                  </div>
                                  </div>

                                </div>

                                <div class="col-md-12 clearfix form-group">
                                 
                                  <div class="col-md-3">
                                    <div class="form-group">
                                      <div class="input-icon">
                                    <label class="">CGST</label>
                                      <input type="text" name = "cgst[]" class = "form-control" Placeholder="CGST">
                                    </div>
                                  </div>
                                  </div>

                                  
                                  <div class="col-md-3">
                                    <label class="">SGST</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "sgst[]" class = "form-control" placeholder="SGST">
                                    </div>
                                  </div>
                                  </div>

                                 
                                  <div class="col-md-3">
                                    <label class="">IGST</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "igst[]" class = "form-control" placeholder="IGST">
                                    </div>
                                  </div>
                                  </div>

                                  
                                  <div class="col-md-3">
                                    <label class="">Invoice/Cheque No</label>
                                    <div class="form-group">
                                      <div class="input-icon">
                                      <input type="text" name = "invoice_no[]" class = "form-control" placeholder="Invoice/Cheque No">
                                    </div>
                                  </div>
                                  </div>
                                  <hr/><hr/><hr/><hr/><hr/>
                                </div>  
                                
                           </div><!--ends class div-->
                           <div class="clearfix" >
                            <div class="col-md-1 " >
                                  <button type="button" id="add_more_fields" class="btn btn-primary"><i class="fa fa-plus-circle"></i>Add More</button>
                          </div>
                            <div class="col-md-1 " ></div>
                            <button type="submit" id= "submitmultipleentry" class="btn btn-success" name="submit">Save</button>

                        </form>
              </div><!--portal body end div-->
          </div>
        </div><!--first-->                        
      </div><!--page content-->
</div><!--page content wrapper -->
<!-- END CONTAINER -->
 <style>
.tag-input input{
  position: relative;
  
  border: none;
  
  border: none;
  background: transparent;

}
.tag-input .tagit{
  position: relative;
  margin-top: -30px;
}
</style>
<?php // BEGIN CORE PLUGINS ?>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
      <?php // END CORE PLUGINS ?>
        <?php //  BEGIN PAGE LEVEL PLUGINS ?>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/jquery.validate.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/datepicker/js/datepicker.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/tag-it/js/tag-it.js" type="text/javascript"></script>
      

  <script>
    $("#radio1").on("click", function(){
    $("#single_entry").show();
    $("#multiple_entry").hide();
});

$("#radio2").on("click", function(){
  
   $(".row").removeAttr("style")
   $("#single_entry").hide();
   $("#multiple_entry").show(); 
});

/**************************JS for add more dynamic fields*****************/
 $(document).ready(function(){

    /****function for load ptag********/
     function loadPtagm (){
            //Gets the name of all Ptag
            $.ajax({
                    url:  "<?php echo base_url('Imagegallery/load_allPtag/'.$tagData->Customer_Id); ?>",
                    type: "POST",
                    async: false,
                    dataType: 'json'
                   
             }).done(function(result){
                resultList = result.tagss;
             });
            //Returns the javascript array

          return resultList;
        }//ends ptag function

        /******function for load stag******/
      function loadStagm (){
          //Gets the name of all Stag
          $.ajax({
                  url:  "<?php echo base_url('Imagegallery/load_allStag/'.$tagData->Customer_Id); ?>",
                  type: "POST",
                  async: false,
                  dataType: 'json'
                 
           }).done(function(result){
              resultList = result.tagss;
           });
        return resultList;
       }//ends stag function

        $( ".pptaaggss" ).autocomplete("destroy");
        $( ".sstaaggss" ).autocomplete("destroy");
        var pptaaggss =loadPtagm();
        var sstaaggss =loadStagm(); 
        var allsstaaggss =loadStagm(); 
        var max_fields = 10; //maximum input boxes allowed
        var addproject = $(".more_fields"); 
        var add_button = $("#add_more_fields");
        var x = 0;

        $(add_button).click(function(e){
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                        x++; //text box increment
                        $(addproject).append('<hr/><div class="clearfix" id="more_fields_add-'+x+'"><div class="col-md-12 clearfix form-group"><div class="col-md-3"><label class="">E.Type</label><div class="form-group"><div class="input-icon"><input type="text" name = "etype[]" class = "form-control" Placeholder="E.Type"></div></div></div><div class="col-md-3"><label class="">Status</label><div class="form-group"><div class="input-icon"><select id="single" name = "status[]" class="form-control select2"><option value = "" >Select Status</option><option value="1">Approved</option><option value="2">Pending</option><option value="3">Decline</option><option value="4">Decline Blur</option></select></div></div></div><div class="col-md-3"><label class="">Date</label><div class="form-group"><div class="input-icon"><input type="text" name = "date[]" id ="date3_'+x+'" class = "form-control" placeholder="Date"></div></div></div><div class="col-md-3"><label class="">Amount</label><div class="form-group"><div class="input-icon"><input type="text" name = "amount[]" class = "form-control" placeholder="Amount"></div></div></div></div><div class="col-md-12 clearfix form-group"><div class="col-md-3"><label class="">P.Tag</label><div class="form-group"><div class="input-icon"><input type="text" name = "ptag[]" class = "form-control pptaaggss" Placeholder="P.Tag"></div></div></div><div class="col-md-3"><label class="">S.Tag</label><div class="form-group tag-input"><div class="input-icon"><input type="tags" id = "stttaaggg'+x+'" name = "stag[]" class = "form-control sstaaggss" placeholder="S.Tag" style="display:block" data-role="tagsinput" required></div></div></div><div class="col-md-3"><label class="">Description</label><div class="form-group"><div class="input-icon"><input type="text" name = "description[]" class = "form-control" placeholder="Description"></div></div></div><div class="col-md-3"><label class="">Sub Description</label><div class="form-group"><div class="input-icon"><input type="text" name = "subdescription[]" class = "form-control" placeholder="Sub Description"></div></div></div></div><div class="col-md-12 clearfix form-group"><div class="col-md-3"><label class="">CGST</label><div class="form-group"><div class="input-icon"><input type="text" name = "cgst[]" class = "form-control" placeholder="CGST"></div></div></div><div class="col-md-3"><label class="">SGST</label><div class="form-group"><div class="input-icon"><input type="text" name = "sgst[]" class = "form-control" placeholder="SGST"></div></div></div><div class="col-md-3"><label class="">IGST</label><div class="form-group"><div class="input-icon"><input type="text" name = "igst[]" class = "form-control" placeholder="IGST"></div></div></div><div class="col-md-3"><label class="">Invoice/Cheque No</label><div class="form-group"><div class="input-icon"><input type="text" name = "invoice_no[]" class = "form-control" placeholder="Invoice/Cheque No"></div></div></div></div><div class="control-label col-md-1 col-sm-1 col-xs-1"><a value = "'+x+'" href="" class="btn btn-danger remove_field">Remove</a><br/></div><br/></div><br/><hr/>'); //add input box
                        $("#date3_"+x).datepicker({dateFormat: 'yy-dd-mm', endDate: new Date()});
                        $(".pptaaggss").autocomplete({
                            source: pptaaggss,
                            autoFocus:true
                        });
                        $(".sstaaggss").autocomplete({
                            source: sstaaggss,
                            autoFocus:true
                        });
                        $("#stttaaggg"+x).tagit({
                          availableTags: allsstaaggss
                        });

                } else {
                    alert('Only 10 additional data allowed');
                }
        });
                 
        $(addproject).on("click",".remove_field", function(e){ //user click on remove text
            var id = $(this).attr('value');
                e.preventDefault(); 
                $("#more_fields_add-"+id).remove();
                $(this).remove();
                x--;
        });

        $("body").keydown(function(e){
         
          // right arrow
          if ((e.keyCode || e.which) == 39)
          {
                var go_to_url = $("#buttonNext").attr('href');
                document.location.href = go_to_url;

          }   
      });

    });

 /*************************ends JS for dynamic fields***************************/
  </script>

  <script>
  /***************JS for autocomplete Ptag********************/
  var pptagg =loadPtag(); 
  
  function loadPtag (){
    //Gets the name of all Ptag
    $.ajax({
            url:  "<?php echo base_url('Imagegallery/load_allPtag/'.$tagData->Customer_Id); ?>",
            type: "POST",
            async: false,
            dataType: 'json'
           
     }).done(function(result){
        resultList = result.tagss;
     });
    //Returns the javascript array

  return resultList;
}
/**********Autocomplee code************/
   $(".pptagg").autocomplete({
        source: function( request, response ) {
            var obj = {};
            var i = 0;
            var matches = $.map( pptagg, function(data) {
                var position = data.toLowerCase().indexOf(request.term.toLowerCase());
                if(position >= 0){
                    return {'name':data, 'position': position};
                }
            });

            matches.sort(function(a,b) {return (a.position > b.position) ? 1 : ((b.position > a.position) ? -1 : 0);} ); 

            var finalMatches = $.map( matches, function(data) {
                return data.name;
            });                
            response(finalMatches);
        }
    });

/***************Ends JS for autocomplete Ptag********************/
  </script>

   <script>
  /***************JS for autocomplete Stag*************************/
  var sstagg =loadStag(); 
  function loadStag (){
    //Gets the name of all Stag
    $.ajax({
            url:  "<?php echo base_url('Imagegallery/load_allStag/'.$tagData->Customer_Id); ?>",
            type: "POST",
            async: false,
            dataType: 'json'
           
     }).done(function(result){
        resultList = result.tagss;
     });
  return resultList;
}

var allSecondryTag = loadStag();
    
    $("#mySingleFieldTags").tagit({
        availableTags: allSecondryTag
      });

    $("#myFieldTags").tagit({
        availableTags: allSecondryTag
      });

/**********Autocomplete code*********/
    $(".sstagg").autocomplete({
        source: function( request, response ) {
            var obj = {};
            var i = 0;
            var matches = $.map(sstagg, function(data) {
                var position = data.toLowerCase().indexOf(request.term.toLowerCase());
                if(position >= 0){      
                    return {'name':data, 'position': position};
                }
            });

            matches.sort(function(a,b) {return (a.position > b.position) ? 1 : ((b.position > a.position) ? -1 : 0);} ); 

            var finalMatches = $.map( matches, function(data) {
                return data.name;
            });   
               
            response(finalMatches);
        }
    });
  /***************Ends JS for autocomplete Stag********************/

 

  </script>


<!--JS validation for Single form field submit-->
<script>

    $("#single_entry").validate({
                errorElement: "span",
                errorClass: "help-block",
                focusInvalid: !1,
                rules: {
                   /* etypesingle: {
                        required: !0
                       
                    },
                    datesingle: {
                        required: !0
                    },
                    amountsingle: {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    ptagsingle: {
                        required: !0    
                    },
                    stagsingle: {
                        required: !0   
                    },
                    descriptionsingle: {
                        required: !0
                    },
                    subdescriptionsingle: {
                        required: !0
                    },
                    cgstsingle: {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    sgstsingle: {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    igstsingle: {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    invoice_nosingle: {
                        required: !0,
                        maxlength:11
                    },
                    statussingle: {
                        required: !0
                    }, */
                },
                messages: {

                  /* etypesingle: {
                        required: "Etype is required."
                    },
                    datesingle: {
                        required: "Date is required."
                    },
                    amountsingle: {
                        required: "Amount is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    ptagsingle: {
                        required: "P.Tag is required."
                    },
                    stagsingle: {
                        required: "S.Tag is required."
                    },
                    descriptionsingle: {
                        required: "Description is required."
                    },
                    subdescriptionsingle: {
                        required: "Sub Description is required."
                    },
                    cgstsingle: {
                        required: "CGST is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    sgstsingle: {
                        required: "SGST is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    igstsingle: {
                        required: "IGST is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    invoice_nosingle: {
                        required: "Invoice/Cheque No is required.",
                        maxlength: "Please do not enter more than 11 words"
                    },
                    statussingle: {
                        required: "Status is required."
                    } */
                    
                   
                },
                invalidHandler: function(e, r) {                    
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error"), e.remove()
                },
                errorPlacement: function(e, r) {
                    e.insertAfter(r.closest(".input-icon"))
                },
                submitHandler: function(e) {
                 //var url_string = window.location.pathname.split("/").slice(-2)[0];; //window.location.href;
                 <?php $url_string = $this->uri->segment('3');?>
      $.ajax({
                cache: false,
                type: "POST",
                url: "<?php echo base_url('Imagegallery/single_entry/').$url_string; ?>",
                data: $("#single_entry").serialize(),
                beforeSend : function(msg){ $("#submitsingleentry").html('<img src="<?php echo base_url('backend/assets/global/img/loading.gif'); ?>" />'); },
                success: function(msg)
                {
                    $('body,html').animate({ scrollTop: 0 }, 200);
                    if(msg.substring(1,7) != 'script')
                    {
                        $("#ajax").html(msg); 
                        $("#submitsingleentry").html('<button type="submit" id="submitsingleentry" class="btn green pull-right"> Save </button>');
                    }
                    else
                    { 
                        $("#ajax").html(msg); 
                    }
                }

               });
                    
                }
            }), $("#single_entry input").keypress(function(e) {
                if (13 == e.which) return $("#single_entry").validate().form() && $("#single_entry").submit(), !1
            });
    </script>

  <!--Ends JS validation for single form fields submit--> 

  <!--JS validation for multiple form fields submit-->
<script>
    
    $("#multiple_entry").validate({
                errorElement: "span",
                errorClass: "help-block",
                focusInvalid: !1,
                rules: {
                   'etype[]': {
                        required: !0
                       
                    },
                    'date[]': {
                        required: !0
                    },
                    'amount[]': {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    'ptag[]': {
                        required: !0
                    },
                    'stag[]': {
                        required: !0     
                    },
                    'description[]': {
                        required: !0
                    },
                    'subdescription[]': {
                        required: !0
                    },
                    'cgst[]': {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    'sgst[]': {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    'igst[]': {
                        required: !0,
                        number  : true,
                        maxlength:11
                    },
                    'invoice_no[]': {
                        required: !0,
                        maxlength:11
                    },
                    'status[]': {
                        required: !0
                    },
                },  
                messages: {

                    'etype[]': {
                          required: "Etype is required."
                      },
                    'date[]': {
                        required: "Date is required."
                    },
                    'amount[]': {
                        required: "Amount is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    'ptag[]': {
                        required: "P.Tag is required."         
                    },
                    'stag[]': {
                        required: "S.Tag is required."
                    },
                    'description[]': {
                        required: "Description is required."
                    },
                    'subdescription[]': {
                        required: "Sub Description is required."
                    },
                    'cgst[]': {
                        required: "CGST is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    'sgst[]': {
                        required: "SGST is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    'igst[]': {
                        required: "IGST is required.",
                        number  : "Please enter number only",
                        maxlength: "Please do not enter more than 11 digits"
                    },
                    'invoice_no[]': {
                        required: "Invoice/Cheque No is required.",
                        maxlength: "Please do not enter more than 11 words"
                    },
                    'status[]': {
                        required: "Status is required."
                    }
                    
                   
                },
                invalidHandler: function(e, r) {                    
                },
                highlight: function(e) {
                    $(e).closest(".form-group").addClass("has-error")
                },
                success: function(e) {
                    e.closest(".form-group").removeClass("has-error"), e.remove()
                },
                errorPlacement: function(e, r) {
                    e.insertAfter(r.closest(".input-icon"))
                },
                submitHandler: function(e) {
                  <?php $url_string = $this->uri->segment('3');?>
      $.ajax({
                cache: false,
                type: "POST",
                url: "<?php echo base_url('Imagegallery/multiple_entry/').$url_string; ?>",
                data: $("#multiple_entry").serialize(),
                beforeSend : function(msg){ $("#submitmultipleentry").html('<img src="<?php echo base_url('backend/assets/global/img/loading.gif'); ?>" />'); },
                success: function(msg)
                {
                    $('body,html').animate({ scrollTop: 0 }, 200);
                    if(msg.substring(1,7) != 'script')
                    {
                        $("#ajax").html(msg); 
                        $("#submitmultipleentry").html('<button type="submit" id="submitmultipleentry" class="btn green pull-right"> Save </button>');
                    }
                    else
                    { 
                        $("#ajax").html(msg); 
                    }
                }

               });
                    
                }
            }), $("#multiple_entry input").keypress(function(e) {
                if (13 == e.which) return $("#multiple_entry").validate().form() && $("#multiple_entry").submit(), !1
            });
    </script>
  <!--Ends JS validation for multiple form fields submit-->

  <!--Date JS-->
  <script>

  $("#date").datepicker({
    dateFormat: 'yy-dd-mm',
    endDate: new Date()
  });

   $("#date2").datepicker({
    dateFormat: 'yy-dd-mm',
    endDate: new Date()
  });




  </script>
  <!--Ends Date JS-->

  
