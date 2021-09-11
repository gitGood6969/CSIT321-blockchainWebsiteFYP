<html>
<?php require_once("NavBar.php");
if(isset($_SESSION['ID'])){
echo '<script> location.replace("index.php")</script> ';	
	
}

?>

<style>
span{
	width:300px;
	color:purple;
}
.SignUpForm * {
  vertical-align: middle;
}

.centerBox {

	width: 50%;
	/* border: 3px solid #73AD21; */
	padding: 10px;
}

label {	
	display: inline-block; /* In order to define widths */
}

label {
	width: 30%;
	text-align: right;    /* Positions the label text beside the input */
}

label+input+textarea {
	width: 40%;
	
	/* Large margin-right to force the next element to the new-line
    and margin-left to create a gutter between the label and input */
		
	/* Margin: 0% for top, 30% for right, 0% for bottom, 4% for left */
	margin: 0 30% 0 4%;
}
#container{
	margin-top:5%;
	width:1400px;
	height:1400px;
	border-radius:20px;
	border:2px solid #000099;
		margin-left:15%;
	overflow: hidden;
 box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
	
}
#innercontainer{
	  background-size: cover;
	  background-size: 700px;
	background-repeat: no-repeat; /* Do not repeat the image */
	background-position: center;
	background-image: url('systemads/SignUpAds.jpg');
	width:50%;
	height:100%;
	background-color:#141240;
	   float: right;
}
#SignUp_GUI{
color:#000099;
width:50%;
height:700px;
float: left;
font-size:15px;
	
}
#SignUp_GUI input{

	height:40px;


}
#SignUp_GUI input[type=submit]{
	
	display:inline-block;
	border:none;
	font-family: 'Roboto';
	background-color:#000099;
	color:white;
	height:50px;
	font-size:20px;
	border-radius:40px;
	margin-right:10px;
width:500px;
}

#SignUp_GUI input[type=submit]:hover {
	
	 outline:60%;
    filter: drop-shadow(0 0 5px blue);
}
#profilepicGUI{
display:none;
color:#000099;
width:50%;
height:700px;
float: left;
font-size:15px;
}
#output{
	margin-left:30%;
	margin-top:5%;
	width:200px;
	height:200px;
	background-repeat: no-repeat; /* Do not repeat the image */
	background-size: cover; /* Resize the background image to cover the entire container */
	border-radius:50%;
	border:2px solid #000099;
}
</style>
<?php 

$SignUpFirstNameError = $SignUpContactError = $SignUpFileErr = $SignUpLastNameError = $SignUpIDError =  $SignUpEmailError =  $SignUpPasswordError =  $SignUpConfirmPasswordError = $SignUpAddressError = $SignUpDisplayNameError = $SignUpDOBError = ""; 


$submit = true;

if(isset($_POST['LoginButton'])){
echo '<script> location.replace("SignUpPage.php")</script> ';	
	
}
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['SignUpButton'])){

	if(empty($_POST["SignUpFirstName"]))
	{
		$SignUpFirstNameError = "First name is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[a-z A-Z]*$/", $_POST["SignUpFirstName"])){
			$SignUpFirstNameError = "First name is invalid";
			$submit = false;
		}
	}
	if(empty($_POST["SignUpLastName"]))
	{
		$SignUpLastNameError = "Last name is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[a-z A-Z]*$/", $_POST["SignUpLastName"])){
			$SignUpLastNameError = "Last name is invalid";
			$submit = false;
		}
	}
	if(empty($_POST["SignUpEmail"]))
	{
		$SignUpEmailError = "Email is required";
		$submit = false;
	}
	else{
		if(!filter_var($_POST["SignUpEmail"], FILTER_VALIDATE_EMAIL)){
			$submit = false;
			$SignUpEmailError = "Invalid Email format";
		}
	}
	
	if(empty($_POST["SignUpID"]))
	{
		$SignUpIDError = "UserID is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[A-Za-z0-9]{8,20}$/i", $_POST["SignUpID"])){
			$submit = false;
			$SignUpIDError = "Invalid User ID ,requires at least 8 characters and 20 characters at maximum";
		}
	}
	
	if(empty($_POST["SignUpDisplayName"]))
	{
		$SignUpDisplayNameError = "Display name is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[A-Za-z0-9]{8,20}$/i",  $_POST["SignUpDisplayName"])){
			$submit = false;
			$SignUpDisplayNameError = "Invalid Display Name ,requires at least 8 characters and 20 characters at maximum";
		}
	}
	if(empty($_POST["SignUpAddress"]))
	{
		$SignUpAddressError = "Address is required";
		$submit = false;
	}
	
	if(empty($_POST["SignUpDOB"]))
	{
		$SignUpDOBError = "Date of birth is required";
		$submit = false;
	}	
	else{
		if(strtotime($_POST["SignUpDOB"]) > strtotime('now')) {
			$SignUpDOBError = "Invalid Date,date of birth must be before ". date('d/m/Y', strtotime(('now'))) ;
		}
	}
	
	if(empty($_POST["SignUpContact"]))
	{
		$SignUpContactError = "Phone Number is required";
		$submit = false;
	}
	else{
		if(!preg_match("/^[0-9]{8}$/",$_POST["SignUpContact"])) {
			$submit = false;
			$SignUpContactError = "Invalid Phone Number";
		}
	}
	
	if(empty($_POST["SignUpPassword"]))
	{
		$SignUpPasswordError = "Password is required";
		$submit = false;
	}
	else{
		
		if ( strlen($_POST["SignUpPassword"]) < 8 ) {
			$submit = False;
			$SignUpPasswordError = "Must have at least 8 characters,containing lower case alphabets,upper case alphabets and numbers";
		}

		if ( !preg_match("#[0-9]+#", $_POST["SignUpPassword"]) ) {
		
			$submit = False;
			$SignUpPasswordError = "Must have at least 8 characters,containing lower case alphabets,upper case alphabets and numbers";
		}

		if ( !preg_match("#[a-z]+#", $_POST["SignUpPassword"]) ) {
	
			$submit = False;
			$SignUpPasswordError = "Must have at least 8 characters,containing lower case alphabets,upper case alphabets and numbers";
		}


		
	}
	
	if(empty($_POST["SignUpConfirmPassword"]))
	{
		$SignUpConfirmPasswordError = "Confirm Password is required";
		$submit = false;
	}
	else{
		
		if(strcmp($_POST["SignUpPassword"],$_POST["SignUpConfirmPassword"])!=0){
		
			$SignUpConfirmPasswordError = "Passwords do not match";
			$submit = false;
		}
	}
	if (empty($_POST["file"])){
		$file = $_FILES['file'];
		
		$File = $_FILES['file']['name'];
		$fileTmpName = $_FILES['file']['tmp_name'];
		$fileSize = $_FILES['file']['size'];
		$FileError = $_FILES['file']['error'];
		$fileType = $_FILES['file']['type'];
		
		$fileExt = explode('.',$File);
		$fileActualExt = strtolower(end($fileExt));
		$allowed = array('jpg','jpeg','png','pdf');
		
		if(in_array($fileActualExt, $allowed)){
		
			if($FileError == 0){
					
				if($fileSize < 5000000){	
					$FileNew = uniqid('', true).".".$fileActualExt;
					

				} else {
					
					$SignUpFileErr= "The file is too big!";
					$submit = false;
				}
			} else {
				
				$SignUpFileErr= "There was an error uploading the file!";
				$submit = false;
			}
			
		} else {
			if($fileSize==0){
				
			}
			else{	
				$SignUpFileErr= "You cannot upload files of this type!";
				$submit = false;
			}
		
		}
	}
	
	
	if($submit){
		require_once("Users.php");
		$BaseUserOBJ = new BaseUser("SignUp");
		if(isset($FileNew)){
		$fileDestination = 'profilepictures/'.$FileNew;
		}
		else{
			$fileDestination= '';
			$fileTmpName= '';
		}
		if($BaseUserOBJ->SignUpValidate($_POST["SignUpID"],$_POST["SignUpEmail"],$_POST["SignUpPassword"],$_POST["SignUpFirstName"],$_POST["SignUpLastName"],$_POST["SignUpContact"],$_POST["SignUpDisplayName"],$_POST["SignUpDOB"],$_POST["SignUpAddress"],$fileTmpName,$fileDestination)=="validated"){
			echo '<script> alert("Successfully Signed Up! Please login now")</script> ';
			echo '<script> location.replace("LoginPage.php")</script> ';
			exit();
		}
		else if($BaseUserOBJ->SignUpValidate($_POST["SignUpID"],$_POST["SignUpEmail"],$_POST["SignUpPassword"],$_POST["SignUpFirstName"],$_POST["SignUpLastName"],$_POST["SignUpContact"],$_POST["SignUpDisplayName"],$_POST["SignUpDOB"],$_POST["SignUpAddress"],$fileTmpName,$fileDestination )=="UserID error"){
			$SignUpIDError = "UserID already exists";
		}
		else if($BaseUserOBJ->SignUpValidate($_POST["SignUpID"],$_POST["SignUpEmail"],$_POST["SignUpPassword"],$_POST["SignUpFirstName"],$_POST["SignUpLastName"],$_POST["SignUpContact"],$_POST["SignUpDisplayName"],$_POST["SignUpDOB"],$_POST["SignUpAddress"],$fileTmpName,$fileDestination )=="Email error"){
			$SignUpEmailError = "Email already exists";
		}
	}
}
else{
$_POST["file"] = "";
$_POST["SignUpID"]  = "";
$_POST["SignUpDisplayName"]  = "";
$_POST["SignUpFirstName"]  = "";
$_POST["SignUpLastName"]  = "";
$_POST["SignUpEmail"]  = "";
$_POST["SignUpContact"]  = "";
$_POST["SignUpAddress"]  = "";
$_POST["SignUpDOB"]  = "";
$_POST["SignUpPassword"]  = "";
$_POST["SignUpConfirmPassword"]  = "";
}


?>
<div id="container">
<div id="SignUp_GUI">
<center><a href="index.php"><img src="systemimages/STIClogo.jpg" class="image" style="object-fit:cover;width:200px;height:100px;border-radius:10px;margin-top:5%"></a></center>
<form class ="SignUpForm" method="post" enctype="multipart/form-data">
<span class="error"></span><br /><br />

	<label>User ID:</label>
	<input type="text" name="SignUpID" value = <?php echo $_POST["SignUpID"] ; ?>>
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpIDError;?></span><br /><br />

	<label>Display Name:</label>
	<input type="text" name="SignUpDisplayName" value = <?php echo $_POST["SignUpDisplayName"] ; ?>>
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpDisplayNameError;?></span><br /><br />

	<img id="output" src="profilepictures/default.jpg"/></br>
	<label>Upload Profile Picture:</label>

	<input type="file" name="file" accept="image/*" value="default" onchange="loadFile(event)">

	<script>
	var loadFile = function(event) {
	var output = document.getElementById('output');
	output.src = URL.createObjectURL(event.target.files[0]);
	output.onload = function() {
	  URL.revokeObjectURL(output.src) 
	}
	};
	</script>
	<span class="error"><?php echo $SignUpFileErr;?></span><br /><br />
	
	
	<label>First Name:</label>
	<input type="text" name="SignUpFirstName" value = "<?php echo $_POST["SignUpFirstName"] ; ?>">
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpFirstNameError;?></span><br /><br />

	<label>Last Name:</label>
	<input type="text" name="SignUpLastName" value = "<?php echo $_POST["SignUpLastName"] ; ?>">
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpLastNameError;?></span><br /><br />

	<label>Email:</label>
	<input type="text" name="SignUpEmail" value = "<?php echo $_POST["SignUpEmail"] ; ?>">
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpEmailError;?></span><br /><br />

	<label>Contact Number:</label>
	<input type="text" name="SignUpContact" value = "<?php echo $_POST["SignUpContact"] ; ?>">
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpContactError;?></span><br /><br />

	<label>Address:</label>
	<textarea rows="4" cols="50" name="SignUpAddress"><?php echo $_POST["SignUpAddress"] ; ?></textarea>
	<br /><center><span class="error">&nbsp;&nbsp;<?php echo $SignUpAddressError;?></span></center><br />

	<label>Date Of Birth:</label>
	<input type="date" name="SignUpDOB" value = "<?php echo $_POST["SignUpDOB"] ; ?>">
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpDOBError;?></span><br /><br />

	<label>Password:</label>
	<input type="password" name="SignUpPassword" value = "<?php echo $_POST["SignUpPassword"] ; ?>">
	<br /><center><span class="error">&nbsp;&nbsp;<?php echo $SignUpPasswordError;?></span><br /></center><br />

	<label>Confirm Password:</label>
	<input type="password" name="SignUpConfirmPassword"  value = "<?php echo $_POST["SignUpConfirmPassword"] ; ?>">
	<span class="error">&nbsp;&nbsp;<?php echo $SignUpConfirmPasswordError;?></span><br /><br />
	
	<center><input type="Submit" name="SignUpButton" value="Sign Up" /></br> </center>


</form>
<center>Already have an account?</br><form action="LoginPage.php" method="post"><input type="Submit" name="LoginButton" value="Login" /></form></br> </center>

</div>

<div id="profilepicGUI">
<center><a href="index.php"><img src="systemimages/STIClogo.jpg" class="image" style="object-fit:cover;width:200px;height:100px;border-radius:10px;margin-top:5%"></a></center>

</div>
<div id="innercontainer">

</div>
</div>
</html>
<?php require_once("Footer.php");?> 