<?php 

	require_once("NavBar.php"); 

?>

<?php
	if(isset($_SESSION['ID']))
	{
		echo '<script> location.replace("index.php")</script> ';
		die("Redirecting to index.php");
	}


	$BaseUserObj = "";
	$EmailErr    = "";
	$mailResult  = "";
	$submit      = "";
	$user_email  = "";
	$user_userid = "";
	

	if(isset($_POST['ForgetButton']))
	{
		$submit = false;
		
	
		if (!empty($_POST["userid"]) and !empty($_POST["ForgetPassEmail"]))
		{
		
			$user_userid = filter_var($_POST["userid"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
			
			
			$user_email = filter_var($_POST["ForgetPassEmail"], FILTER_SANITIZE_EMAIL);
			
		
			if(filter_var($user_email, FILTER_VALIDATE_EMAIL)) 
			{
			
				$submit = true;
			}
			else { $EmailErr = "Invalid Email format"; }
		}
		elseif (empty($_POST["userid"]))          { $EmailErr = "UserID is required"; } 
		elseif (empty($_POST["ForgetPassEmail"])) { $EmailErr = "Email is required";  } 
		else { $EmailErr = "Something broke, please re-enter details"; }
		
		
		if($submit)
		{
		
			$BaseUserObj = new BaseUser("Forget Password");
			$mailResult = $BaseUserObj->ForgetPassword($user_userid, $user_email);
			if($mailResult=="SUCCESS")
			{
				echo'<style> .ForgetPassword_GUI{display:none;}</style>';
				echo "<script type='text/javascript'>alert('Please check your email to reset your password');</script>";
				echo "<script type='text/javascript'> location.replace('PasswordResetPage.php')</script> ";	
			}
			else { $EmailErr = "Error occured! Please retry!"; }
		}		
	}
	else
	{
		$_POST["userid"] = '';
		$_POST["ForgetPassEmail"] = '';
		$EmailErr = '';
	}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<style>
			.ForgetPassword_GUI {
			margin: auto;
			margin-top:2%;
			width: 30%;
			border:1px solid black;
			border-radius:10px;
			padding: 10px;
			box-shadow: 2px 4px 2px 2px gray;

			}
			#forgetbtn {
			border:none;
			background-color:purple;
			color:white;
			font-size:20px;
			border-radius:10px;
			margin-right:10px;
			}
			#forgetbtn:hover {

			outline:60%;
			filter: drop-shadow(0 0 5px purple);
			}
		</style>
	</head>
	
	<body>
		<div class="ForgetPassword_GUI">
			<form method="post" name="recoverPasswordForm">
				<h1>Password Recovery</h1>
				<p>Please key in your UserID and email address.</p>
				<p>We will send you an email to reset your password.</p>
				<label for="userid">UserID:</label><br> 
				<input id="userid" type="text" name="userid" size="20"  placeholder="JohnDoeAnderson" autocomplete="off" required><br><br>
				
				<label for="email">Email:</label><br>
				<input type="email" id="email" name="ForgetPassEmail" placeholder="abc@gmail.com" autocomplete="off" required><br><br>
				

				<input type="submit" id="forgetbtn" name="ForgetButton" value="submit" name="submit" style="float:left;"/><br><br> 
				<span class="error">&nbsp;&nbsp;<?php echo $EmailErr;?></span><br><br>
			</form>
		</div>
		

		<?php require_once 'Footer.php';?>
		<script>
			if ( window.history.replaceState ) 
			{
				window.history.replaceState( null, null, window.location.href );
			}
		</script>
	</body>
</html>

<?php require_once("Footer.php");?> 