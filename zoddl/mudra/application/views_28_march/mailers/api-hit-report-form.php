<!DOCTYPE html>
<html>
<body>

<h2>Send e-mail to someone@example.com:</h2>

<?php
if($this->session->flashdata('success_msg')){
	?>
		<p> <?php echo $this->session->flashdata('success_msg'); ?>	</p>

<?php }?>

<form action="<?php echo base_url('customerverification/apihitreport')?>" method="post">

E-mail:<br>
<input type="email" name="email" placeholder="antaryami@apptology.in"><br>
<input type="submit" name="submit" value="Send">
</form>

</body>
</html>