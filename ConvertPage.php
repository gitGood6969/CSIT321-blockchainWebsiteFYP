<?php require_once("NavBar.php");?>

<style>
span{

color:red;
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

#convertGUI{
width:1000px;
height:700px;
margin:auto;
margin-top:2%;
}

.TopUpGUI{
width:600px;
margin-bottom:100px;
text-align:center;
margin:auto;
border:1px solid black;
border-radius:20px;
box-shadow:5px 5px gray;
}

.RedeemGUI{
width:600px;
text-align:center;
margin:auto;
border:1px solid black;
border-radius:20px;
box-shadow:5px 5px gray;
}

button,input[type=submit] {
margin-top:20px;
border:none;
background-color:purple;
color:white;
font-size:20px;
border-radius:10px;
margin-right:10px;
}

input[type=submit]:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);
}

#Convert2Ethbtn{
float:right;
height:400px;
width:300px;
background-repeat: no-repeat; /* Do not repeat the image */
background-size: full; /* Resize the background image to cover the entire container */
background-image: url('systemimages/TopUpImage.png');
background-attachment: scroll;
background-position: center;
background-repeat: no-repeat;
background-size: 300px 500px;
cursor:pointer;
transition:all 0.5s ease-in-out;
-webkit-transition: all 0.5s ease-in-out;
-moz-transition: all 0.5s ease-in-out;
-o-transition: all 0.5s ease-in-out;
}

#Convert2Fbtn{
float:left;
height:400px;
width:300px;
background-repeat: no-repeat; /* Do not repeat the image */
background-size: full; /* Resize the background image to cover the entire container */
background-image: url('systemimages/RedeemImage.png');
background-attachment: scroll;
background-position: center;
background-repeat: no-repeat;
background-size: 300px 500px;
cursor:pointer;
transition:all 0.5s ease-in-out;
-webkit-transition: all 0.5s ease-in-out;
-moz-transition: all 0.5s ease-in-out;
-o-transition: all 0.5s ease-in-out;
}

#Convert2Fbtn:hover{
outline:60%;
filter: drop-shadow(0 0 7px indigo);	
}

#Convert2Ethbtn:hover{
outline:60%;
filter: drop-shadow(0 0 7px indigo);
}

#transactionscontainer{
width:50%;
text-align:center;
margin:auto;
border:3px solid purple;
box-shadow:5px 5px gray;
height:400px;
overflow:scroll;
overflow-x: hidden;
overflow-y: auto;
}

.card{
width:80%;
text-align:left;
margin:auto;
border:1px solid black;
}

#transactionscontainer::-webkit-scrollbar {
width:3px;
background-color:white;
}

#transactionscontainer:-webkit-scrollbar-track {
width:1px;
}

#transactionscontainer::-webkit-scrollbar-thumb {
background-color:purple;
outline: 2px solid purple;
}

#transactionscontainer::-webkit-scrollbar-track-piece:start {background: purple;margin-top: 20px;}
#leftbox {
float:left; 
width:50%;
}

#middlebox{
float:left; 
display:none;
margin-auto;
}

#rightbox{
float:right;
width:50%;
}
</style>
<?php

if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}

if(isset($_SESSION['ConvertComplete'])){
	unset($_SESSION['ConvertComplete']);
	echo '<script> location.replace("ConvertPage.php")</script> ';
}
$Convert_amountError= "";
$Convert2_amountError= "";

$TU = false;
$RE = false;



if(isset($_POST['TopUpBtn'])){

	$TU = true;
	if(empty($_POST["Convert_amount"]))
	{
		$Convert_amountError = "Amount is required";
		
	}
	else{
			echo'<style> .TopUpGUI{display:none;}</style>';
			echo'<style> #confirmation{display:block;}</style>';
			$_SESSION['AmountTU'] = $_POST["Convert_amount"] ;
			
	}
}
else{
	$_POST["Convert_amount"] = '';
}


if(isset($_POST['RedeemBtn'])){
	$TU = true;
	if(empty($_POST["Convert2_amount"]))
	{
		$Convert2_amountError = "Amount is required";
		$validated = false;
	}
	else{
		if($_POST["Convert2_amount"]>$_SESSION['Object']->getAccountBalance()){
			$Convert_amountError = "";	
			echo'<script>alert("You have insufficient amount of money,please top up");</script>';
		}
		else{
			echo'<style> .RedeemGUI{display:none;}</style>';
			echo'<style> #confirmation{display:block;}</style>';
			$_SESSION['AmountRE'] = $_POST["Convert2_amount"] ;
		}
	}
}
else{
	$_POST["Convert2_amount"] = '';
}




if(isset($_SESSION['VerifiedUser'])&&isset($_SESSION['AmountTU'])){
unset($_SESSION['VerifiedUser']);
$_SESSION['Object'] -> creditCardIn($_SESSION['AmountTU']);
unset($_SESSION['AmountTU']);
$_SESSION['ConvertComplete'] = true;
echo '<script> location.replace("https://2a7995e7ed69.ngrok.io/checkout.html")</script> ';

}

if(isset($_SESSION['VerifiedUser'])&&isset($_SESSION['AmountRE'])){
unset($_SESSION['VerifiedUser']);
$link = $_SESSION['Object'] -> creditCardOut($_SESSION['AmountRE']);
unset($_SESSION['AmountRE']);
$_SESSION['ConvertComplete'] = true;

echo'<style> #transactionscontainer{display:none;}</style>';	
echo'<style> .loadergui{display:block;}</style>';	
echo '<script> location.replace("'.$link .'")</script> ';

}



if(!$RE){
echo'<style> .RedeemGUI{display:none;}</style>';	
}
if(!$TU){
echo'<style> .TopUpGUI{display:none;}</style>';	
}




?>
<div id="loadergui">
<h2>Loading Please Wait</h2>
<div id="loader"></div>
<div id="loaderimage"></div>
</div>


<div id="convertGUI">

<div id="leftbox">
<button id="Convert2Ethbtn" class="TopUp"></button>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $(".TopUp").click(function(){
$(".TopUp").css('margin-right', '40%');
$(".Transfer").css('margin-left', '40%');

setTimeout(
  function() 
  {
	  $("#leftbox").css('width', '20%');
$("#rightbox").css('width', '20%');
 $("#middlebox").show();
  }, 800);
    $(".TopUpGUI").show();
	
	$(".RedeemGUI").hide();
  });
  
  
  
  $(".Transfer").click(function(){
	  $(".TopUp").css('margin-right', '40%');
$(".Transfer").css('margin-left', '40%');

setTimeout(
  function() 
  {
	  $("#leftbox").css('width', '20%');
$("#rightbox").css('width', '20%');
 $("#middlebox").show();
  }, 800);
    $(".RedeemGUI").show();
	$(".TopUpGUI").hide();
  });
});

</script>
<div id="middlebox">
	<div class="TopUpGUI">	
	<form method="post" id="formTopUp" >
	  <b>Top Up Store Credits</b></br>
	  <label for="Convert2_amount">Amount(<?php echo $_SESSION['Object']->getCurrency()?>):</label><br/>
	  <input type="Number" step="any" id="Convert_amount" max="999999.99" min="0" name="Convert_amount"value=<?php echo $_POST["Convert_amount"];?>><br><br>
	  <span class="error"><?php echo $Convert_amountError;?></span><br /><br />
	  <input type="submit"  name="TopUpBtn" value="Top Up Now!">
	</form> 
	</div>

	<div class="RedeemGUI">	
	<form method="post" id="formRedeem">
	  <b>Redeem Money</b></br>
	  <label for="Convert2_amount">Amount(<?php echo $_SESSION['Object']->getCurrency()?>):</label><br/>
	  <input type="Number" step="any" id="Convert2_amount" max="999999.99" min="0" name="Convert2_amount"value=<?php echo $_POST["Convert2_amount"];?>><br><br>
	  <span class="error"><?php echo $Convert2_amountError;?></span><br /><br />
	  <input type="submit"  name="RedeemBtn" value="Redeem Now!">
	</form> 
	</div>
	</div>
<div id="rightbox">
<button id="Convert2Fbtn" class="Transfer"></button>
</div>
</div>

<div id="confirmation">
<div id="confirmationtext">
<b>Are you sure?</b></br>
<input type="submit"   name="submit" id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="emailverification(this.id)" value="Yes">
<input type="submit"  onclick="rejectfunction()" value="No">

</div>
</div>
<div id="OTP" >
<div id="OTPform">
<center><b style="font-size:20px">OTP code sent to your email</b></center><center><input type="text"  id="OTPinput"></center>
<input type="submit" onclick="VerifyOTP()" value="Submit OTP">
<input type="submit" id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="ResendOTP(this.id)" value="Resend">
</div>
</div>

<input type="hidden" class="text-box" id="User"  value = "<?php echo $_SESSION['Object']->getUID()?>">

</div>

<hr style="purple">

<div id="transactionscontainer">
<center><h1>Recent Transactions</h1></center>
</br></br></br>
<?php
$array = $_SESSION['Object']->ListOfRecentTransactions();
if(!empty($array)){
foreach ($array as &$ID) {
?>
<a href="MyTransactionsPage.php" style="text-decoration:none;color:purple">
<div id="card" class="card">

<b style=" <?php if($_SESSION['Object']->getTransactionSender($ID)== $_SESSION['ID']){
echo "color:red";
}
else{
echo "color:green";
}

?>
">Amount:<?php echo   " SGD$".number_format($_SESSION['Object']->getTransactionAmount($ID)/100, 2, '.', '')?></b>
<b>Sender:<?php echo  $_SESSION['Object']->getTransactionSender($ID) ?></b>
<b>Reciever:<?php echo $_SESSION['Object']->getTransactionReceiver($ID) ?></b>
<b>Transaction Date:<?php  echo $_SESSION['Object']->getTransactionDate($ID) ?></b>	
<b>Transaction Title:<?php  echo$_SESSION['Object']->getTransactionTitle($ID) ?></b>	
</div></a></br>
<?php
}
}
else{
	echo'<h2>You currently have no transactions</h2>';
}
?>

</center>
</div>
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
	ajax.open("POST", "ConvertPageController.php", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("Email=" + email);
	console.log(ajax);
	document.getElementById('confirmation').style.display = "none";
	document.getElementById('OTP').style.display = "block";

}
function ResendOTP(email){

	ajax.open("POST", "ConvertPageController.php", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("Email=" + email);
	console.log(ajax);
	alert("Resent Email");
}
function VerifyOTP(){
	document.getElementById('convertGUI').style.display = "none";
	document.getElementById('transactionscontainer').style.display = "none";
	document.getElementById('loadergui').style.display = "block";
	OTPEntry = document.getElementById('OTPinput').value;
	document.getElementById('OTP').style.display = "none";
	ajax.open("POST", "ConvertPageController.php", true);
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
								document.getElementById('convertGUI').style.display = "none";
								document.getElementById('loadergui').style.display = "block";
								ajax.open("POST", "ConvertPageController.php", true);
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