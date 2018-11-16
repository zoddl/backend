<div class="page-content-wrapper">
<!-- BEGIN CONTENT BODY -->
<div class="page-content">
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN THEME PANEL -->

    <!-- END THEME PANEL -->
    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> Notification Management </h1>
    <!-- BEGIN PAGE BAR -->
    <div class="page-bar">
        <ul class="page-breadcrumb">
            <li>
                <a href="<?php echo base_url();?>">Home</a>
                <i class="fa fa-circle"></i>
            </li>
            <li>
                <span>Notification</span>
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
            <form action="<?php echo base_url();?>notification/send" name="notifyForm" id="notifyForm" method="post">
                <div class="form-group">
                    <p class='flashMsg forgetSuccess' style="color:green;">
                        <?php echo $this->session->flashdata('flashNotifySuccess'); ?>
                    </p>
                </div>
                <div class="form-group">
                    <label for="notifyTitle">Title</label>
                    <input type="text" name="notifyTitle" id="notifyTitle" class="form-control" aria-describedby="notifyTitleHelp" placeholder="Enter your notification title" maxlength="30" minlength="5">
                    <small id="notifyTitleHelp" class="form-text text-muted"><?php  echo form_error('notifyTitle'); ?></small>
                </div>
                <div class="form-group">
                    <label for="messageTextarea">Message</label>
                    <textarea name="messageTextarea" id="messageTextarea" class="form-control" aria-describedby="notifyMessageHelp" rows="3" maxlength="100" minlength="5" placeholder="Enter your notification message"></textarea>
                    <small id="notifyMessageHelp" class="form-text text-muted"><?php  echo form_error('messageTextarea'); ?></small>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
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

<script src="<?php echo base_url('backend/assets/jquery/jquery-2.1.4.min.js')?>"></script> 

<script type="text/javascript">
$(document).ready(function(){
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
            "url": "<?php echo base_url('primarytag/primarytagdata')?>",
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