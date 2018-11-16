<?php
$textfieldsearch = '';
$selectsearch = '';
if($searchkeyword != '')
{

   if(!is_numeric($searchkeyword))
   {

       $textfieldsearch = $searchkeyword;
   }
   else
   {

       $selectsearch = $searchkeyword;
   }

}
else
{

   $searchkeyword == ''; 
}

?>
<div class="col-lg-4">
                                                    <div class="search-filter ">
                                                        <div class="search-label uppercase">Search By</div>
                                                        <div class="input-icon right">
                                                            <i class="icon-magnifier" id="searchbytext" > </i>
                                                            <input  type="text" class="form-control" placeholder="Name/Email/Phone" id="searchkeyword" value="<?php echo $textfieldsearch ?>"> </div>
                                                        
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="search-filter ">
                                                      
                                                        <div class="search-label uppercase">Filter By Status</div>
                                                        <select class="form-control" name="userstatus" id="userstatus">
                                                             <option value="">-------Choose One---------</option>
                                                            <option value="1" <?php if($selectsearch == 1 ) { echo "selected";}?>>Active</option>
                                                            <option value="2" <?php if($selectsearch == 2) { echo "selected";}?> >Inactive</option>
                                                            <option value="3" <?php if($selectsearch == 3) { echo "selected";}?>>Verified</option>
                                                            <option value="4" <?php if($selectsearch == 4 ) { echo "selected";}?>>Unverified</option>                                                            
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="search-filter ">
                                                        <?php
                                                            $uri = $this->uri->segment('1');    
                                                        ?>
                                                        <div class="search-label uppercase">Export Data</div>
                                                            
                                                            <?php
                                                                if($uri=='staffcustomer'){
                                                            ?>
                                                           <a href = "<?php echo base_url('Exportcsv/export_csvStaff/')?>" type="submit" class="btn btn-success mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Export CSV</span>
                                                                <span class="ladda-spinner"></span>
                                                            </a>

                                                            <a href = "<?php echo base_url('Exportcsv/export_excelStaff/')?>" type="submit" class="btn blue mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Export XLS</span>
                                                                <span class="ladda-spinner"></span>
                                                            </a>

                                                            <button type="button" class="btn red mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Delete</span>
                                                                <span class="ladda-spinner"></span>
                                                            </button>
                                                       <?php } ?>

                                                       <?php
                                                                if($uri=='appcustomer'){
                                                            ?>
                                                           <a href = "<?php echo base_url('Exportcsv/export_csvGeneral/')?>" type="submit" class="btn btn-success mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Export CSV</span>
                                                                <span class="ladda-spinner"></span>
                                                            </a>

                                                            <a href = "<?php echo base_url('Exportcsv/export_excelGeneral/')?>" type="submit" class="btn blue mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Export XLS</span>
                                                                <span class="ladda-spinner"></span>
                                                            </a>

                                                            <button type="button" class="btn red mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Delete</span>
                                                                <span class="ladda-spinner"></span>
                                                            </button>
                                                       <?php } ?>

                                                       <?php
                                                                if($uri=='companycustomer'){
                                                            ?>
                                                           <a href = "<?php echo base_url('Exportcsv/export_csvCompany/')?>" type="submit" class="btn btn-success mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Export CSV</span>
                                                                <span class="ladda-spinner"></span>
                                                            </a>

                                                            <a href = "<?php echo base_url('Exportcsv/export_excelCompany/')?>" type="submit" class="btn blue mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Export XLS</span>
                                                                <span class="ladda-spinner"></span>
                                                            </a>

                                                            <button type="button" class="btn red mt-ladda-btn ladda-button btn-circle" data-style="expand-right">
                                                                    <span class="ladda-label">Delete</span>
                                                                <span class="ladda-spinner"></span>
                                                            </button>
                                                       <?php } ?>
                                                    
                                                    </div>
                                                </div>

