<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="">
		<style>
		 .form_hint{background-color:#2ed42b; color:#fff; padding:5px 12px; }
		</style>
	</head>
	<!-- NAVBAR
		================================================== -->
	<body>
		<div class="main container" style="width:100%;">

		<div class="msg-content col-md-12 pull-left">
			<p style="font-family:Arial; font-size:16px;">Hi Admin,</p>
			<div class="text-center">
			
					<h2 style="font-family:Arial; font-size:14px;">User Sign Up Summary  : </h2>
					<table>
						 <tr>
						 	<td><strong>Email: </strong> <?php echo $useremail; ?></td>
						 	
						 </tr>
						 
						 <tr>
							<td><strong>Sign Up Date: </strong> <?php echo $registrationdate; ?></td>

						 </tr>
					</table> 
		 <p style="font-family:Arial; font-size:14px;">Thank you</p>
			</div>
		</div>		 
	</body>
</html>