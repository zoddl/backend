 <!-- BEGIN FOOTER -->
            <div class="page-footer">
                <div class="page-footer-inner"> 2017 &copy; Zoddl </div>
                <div class="scroll-to-top">
                    <i class="icon-arrow-up"></i>
                </div>
            </div>
            <!-- END FOOTER -->
        </div>
       <!--[if lt IE 9]>
<script src="<?php echo base_url('backend/'); ?>assets/global/plugins/respond.min.js"></script>
<script src="<?php echo base_url('backend/'); ?>assets/global/plugins/excanvas.min.js"></script> 
<script src="<?php echo base_url('backend/'); ?>assets/global/plugins/ie8.fix.min.js"></script> 
<![endif]-->
        <!-- BEGIN CORE PLUGINS -->
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
        <!-- END CORE PLUGINS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->        
        
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/morris/morris.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/morris/raphael-min.js" type="text/javascript"></script>        
             
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL SCRIPTS -->
        <script src="<?php echo base_url('backend/'); ?>assets/global/scripts/app.min.js" type="text/javascript"></script>
       
         <script src="<?php echo base_url('backend/'); ?>assets/pages/scripts/table-datatables-ajax.min.js" type="text/javascript"></script>
        <!-- END THEME GLOBAL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL SCRIPTS -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <script src="<?php echo base_url('backend/'); ?>assets/global/scripts/datatable.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/datatables/datatables.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->
        <!-- END PAGE LEVEL SCRIPTS -->
        <!-- BEGIN THEME LAYOUT SCRIPTS -->
        <script src="<?php echo base_url('backend/'); ?>assets/pages/scripts/table-datatables-managed.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/layouts/layout/scripts/layout.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/layouts/layout/scripts/demo.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/layouts/global/scripts/quick-sidebar.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/layouts/global/scripts/quick-nav.min.js" type="text/javascript"></script>
         <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
        <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/datepicker/js/datepicker.js" type="text/javascript"></script>
         <script src="<?php echo base_url('backend/'); ?>assets/global/plugins/tag-it/js/tag-it.js" type="text/javascript"></script>
         <script type="text/javascript" src="<?php echo base_url('backend/assets/layouts/layout/scripts/');?>typeahead.js"></script>
        <script src="<?php echo base_url('backend/assets/global/plugins/icheck/icheck.min.js')?>" type="text/javascript"></script>
        <!-- END THEME LAYOUT SCRIPTS -->
        <script>
            $(document).ready(function()
            {
                $('#clickmewow').click(function()
                {
                    $('#radio1003').attr('checked', 'checked');
                });

                $('#customer').typeahead({

                    source: function (query, result) {
                        $.ajax({
                            url: "<?php echo base_url('entry/searchkeyword')?>",
                            data: 'query=' + query,            
                            dataType: "json",
                            type: "POST",
                            success: function (data) {
                                result($.map(data, function (item) {
                                    return item;
                                }));
                            }
                        });
                    }
                });

                
            })
        </script>

         <script>
       
            $(".date-picker1").datepicker({
                rtl: App.isRTL(),
                endDate: '+0d',
                autoclose: !0
            });
        
        </script>

        

        
    </body>

</html>
