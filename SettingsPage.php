<?php require_once("NavBar.php");

if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}



?>
<style>
span{
width:200px;
color:red;
}
.EditProfileForm * {
vertical-align: middle;
}

.centerBox {
margin: auto;
width: 40%;
/* border: 3px solid #73AD21; */
padding: 10px;
}

label, input, textarea {	
display: inline-block; /* In order to define widths */
}

label {
width: 30%;
text-align: right;    /* Positions the label text beside the input */
}

label+input+textarea {
width: 30%;

/* Large margin-right to force the next element to the new-line
and margin-left to create a gutter between the label and input */

/* Margin: 0% for top, 30% for right, 0% for bottom, 4% for left */
margin: 0 30% 0 4%;
}
#innercontainer1 button {
border:none;
background-color:purple;
color:white;
font-size:20px;
border:1px solid white;
height:100px;
width:100%
}
#innercontainer1 button:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
#container{
width:1400px;
height:700px;
border:1px solid black;
border-radius: 10px;
overflow:hidden;
box-shadow:5px 5px gray;
margin:auto;
margin-top:2%;

}
#innercontainer1{
width:20%;
height:100%;
float:left;	
border:1px solid black;
}

#innercontainer2{

width:80%;
height:100%;
overflow:scroll;
float:right;
display:inline-block;
background-color:white;	
}
#innercontainer2::-webkit-scrollbar {
width: 0.2em;

}

#innercontainer2:-webkit-scrollbar-track {
-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}

#innercontainer2::-webkit-scrollbar-thumb {
background-color: black;
outline: 1px solid black;

}
#donebtn {
border:none;
background-color:purple;
color:white;
font-size:20px;
border-radius:10px;
margin-right:10px;
}
#donebtn:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
#confirmation{
display:none;
position:fixed;
padding:0;
margin:auto;
top:0;
left:0;

width: 100%;
height: 100%;
background:rgba(255,255,255,0.8);
}
#confirmationtext{
width:200px;
margin:auto;
margin-top:20%;

}

#OTP{
display:none;
position:fixed;
padding:0;
margin:auto;
top:0;
left:0;

width: 100%;
height: 100%;
background:rgba(255,255,255,0.8);
}
#OTPform{
width:200px;
margin:auto;
margin-top:20%;

}
#loadergui {
width:300px;
margin-left:auto;
margin-right:auto;
margin-top:200px;
display:none;
}
#loader {
position: absolute;
border: 16px solid grey;
border-radius: 50%;
border-top: 16px solid purple;
width: 300px;
height: 300px;


-webkit-animation: spin 2s linear infinite; /* Safari */
animation: spin 2s linear infinite;
}
#loaderimage {
margin-left:14px;
margin-top:15px;
border-radius: 50%;
position: absolute;
background-image: url("systemimages/Logo.jpg");
width:270px;
height:270px;
background-repeat: no-repeat;
background-size: auto;
background-size: 270px 270px;
}
/* Safari */
@-webkit-keyframes spin {
0% { -webkit-transform: rotate(0deg); }
100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
0% { transform: rotate(0deg); }
100% { transform: rotate(360deg); }
}
.OTPbtns{
margin-top:20px;
border:none;
background-color:purple;
color:white;
font-size:20px;
border-radius:10px;
margin-right:10px;
}
.OTPbtns:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);
}
</style>
<div id="container">
<div id="innercontainer1">

<button class="EditProfile">Edit Profile</button></br>
<button class="ChangePassword">Change Password</button></br>
<button class="ChangeEmail">Change Email</button>
</div>
<div id="innercontainer2">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $(".EditProfile").click(function(){
    $(".EditProfile_GUI").show();
	$(".ChangePassword_GUI").hide();
	$(".ChangeEmail_GUI").hide();
  });
  $(".ChangePassword").click(function(){
    $(".EditProfile_GUI").hide();
	$(".ChangePassword_GUI").show();
		$(".ChangeEmail_GUI").hide();
  });
   $(".ChangeEmail").click(function(){
    $(".EditProfile_GUI").hide();
	$(".ChangePassword_GUI").hide();
		$(".ChangeEmail_GUI").show();
  });

});
</script>

<?php 
$submit = true;
$EditProfileFirstNameError = $EditProfileContactError = $EditProfileLastNameError  =  $EditProfileAddressError = $EditProfileDisplayNameError =  $EditProfileFileErr = ""; 

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['EditProfileButton'])){
	$EditProfile  = true;
	if(empty($_POST["EditProfileFirstName"]))
	{
		$EditProfileFirstNameError = "First name is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[a-zA-Z]*$/", $_POST["EditProfileFirstName"])){
			$EditProfileFirstNameError = "First name is invalid";
			$submit = false;
		}
	}
	if(empty($_POST["EditProfileLastName"]))
	{
		$EditProfileLastNameError = "Last name is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[a-zA-Z]*$/", $_POST["EditProfileLastName"])){
			$EditProfileLastNameError = "Last name is invalid";
			$submit = false;
		}
	}
	
	if(empty($_POST["EditProfileDisplayName"]))
	{
		$EditProfileDisplayNameError = "Display name is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[A-Za-z0-9]{8,20}$/i",  $_POST["EditProfileDisplayName"])){
			$submit = false;
			$EditProfileDisplayNameError = "Invalid Display Name ,requires at least 8 characters and 20 characters at maximum";
		}
	}
	if(empty($_POST["EditProfileAddress"]))
	{
		$EditProfileAddressError = "Address is required";
		$submit = false;
	}
	
	
	if(empty($_POST["EditProfileContact"]))
	{
		$EditProfileContactError = "Phone Number is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[0-9]{8}$/",$_POST["EditProfileContact"])) {
			$submit = false;
			$EditProfileContactError = "Invalid Phone Number";
		}
	}
	$fileempty  = false;
	if (empty($_POST["file"])){
		$file = $_FILES['file'];
		$File = $_FILES['file']['name'];
		$fileTmpName = $_FILES['file']['tmp_name'];
		$fileSize = $_FILES['file']['size'];
		$EditProfileFileError = $_FILES['file']['error'];
		$fileType = $_FILES['file']['type'];
		
		$fileExt = explode('.',$File);
		$fileActualExt = strtolower(end($fileExt));
		$allowed = array('jpg','jpeg','png');
		
		if(in_array($fileActualExt, $allowed)){
		
			if($EditProfileFileError == 0){
					
				if($fileSize < 500000){	//if file size less then 50mb
					$FileNew = uniqid('', true).".".$fileActualExt;
					

				} else {
					
					$EditProfileFileErr= "The file is too big!";
					$submit = false;
				}
			} else {
				
				$EditProfileFileErr= "There was an error uploading the file!";
				$submit = false;
			}
			
		} else {
			if($fileSize==0){
				$fileempty = true;
			}
			else{	
				$EditProfileFileErr= "You cannot upload files of this type!";
				$submit = false;
			}
		
		}
	}
	if($submit){
		if($_POST["EditProfileDisplayName"] == $_SESSION['Object']-> getDisplayName() && $_POST["EditProfileFirstName"] == $_SESSION['Object']-> getFirstName() && $_POST["EditProfileLastName"] == $_SESSION['Object']-> getLastName() && $_POST["EditProfileContact"] == $_SESSION['Object']-> getContactNumber() && $_POST["EditProfileAddress"]  == $_SESSION['Object']-> getAddress()&&$fileempty){
			$EditProfile = false;
			echo'<script>alert("No changes were made")</script>';
			echo '<script> location.replace("SettingsPage.php")</script> ';
		}
		else{
			if(isset($FileNew)){
			$FileNew = 'profilepictures/'.$FileNew;
			}
			else{
				$fileTmpName = '';
				$FileNew = '';
			}
			if($_SESSION['Object']->EditProfileValidate($_POST["EditProfileFirstName"],$_POST["EditProfileLastName"],$_POST["EditProfileContact"],$_POST["EditProfileDisplayName"],$_POST["EditProfileAddress"],$fileTmpName,$FileNew,$fileempty)=="validated"){
				$EditProfile = false;
				echo'<script>alert("Successfully updated your profile, please check your profile")</script>';
				$_SESSION['Object']->setUID($_SESSION['Object']->getUID());
				echo '<script> location.replace("SettingsPage.php")</script> ';
				
				
			}
		}
	}
}
else{
$_POST["EditProfileDisplayName"]  = "";
$_POST["EditProfileFirstName"]  = "";
$_POST["EditProfileLastName"]  = "";
$_POST["EditProfileContact"]  = "";
$_POST["EditProfileAddress"]  = "";
$EditProfile = false;
}


?>
<script>
function UpdatePicture(){
	var input = document.getElementById("fileupload");
	var fReader = new FileReader();
	fReader.readAsDataURL(input.files[0]);
	fReader.onloadend = function(event){
	var img = document.getElementById("profilepicture");
	img.src = event.target.result;
	}

}
</script>
<div class="EditProfile_GUI" id="EditProfile_GUI">
<form class ="EditProfileForm" method="post" enctype="multipart/form-data">
<span class="error"></span><br /><br />
<div class="centerBox">

	<center><h1>Edit Profile<h1></center>
	
	<label>Profile Picture:</label>
	<img src="<?php echo $_SESSION['Object']->ProfilePic ?>" id="profilepicture" height="300" width="300">
	<label>Upload Profile Picture:</label>
	<input type="file" id="fileupload" onchange="UpdatePicture()" name="file" />
	<span class="error"><?php echo $EditProfileFileErr;?></span><br /><br />
	
	<label>Display Name:</label>
	<input type="text" name="EditProfileDisplayName" value = <?php echo $_SESSION['Object']->getDisplayName() ; ?>>
	<span class="error">&nbsp;&nbsp;<?php echo $EditProfileDisplayNameError;?></span><br /><br />
	
	<label>First Name:</label>
	<input type="text" name="EditProfileFirstName" value = <?php echo $_SESSION['Object']->getFirstName(); ?>>
	<span class="error">&nbsp;&nbsp;<?php echo $EditProfileFirstNameError;?></span><br /><br />

	<label>Last Name:</label>
	<input type="text" name="EditProfileLastName" value = <?php echo $_SESSION['Object']->getLastName(); ?>>
	<span class="error">&nbsp;&nbsp;<?php echo $EditProfileLastNameError;?></span><br /><br />


	<label>Contact Number:</label>
	<input type="text" name="EditProfileContact" value = <?php echo $_SESSION['Object']->getContactNumber(); ?>>
	<span class="error">&nbsp;&nbsp;<?php echo $EditProfileContactError;?></span><br /><br />

	<label style=" vertical-align: middle;">Address:</label>
	<textarea rows="4" cols="50" name="EditProfileAddress"><?php echo $_SESSION['Object']->getAddress(); ; ?></textarea>
	<span class="error">&nbsp;&nbsp;<?php echo $EditProfileAddressError;?></span><br /><br />


	<input type="submit" name="EditProfileButton" id="donebtn" value="Done" style="float:right;"/></br> 
</form>
</div>

</div>

<?php 

$ChangePasswordPasswordError = $ChangePasswordNewPasswordError = $ChangePasswordNewConfirmPasswordError = "";
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ChangePasswordButton'])){
$ChangePassword = true;
$submit2 = true;
		if(empty($_POST["ChangePasswordNewPassword"]))
		{
			$ChangePasswordNewPasswordError = "New password is required";
			$submit2 = false;
		}
		else{
		
		if ( strlen($_POST["ChangePasswordNewPassword"]) < 8 ) {
			$submit2 = False;
			$EditProfilePasswordError = "Must have at least 8 characters,containing lower case alphabets,upper case alphabets and numbers";
		}

		if ( !preg_match("#[0-9]+#", $_POST["ChangePasswordNewPassword"]) ) {
		
			$submit2 = False;
			$EditProfilePasswordError = "Must have at least 8 characters,containing lower case alphabets,upper case alphabets and numbers";
		}

		if ( !preg_match("#[a-z]+#", $_POST["ChangePasswordNewPassword"]) ) {
	
			$submit2 = False;
			$EditProfilePasswordError = "Must have at least 8 characters,containing lower case alphabets,upper case alphabets and numbers";
		}


		
	}

		if(empty($_POST["ChangePasswordNewConfirmPassword"]))
		{
			$ChangePasswordNewConfirmPasswordError = "Confirm Password is required";
			$submit2 = false;
		}
		else{

		if(strcmp($_POST["ChangePasswordNewPassword"],$_POST["ChangePasswordNewConfirmPassword"])!=0){

			$ChangePasswordNewConfirmPasswordError = "Passwords do not match";
			$submit2 = false;
		}
		}
		if($submit2){
		$ChangePassword = false;
		if($_SESSION['Object']->ChangePasswordValidate($_POST["ChangePasswordPassword"],$_POST["ChangePasswordNewPassword"],$_POST["ChangePasswordNewConfirmPassword"]) =="Validated"){
			echo'<style> alert("Password Changed Successfully!Please login again")</style>';
			$_SESSION["Object"]->LogOut();
			
		}
		else{
			$ChangePasswordPasswordError = "Incorrect password";
			$ChangePassword = true;
		}
		}
}
else{
	$ChangePassword = false;
}
if(!$ChangePassword){
echo'<style> .ChangePassword_GUI{display:none;}</style>';	
}




?>

<div class="ChangePassword_GUI">
<form method="post">
<span class="error"></span><br /><br />
<div class="centerBox">
	<center><h1>Change Password<h1></center>
	<label>Password:</label>
	<input type="password" name="ChangePasswordPassword">
	<span class="error">&nbsp;&nbsp;<?php echo $ChangePasswordPasswordError;?></span><br /><br />
	
	<label>New Password:</label>
	<input type="password" name="ChangePasswordNewPassword">
	<span class="error">&nbsp;&nbsp;<?php echo $ChangePasswordNewPasswordError;?></span><br /><br />

	<label>Confirm new Password:</label>
	<input type="password" name="ChangePasswordNewConfirmPassword">
	<span class="error">&nbsp;&nbsp;<?php echo $ChangePasswordNewConfirmPasswordError;?></span><br /><br />

	<input type="Submit" name="ChangePasswordButton" id="donebtn" value="Change Password" style="float:right;"/></br> 
</form>
</div>
</div>

<?php 
$submit = true;
 $EditProfileEmailError = ""; 

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ChangeEmailButton'])){
$ChangeEmail = true;
	
	if(empty($_POST["EditProfileEmail"]))
	{
		$EditProfileEmailError = "Email is required";
		$submit = false;
	}
	else{
		if(!filter_var($_POST["EditProfileEmail"], FILTER_VALIDATE_EMAIL)){
			$submit = false;
			$EditProfileEmailError = "Invalid Email format";
		}
	}
	
	
		
	
	if($submit){
		if( $_POST["EditProfileEmail"] == $_SESSION['Object']-> getEmail()){
				$submit = false;
			echo'<script>alert("No changes were made")</script>';
			echo '<script> location.replace("SettingsPage.php")</script> ';
		}
		else{
			$_SESSION['InputVerified']=true;
			echo'<style> .ChangeEmail_GUI{display:none;}</style>';
			echo'<style> #confirmation{display:block;}</style>';
		}
	}
}
else{

$_POST["EditProfileEmail"]  = "";
$ChangeEmail = false;
	$submit = false;
}

if(!$ChangeEmail){
echo'<style> .ChangeEmail_GUI{display:none;}</style>';	
}



if(isset($_SESSION['VerifiedUser'])&&isset($_SESSION['InputVerified'])){
	
		
			if($_SESSION['Object']->EditProfileEmailValidate($_POST["EditProfileEmail"])=="validated"){
				
		
				echo'<script>alert("Successfully updated your email, please check your profile")</script>';
				$_SESSION['Object']->setUID($_SESSION['Object']->getUID());
				echo '<script> location.replace("SettingsPage.php")</script> ';
				
				
			}
			else if($_SESSION['Object']->EditProfileEmailValidate($_POST["EditProfileEmail"])=="Email error"){
				$EditProfileEmailError = "Email already exists";
				$EditProfile = false;
			}
					unset($_SESSION['InputVerified']);
			unset($_SESSION['VerifiedUser']);
}
?>

<div class="ChangeEmail_GUI">
<form method="post">
<span class="error"></span><br /><br />
<div class="centerBox">
	<center><h1>Change Email<h1></center>
	
	<p><i>Do note that you will have to verify yourself before you can change your email</i></p>
	<center><input type="text" name="EditProfileEmail" style="width:70%"value = <?php echo $_SESSION['Object']->getEmail(); ?>></center>
	<span class="error">&nbsp;&nbsp;<?php echo $EditProfileEmailError;?></span><br /><br />

	<input type="Submit" name="ChangeEmailButton" id="donebtn" value="Change Email" style="float:right;"/></br> 
</form>
</div>

</div>
</div>
</div>


<div id="loadergui">
<h2>Loading Please Wait</h2>
<div id="loader"></div>
<div id="loaderimage"></div>
</div>


</div>
<div id="confirmation">
<div id="confirmationtext">
<b>Are you sure you?</b></br>
<input type="submit"  class="OTPbtns" name="submit" id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="emailverification(this.id)" value="Yes">
<input type="submit" class="OTPbtns"  onclick="rejectfunction()" value="No">

</div>
</div>
<div id="OTP" >
<div id="OTPform">
<center><b style="font-size:20px">OTP code sent to your email</b></center><center><input type="text"  id="OTPinput"></center>
<input type="submit" class="OTPbtns" onclick="VerifyOTP()" value="Submit OTP">
<input type="submit" class="OTPbtns" id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="ResendOTP(this.id)" value="Resend">
</div>
</div>

<input type="hidden" class="text-box" id="User"  value = "<?php echo $_SESSION['Object']->getUID()?>">

<script>


var User  = document.getElementById('User').value;
function AcceptConfirm(){
document.getElementById('confirmation').style.display = "block";

}
function rejectfunction(){
	history.pushState({}, "", "");
location.reload();
}
function emailverification(email){
	ajax.open("POST", "SettingsPageController.php", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("Email=" + email);
	console.log(ajax);
	document.getElementById('confirmation').style.display = "none";
	document.getElementById('OTP').style.display = "block";

}
function ResendOTP(email){

	ajax.open("POST", "SettingsPageController.php", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("Email=" + email);
	console.log(ajax);
	alert("Resent Email");
}
function VerifyOTP(){
	OTPEntry = document.getElementById('OTPinput').value;
	document.getElementById('container').style.display = "none";
	document.getElementById('loadergui').style.display = "block";
	document.getElementById('OTP').style.display = "none";
	ajax.open("POST", "SettingsPageController.php", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("OTP=" + OTPEntry );
	
}
var connection =  new WebSocket(webportconfig);
connection.onmessage = function (message) {
	var data = JSON.parse(message.data);

			if (data.REPLY == 'OTPResult') {
					if(User == data.User){
						console.log(data.Result);
							if(data.Result == "Success"){
								ajax.open("POST", "SettingsPageController.php", true);
								ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								location.reload();
							}
							if(data.Result == "Failed"){
								alert("Invalid OTP code"); 
								history.pushState({}, "", "");
								location.reload();
							
							}
							if(data.Result == "LogOut"){
								alert("Max Attempt reached,you will be logged out"); 
								ajax.open("POST", "ConvertPageController.php", true);
								ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								ajax.send("Logout=" + User);
								location.replace("LoginPage.php");
							}
						
					}
					
				}
	
}
</script>
<?php require_once("Footer.php");?>