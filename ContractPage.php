<?php
date_default_timezone_set("Singapore");
require_once("Users.php");
require_once("Products.php");
require_once("Contracts.php");
session_start();
if(!isset($_SESSION['ID'])){
echo '<script> location.replace("index.php")</script> ';
}
$ContractID = $_GET['ID'] ;
$_SESSION["ContractID"] = $_GET['ID'];
$ContractObj = new Contracts();
if(!$ContractObj->initialiseContract($ContractID)){
echo '<script> location.replace("index.php")</script> ';
}
if($ContractObj->BuyerUserID!=$_SESSION['ID']&&$ContractObj->SellerUserID!=$_SESSION['ID']){
if($_SESSION['Object']->getAccountType()!="Administrator" && $_SESSION['Object']->getAccountType()!="Courier"){
echo '<script> location.replace("index.php")</script> ';
}
if($ContractObj->DeliveryMode == "Courier Delivery"  && $_SESSION['Object']->getAccountType()=="Courier"){
if($ContractObj->Courier != $_SESSION['ID']){
echo '<script> location.replace("index.php")</script> ';
}
}
}
$_SESSION['Object']->RemoveNotificationInPage('MyContractPage.php');
?>
<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>
<style type="text/css">
body{
font-family: 'Roboto';
color:purple;
font-size:10px;
}
button,input[type=submit],input[type=button] {
border:none;
background-color:purple;
color:white;
font-size:20px;
border-radius:10px;
margin-right:10px;
float:left;
}
input[type=submit]:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);
}
input[type=button]:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);
}
button:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);
}
ol.progtrckr {
list-style-type none;
}
ol.progtrckr li {
display: inline-block;
text-align: center;
line-height: 3.5em;
}
ol.progtrckr[data-progtrckr-steps="2"] li { width: 49%; }
ol.progtrckr[data-progtrckr-steps="3"] li { width: 33%; }
ol.progtrckr[data-progtrckr-steps="4"] li { width: 24%; }
ol.progtrckr[data-progtrckr-steps="5"] li { width: 19%; }
ol.progtrckr[data-progtrckr-steps="6"] li { width: 16%; }
ol.progtrckr[data-progtrckr-steps="7"] li { width: 14%; }
ol.progtrckr[data-progtrckr-steps="8"] li { width: 12%; }
ol.progtrckr[data-progtrckr-steps="9"] li { width: 11%; }
ol.progtrckr li.progtrckr-done {
color: black;
border-bottom: 4px solid yellowgreen;
}
ol.progtrckr li.progtrckr-todo {
color: silver;
border-bottom: 4px solid silver;
}
ol.progtrckr li:after {
content: "\00a0\00a0";
}
ol.progtrckr li:before {
position: relative;
bottom: -2.5em;
float: left;
left: 50%;
line-height: 1em;
}
ol.progtrckr li.progtrckr-done:before {
content: "\2713";
color: white;
background-color: purple;
height: 2.2em;
width: 2.2em;
line-height: 2.2em;
border: none;
border-radius: 2.2em;
}
ol.progtrckr li.progtrckr-todo:before {
content: "\039F";
color: silver;
background-color: white;
font-size: 2.2em;
bottom: -1.2em;
}
.topnavbg{
display:none;
}
* {
box-sizing:border-box;
}
#sharedform{
font-size:15px;
width:45%;
height:500px;
float:left;
display:block;
opacity:1;
}
#sharedform input{
}
#chatbox {
float:right;
width:50%;
max-width:100%;
margin:30px auto;
display:none;
background: rgba(255, 255, 255, 0.05);
height: 70%;
border-radius: 0.2em;
position: relative;
box-shadow: 0 0 3px grey;
font-size:15px;
margin-top:1px;
}
#message-box{
padding:30px;
height: 110px;
height: 85%;
overflow: auto;
}
body:-webkit-scrollbar-track {
-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}
#message-box:-webkit-scrollbar-thumb {
display:none;
}
#message-input{
width:90%;
border-radius:5px;
height:50px;
margin-top:20px;
padding: 0  0 0 0 ;
border:1px solid grey;
box-shadow: 0 0 2px grey;
margin-left:5%;;
}
.author {
margin-right:5px;
margin-left:20px;
font-weight:600;
float:left;
}
.messsage-text{
display: inline-block;
width: 90%;
margin-right:5%;
margin-left:5%;
}
.text-box {
margin:auto;
}
#User1{
display: inline-block;
box-shadow: 1px 1px 12px rgba(0, 0, 0, 0.3);
text-align:right;
margin-left:60%;
max-width:70%;
overflow: auto;
border-radius:5px;
background-color:purple;
min-height:50px;
color:white;
word-wrap: break-word;
float:right;
margin-bottom:2%;
}
#User1Outer{
width:100%;
word-wrap: break-word;
}
#User2{
overflow: auto;
@include triangle(rgba(255, 255, 255, 0.2), 10, left);
box-shadow: 1px 1px 12px rgba(0, 0, 0, 0.1);
display: inline-block;
text-align:right;
border-radius:5px;
max-width:40%;
margin-right:60%;
background-color:white;
min-height:50px;
word-wrap: break-word;
}
#User2Outer{
width:100%;
}
#confirmation{
position:fixed;
padding:0;
margin:auto;
top:0;
left:0;
z-index:100;
width: 100%;
height: 100%;
background:rgba(255,255,255,0.8);
}
#confirmationtext{
width:105px;
font-size:15px;
text-align:center;
margin:auto;
margin-top:20%;
}
#confirmation2{
display:none;
position:fixed;
padding:0;
margin:auto;
top:0;
left:0;
z-index:100;
width: 100%;
height: 100%;
background:rgba(255,255,255,0.8);
}
#confirmationtext2{
width:105px;
font-size:15px;
text-align:center;
margin:auto;
margin-top:20%;
}
#confirmation3{
display:none;
position:fixed;
padding:0;
z-index:100;
margin:auto;
top:0;
left:0;
width: 100%;
height: 100%;
background:rgba(255,255,255,0.8);
}
#confirmationtext3{
width:105px;
font-size:15px;
text-align:center;
margin:auto;
margin-top:20%;
}
#confirmation4{
display:none;
position:fixed;
padding:0;
margin:auto;
z-index:100;
top:0;
left:0;
width: 100%;
height: 100%;
background:rgba(255,255,255,0.8);
}
#confirmationtext4{
width:105px;
font-size:15px;
text-align:center;
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
z-index:100;
background:rgba(255,255,255,0.8);
}
#OTPform{
width:400px;
height:300px;
margin:auto;
margin-top:20%;
text-align:center;
}
#OTP input[type="submit"]{
margin-top:40px;
margin-left:50px;
}
#contractdetailsfromcontract{
display:none;
}
#loadergui {
width:300px;
margin:auto;
margin-top:20%;
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
#navbar{
display:none;
}
label {
display: inline-block; /* In order to define widths */
}
label {
width: 40%;
text-align: right;    /* Positions the label text beside the input */
margin-right:20px;
margin-top:5px;
}
#reportbtn{
float:left;
}
#namesofusers{
float:right;
font-size:30px;
margin-right:2%;
}
#courierlink{
	float:right;
	text-align:center;
	display:inline-block;
	border:1px solid purple;
	width:750px;
	margin: 0;
	margin-top:10%;
	margin-left:20%;
	margin-bottom:5%;
	height:300px;
}
@media only screen and (max-device-width: 480px) {
div {
width: 500px;
}
#sharedform {
	width:500px;

}
#chatbox{
	width:500px;
	margin-top:600px;
}
#allbuttons {
margin-top:600px;
}
input[type="submit"]{
	margin-top:10px;
}
}

</style>
</head>
<body>
<div id="loadergui">
<h2>Loading Please Wait</h2>
<div id="loader"></div>
<div id="loaderimage"></div>
</div>
<div id="contractgui">
<div id="confirmation2">
<div id="confirmationtext2">
<b>Are you sure?</b></br>
<input type="submit"  id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="emailverification(this.id)" value="Yes">
<input type="submit"  onclick="location.reload()" value="No">
</form>
</div>
</div>
<div id="confirmation3">
<div id="confirmationtext3">
<b>Are you sure?</b></br>
<input type="submit"  id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="emailverificationCancel(this.id)" value="Yes">
<input type="submit"  onclick="location.reload()" value="No">
</form>
</div>
</div>
<div id="confirmation4">
<div id="confirmationtext4">
<b>Are you sure?</b></br>
<input type="submit"  id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="emailverificationRefund(this.id)" value="Yes">
<input type="submit"  onclick="location.reload()" value="No">
</form>
</div>
</div>
<div id="OTP">
<div id="OTPform">
<center><b style="font-size:20px">OTP code sent to your email</b></center><center><input type="text"  id="OTPinput"></center>
<input type="submit" onclick="VerifyOTP()" value="Submit OTP">
<input type="submit" id="<?php echo $_SESSION['Object']->getEmail()?>" onclick="ResendOTP(this.id)" value="Resend">
</form>
</div>
</div>
<?php
if(isset( $_SESSION['Object'])){
if(isset($_POST['Report'])){
echo'
<form method="post" >
<div id="confirmation">
<div id="confirmationtext">
<b>Are you sure you want to report this contract?</b></br>
<input type="submit" name="Confirmationreport" value="Yes">
<input type="submit" name="Confirmationreport" value="No">
</form>
</div>
</div>
';
}
if(isset($_POST['Unreport'])){
echo'
<form method="post" >
<div id="confirmation">
<div id="confirmationtext">
<b>Are you sure you want to unreport this contract?</b></br>
<input type="submit" name="Confirmationunreport" value="Yes">
<input type="submit" name="Confirmationunreport" value="No">
</form>
</div>
</div>
';
}
$Type = '';
if($ContractObj->BuyerUserID==$_SESSION['ID']){
$Type = "Buyer";
$_POST["Chat_with"] = $ContractObj->SellerUserID;
}
else if($ContractObj->SellerUserID==$_SESSION['ID']){
$Type = "Seller";
$_POST["Chat_with"] = $ContractObj->BuyerUserID;
}
else{
if($_SESSION['Object']->getAccountType()=="Administrator"){
$Type = "Admin";
$_POST["Chat_with"] = $_SESSION['ID'];
}
}
$ProductObj = new Products();
$ProductObj->InitialiseProduct($ContractObj->ProductID);
if (isset($_POST["Chat_with"])){
if($Type == "Admin"){
$_SESSION['AdminAssist'] = $_POST["Chat_with"];
}
else{
$_SESSION['OtherUser'] = $_POST["Chat_with"];
}
echo'<style> #chatbox{display:block;}</style>';
}
if(!isset($_SESSION['ID'])){
echo '<script> location.replace("index.php")</script> ';
}
$submit = true;
$ProductID = $ProductObj->ProductID;
$_SESSION['Temp_Product']=$ProductID;
$DateRequiredError = $OfferError = "";
$Owner = $_SESSION['Object']->getProductOwner($ProductID);
$ProductObj = new Products();
$ProductObj->InitialiseProduct($ProductID);
?>
<ol style="width:100%;"class="progtrckr" data-progtrckr-steps="3">
<li style="margin-left:auto";" class="progtrckr-done">Sent offer to seller</li>
<li style="margin:auto;" id="trackerdeal" class="progtrckr-todo">Contract Deal</li>
<li style="margin:auto;"id="trackercomplete" class="progtrckr-todo">Service Complete</li>
</ol>
<?php
if($ContractObj->Transaction == "On-Going"){
?>
<script>
$('#trackerdeal').removeClass('progtrckr-todo');
$('#trackerdeal	').addClass('progtrckr-done');
</script>
<?php
}
if($ContractObj->Transaction == "Complete"){
?>
<script>
$('#trackerdeal').removeClass('progtrckr-todo');
$('#trackerdeal	').addClass('progtrckr-done');
$('#trackercomplete').removeClass('progtrckr-todo');
$('#trackercomplete	').addClass('progtrckr-done');
</script>
<?php
}
?>
<input type="hidden" class="text-box" id="UserID"  value = "<?php echo $_SESSION['ID']?>">
<input type="hidden" class="text-box" id="contractid"  value = "<?php echo $_GET['ID']?>">
<input type="hidden" class="text-box" id="usertype"  value = "<?php echo $Type?>">
<input type="hidden" class="text-box" id="Payment"  value = "<?php echo $ContractObj->PaymentMode?>">
<input type="hidden" class="text-box" id="Delivery"  value = "<?php echo $ContractObj->DeliveryMode?>">
<input type="hidden" class="text-box" id="Balance"  value = "<?php echo $_SESSION['Object']->getAccountBalance()?>">
<input type="hidden" class="text-box" id="TransactionStatus"  value = "<?php echo $ContractObj->Transaction?>">
<input type="hidden" class="text-box" id="Commission"  value = "<?php echo $_SESSION['Object']->CommissionRate()?>">

<h1 id="statusmessage" style="text-align:center;font-size:40px">Status:<?php
if($ContractObj->Status == "Deal"){
echo 'Contract agreed upon, awaiting for product to be transferred';
}
else if($ContractObj->Status == "Requested Refund"){
echo 'Buyer requested for refund,await Admin assistance';
}
else if($ContractObj->Status == "Buyer has accepted service"){
echo 'Buyer has accepted service';
}
else if($ContractObj->Status == "Seller has accepted service"){
if($ContractObj->DeliveryMode == "Courier Delivery"){
echo 'Courier has fulfilled service';
}
else{
echo 'Seller has fulfilled service';
}
}
else if($ContractObj->Status == "Buyer has accepted"){
echo 'Buyer has accepted';
}
else if($ContractObj->Status == "Seller has accepted"){
echo 'Seller has accepted';
}
else if($ContractObj->Status == "Transaction Complete"){
echo 'Transaction Complete,kindly leave a review for the other party in MyContracts Page';
}
else if($ContractObj->Status == "Order Cancelled"){
echo 'Seller has cancelled the order, sorry for any inconvenience caused,amount will be returned to the buyer , as soon as possible.';
}
else if($ContractObj->Status == "Rejected"){
echo 'Transaction declined,offer is rejected';
}
else if($ContractObj->Status == "Refunded Transaction"){
echo 'Admin intervened and refunded buyer';
}
else if($ContractObj->Status == "Admin has halted this transaction"){
echo 'Admin has halted this transaction';
}
else{
echo 'Terms are being negotiated';
}
?></h1>
<?php if($Type!="Admin"){
if($Type=="Buyer"){
?>
<h2 id="namesofusers" >Buying from <a onclick="OpenWindow(this.id)" href="" id="ProfilePage.php?ID=<?php if(isset($_SESSION['OtherUser'])){ echo $_SESSION['OtherUser'];}?>"><?php  if(isset($_SESSION['OtherUser'])){ echo $_SESSION['Object']->getUserDisplayName($_SESSION['OtherUser']); }?></a></h2>
<?php
}
if($Type=="Seller"){
?>
<h2 id="namesofusers" >Selling to <a onclick="OpenWindow(this.id)" href="" id="ProfilePage.php?ID=<?php if(isset($_SESSION['OtherUser'])){ echo $_SESSION['OtherUser'];}?>"><?php  if(isset($_SESSION['OtherUser'])){ echo $_SESSION['Object']->getUserDisplayName($_SESSION['OtherUser']); }?></a></h2>
<?php
}
}
else{
?>
<h2 id="namesofusers" >Buyer:<a onclick="OpenWindow(this.id)" href=""  id="ProfilePage.php?ID=<?php echo $ContractObj->BuyerUserID?>"><?php echo $_SESSION['Object']->getUserDisplayName($ContractObj->BuyerUserID)?></a></h2>
<h2 id="namesofusers" >Seller:<a onclick="OpenWindow(this.id)" href="" id="ProfilePage.php?ID=<?php echo $ContractObj->SellerUserID?>"><?php echo $_SESSION['Object']->getUserDisplayName($ContractObj->SellerUserID)?></a></h2>
<?php
}
?>
<div id="sharedform">
<center><h2>Product Details</h2>
<img src="<?php echo $ProductObj->Image;?>" width="300px" height="300px" style="object-fit: cover;border:1px solid purple;border-radius:5px"></br></center>
<label>ContractID:</label><b><?php echo $ContractID?></b></br>
<label>Product Name: </label><b><?php echo $ProductObj->ProductName;?></b></br>
<label>Product ID: </label><b><?php echo $ProductObj->ProductID;?></b></br>
<label>Product Owner: </label><b><?php echo $_SESSION['Object']->getUserDisplayName($ProductObj->SellerUserID)?></b></br>
<div style=" width: 500px;
  overflow: scroll;
  text-overflow: ellipsis // This is where the magic happens"><label>Product Caption: </label><b><?php echo $ProductObj->ProductCaption?></b></br>
<label>Product Description: </label><b style="width:100px"><?php echo $ProductObj->ProductDescription?></b></br>
</div>
<label>Product Initial Price: </label><b><?php echo $_SESSION['Object']->getCurrency().number_format($ProductObj->ProductInitialPrice, 2, '.', '')?></b></br>

<hr><center><h2>Contract Details</h2></center>
<div id="contractdetailsfromcontract">
<hr>
<center><h3>Contract Details from Smart Contract</h3></center>
<label><b>Status:</b></label><label id="contractstatus"></label></br>
<label><b>Amount Paid:</b></label><label id="amountpaid"></label></br>
<hr>
</div>
<?php
if($Type == "Buyer"){
echo'
<label>Date product is required by:</label>
<input type="date"  id ="DateRequired" name="DateRequired" oninput="formsyncfunction()" value="'.$ContractObj->DateRequired.'" required>';
}
if($Type == "Seller" || $Type== "Admin"){
echo'
<label>Cost per transaction:</label><b>'.number_format($_SESSION['Object']->CommissionRate()*100, 2, '.', '').'% of offer</b></br>
<label>Date product is required by:</label>
<input type="date" id ="DateRequired" name="DateRequired" oninput="formsyncfunction()" value="'.$ContractObj->DateRequired.'" readonly>';
}
?>
<br />
<label>Offer(SGD):</label>
<input type="number" id="Offer" name="Offer" min="0.00" step="any" oninput="formsyncfunction()" value="<?php echo number_format($ContractObj->NewOffer, 2, '.', '');?>" required>
<br />
<?php
echo'
<br /><br />
<label><b>Payment Mode</b></label><br>
<label for="Half">Half-Amount now:</label>
<input type="radio"  id="PaymentMode1" onclick="formsyncfunction()" name="PaymentMode" value="Half-STICoins" ><br>
<label for="female">Full-Amount now:</label>
<input type="radio"  id="PaymentMode2" onclick="formsyncfunction()" name="PaymentMode" value="Full-STICoins"><br>
<label for="FullLater">Full-Amount later:</label>
<input type="radio" id="PaymentMode3" onclick="formsyncfunction()" name="PaymentMode" value="Full-STICoins_Later">';
if($Type == "Seller" || $Type == "Admin"){
?>
<script>
$('input[name="PaymentMode"]').attr('disabled', 'disabled');
</script>
<?php
}
?>
<script>
var Payment = document.getElementById("Payment").value;
$("input[type=radio][name='PaymentMode'][value='"+Payment+"']").attr("checked", "checked");
</script>
<?php
echo'
</br><label><b>Delivery Mode</b></label><br>
<label>Self: </label><input type="radio" id="Self Delivery" onclick="formsyncfunction()" name="DeliveryMode" value="Self Delivery"><br></td>
<label>Courier: </label><input type="radio" id="Courier Delivery" onclick="formsyncfunction()" name="DeliveryMode" value="Courier Delivery"><br></td>
</tr>
';
echo'
</table>
</br>';
if($Type == "Admin"|| $Type == "Buyer" ){
?>
<script>
$('input[name="DeliveryMode"]').attr('disabled', 'disabled');
</script>
<?php
}
?>
<script>
var Delivery = document.getElementById("Delivery").value;
$("input[type=radio][name='DeliveryMode'][value='"+Delivery+"']").attr("checked", "checked");
</script>
<?php
if($Type=="Buyer" && $ContractObj->Status == "Seller has accepted service"||$Type=="Seller" && $ContractObj->Status == "Deal"){
if($Type=="Seller" && $ContractObj->DeliveryMode!="Courier Delivery"){
echo'</br><input type="button" name="service" id="service" onclick="AcceptConfirm()" style="margin-top:20px;" value="Service fulfilled">';
}
if($Type=="Buyer"){
echo'</br><input type="button" name="service" id="service" style="margin-top:20px;" onclick="AcceptConfirm()" value="Service fulfilled">';
}
}
if($Type == "Buyer" && $ContractObj->Transaction == "Complete" &&  $ContractObj->Status!="Refunded Transaction" && $ContractObj->Status!="Rejected" &&  $ContractObj->Status!="Requested Refund"|| $Type == "Buyer" && $ContractObj->Transaction == "On-Going" &&  $ContractObj->Status!="Refunded Transaction"&&  $ContractObj->Status!="Requested Refund" ){
if($ContractObj->Status!="Order Cancelled"){
echo'</br><input type="button" name="refund" id="refund" onclick="RequestRefund()" value="Request Refund">';
}
}
if($Type == "Seller" && $ContractObj->Transaction == "On-Going" && $ContractObj->Status != "Order Cancelled" && $ContractObj->Status != "Buyer has accepted service" && $ContractObj->Status != "Seller has accepted service"){
echo'</br><input type="button" name="cancel" id="cancel" onclick="CancelConfirm()" value="Cancel Order">';
}
?>
<div id="allbuttons"></div>
<button id="Accept" name="Accept" value="Accept" onclick="AcceptConfirm()">Sign Contract</button>
<button id="Reject" name="Reject" value="Reject" onclick="Reject()">Reject Contract</button>
<script>
document.getElementById('Accept').style.display = "None";
document.getElementById('Reject').style.display = "None";
</script>
<?php
if($ContractObj->Status == "Seller has accepted" || $Type=="Seller"){
?>
<script>
document.getElementById('Accept').style.display = "block";
document.getElementById('Reject').style.display = "block";
</script>
<?php
}
if($ContractObj->Status == "Seller has accepted" && $Type=="Seller"){
?>
<script>
document.getElementById("Offer").disabled = false;
</script>
<?php
}
if($Type=="Admin"){
if($ContractObj->Status == "Requested Refund"){
echo'<button id="Refund_Admin" name="Refund_Admin" value="Refund Buyer"  style="margin-top:13px;" onclick="RefundConfirm()">Refund Buyer</button>';
}
echo'<div>';
echo'<form action="UserManagementPage.php?ID='.$ContractObj->SellerUserID.'" method="post"><input type="submit" value="Ban/Suspend seller"></form>';
echo'<form action="UserManagementPage.php?ID='.$ContractObj->BuyerUserID.'"method="post"><input type="submit" value="Ban/Suspend buyer"></form>';
echo'</div>';
if($ContractObj->Status == "Admin has halted this transaction"){
echo'<form method="post"><input type="submit" name="ResumeTranasction" value="Resume Transaction"></form>';
}
}
else{
if($Type=="Buyer"){
echo'<input type="submit" onclick="OpenWindow(this.id)" id="OfferPage.php" value="Make a new contract">';
}
}
if(isset($_POST['ResumeTranasction'])){
$_SESSION['Object']->ResumeTranasction($ContractObj->ContractID,$ContractObj->Transaction);
echo'<script>location.replace("ContractManagementPage.php");</script>';
}
if(isset( $_SESSION['Object'])){
if($ContractObj->Reported==0&& $_SESSION['Object']->getAccountType()!="Administrator"){
echo'
<form method="post">
<input type="submit" name="Report" id="reportbtn" value="Report">
</form>';
}
if($ContractObj->Reported>0 && $_SESSION['Object']->getAccountType()=="Administrator"&&$_SESSION['ID']!=$ContractObj->BuyerUserID&&$_SESSION['ID']!=$ContractObj->SellerUserID){
echo'
<form method="post">
<input type="submit" name="Unreport" id="reportbtn" value="Unreport">
</form>';
}
}
echo'<br><br>';
?>
</div>
<div id="chatbox" style="border-radius:20px">
<div id="message-box" >
<?php
$_SESSION['Object']->RetrieveChat($ContractObj->ContractID)?>
</div>
<input  type="text" id="message-input" placeholder="Type and press enter to send message" onkeyup="handleKeyUp(event)">
</div>
<?php
if($Type=="Admin" ||$ContractObj->Transaction == "On-Going" || $ContractObj->Status == "Admin has halted this transaction"|| $ContractObj->Status == "Buyer has accepted" && $Type == "Buyer" || $ContractObj->Status == "Rejected" ||  $ContractObj->Status == "Order Cancelled" || $ContractObj->Status ==  "Requested Refund" || $ContractObj->Status == "Deal" ||$ContractObj->Status == "Buyer has accepted service" || $ContractObj->Status == "Seller has accepted service"){
?>
<script>
document.getElementById('Accept').style.display = "None";
document.getElementById('Offer').disabled = true;
$('input[name="PaymentMode"]').attr('disabled', 'disabled');
$('input[name="DeliveryMode"]').attr('disabled', 'disabled');
</script>
<?php
}
if($Type=="Admin" || $ContractObj->Status == "Admin has halted this transaction"|| $ContractObj->Status == "Buyer has accepted" && $Type == "Seller" || $ContractObj->Status == "Rejected" || $ContractObj->Status ==  "Requested Refund" ||$ContractObj->Status == "Order Cancelled" ||  $ContractObj->Status == "Deal" || $ContractObj->Status == "Seller has accepted service" || $ContractObj->Status == "Buyer has accepted service" || $ContractObj->Status == "Transaction Complete" ){
?>
<script>
document.getElementById('Reject').style.display = "None";
</script>
<?php
}
if($ContractObj->Status == "Seller has accepted" && $Type == "Seller" || $ContractObj->Status == "Buyer has accepted" && $Type == "Seller"){
?>
<script>
document.getElementById('Accept').style.display = "None";
document.getElementById('Offer').disabled = true;
</script>
<?php
}
if($ContractObj->Transaction == "Negotiating"){
?>
<script>
</script>
<?php
}
else{
if($ContractObj->DeliveryMode =="Courier Delivery" && $Type == "Seller"){
?>

<div id="courierlink">
<h1  style="margin-top:10%">Courier Link</h1>
<h2>Please do not give this link to anybody except your courier</h2>
<h5>TempID:<?php echo $_SESSION['Object']->generateCourierLink($ContractID) ?></h5>
<b style="color:red"><?php echo "https://01d3f4096b3e.ngrok.io/CourierPage.php?ID=".$_SESSION['Object']->generateCourierLink($ContractID);?></b>
</div>
<?php
}
?>
<script>
document.getElementById('Accept').style.display = "None";
document.getElementById('Reject').style.display = "None";
document.getElementById('Offer').disabled = true;
document.getElementById('DateRequired').disabled = true;
$('input[name="PaymentMode"]').attr('disabled', 'disabled');
$('input[name="DeliveryMode"]').attr('disabled', 'disabled');
</script>
<?php
}
?>
</div>
<script src="https://smtpjs.com/v3/smtp.js"></script>
<script>
webportconfig = 'wss://fddaa3b4a543.ngrok.io';
window.WebSocket = window.WebSocket || window.MozWebSocket;
function OpenWindow(ID){
window,opener.close();window.open(ID);
}
var objDiv = document.getElementById("message-box");
var styleElement = document.createElement("style");
styleElement.appendChild(document.createTextNode("div ::-webkit-scrollbar {-webkit-appearance: none;width:6px;}div ::-webkit-scrollbar-thumb {border-radius: 50px;background-color:purple;-webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);margin-top:50px;} div::-webkit-scrollbar-track-piece:start {background: transparent;margin-top: 10px;}"));
objDiv.appendChild(styleElement);
var ajax = new XMLHttpRequest();
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//initialise all required variables from php -> Js
document.getElementById("message-box").scrollTop = document.getElementById("message-box").scrollHeight;
var cancelled = false;
var adminrefund = false;
var User = document.getElementById("UserID").value.trim(),
ContractID =  document.getElementById("contractid").value.trim(),
Balance = Number(document.getElementById("Balance").value),
messageInput = document.getElementById("message-input"),
UserType =  document.getElementById("usertype").value.trim();
PaymentType = document.getElementById("Payment").value;
TransactionStatus = document.getElementById("TransactionStatus").value;
Comission = document.getElementById("Commission").value;
var Offer =  document.getElementById("Offer").value.trim();
Comission = Offer*Comission;
var Delivery = document.getElementById("Delivery").value;
console.log(Comission);
if(TransactionStatus!="Negotiating" && UserType == "Admin"){
console.log(UserType);
}
/////////////////////////////////////////
function handleKeyUp(e) {
if (e.keyCode === 13) {
sendMessage();
}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function sendMessage() {
var message = messageInput.value.trim();
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("User=" + User + "&message=" + message + "&contractid=" + ContractID + "&usertype=" + UserType );
messageInput.value = "";
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function formsyncfunction(){
var Offer =  document.getElementById("Offer").value.trim();
DateRequired =  document.getElementById("DateRequired").value.trim();
var PaymentMode = $('input[name=PaymentMode]:checked').val();
var DeliveryMode =  $('input[name=DeliveryMode]:checked').val();
if(UserType=="Buyer"){
document.getElementById('Accept').style.display = "none";
document.getElementById('Reject').style.display = "none";
}
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("User=" + User + "&offer=" + Offer + "&daterequired=" + DateRequired + "&paymentmode=" + PaymentMode + "&contractid=" + ContractID + "&usertype=" + UserType + "&delivery=" + DeliveryMode);
document.getElementById('statusmessage').innerHTML = "Status:Terms are being negotiated";
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function AcceptConfirm(){
document.getElementById('confirmation2').style.display = "block";
}
function emailverification(email){
if(UserType=="Seller"){
AcceptConfirmed(TransactionStatus);
return;
}
if(UserType=="Buyer")
{
if (document.getElementById('PaymentMode3').checked && TransactionStatus =="Negotiating") {
AcceptConfirmed(TransactionStatus);
return;
}
if (document.getElementById('PaymentMode2').checked && TransactionStatus =="On-Going") {
AcceptConfirmed(TransactionStatus);
return;
}
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Email=" + email);
document.getElementById('confirmation2').style.display = "none";
document.getElementById('OTP').style.display = "block";
}
}
function ResendOTP(email){
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Email=" + email);
alert("Resent OTP to your email!");
}
function VerifyOTP(){
document.getElementById('OTPform').style.display = "none";
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
OTPEntry = document.getElementById('OTPinput').value
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("OTP=" + OTPEntry + "&contractid=" + ContractID  );
}
function AcceptConfirmed(TransactionStatus){
if(cancelled){
CancelOrder();
return;
}
if(adminrefund){
Refund_Admin();
return;
}
if(TransactionStatus =="Negotiating"){
Accept();
}
if(TransactionStatus =="On-Going"){
AcceptService();
}
}
function Accept(){
document.getElementById('confirmation2').style.display = "none";
if(document.getElementById('PaymentMode2').checked){

if(UserType =="Seller"){
SendAccept();

}
}
if(document.getElementById('PaymentMode1').checked){

if(UserType =="Seller"){
SendAccept();

}
}
if(document.getElementById('PaymentMode2').checked){
if(UserType =="Seller"){
SendAccept();
}
}
if(document.getElementById('PaymentMode3').checked){
SendAccept();
}
if(document.getElementById('PaymentMode2').checked && UserType =="Buyer"){
if(Balance < Number(document.getElementById("Offer").value)){
alert("You have insufficient balance,please top up");
window.open("ConvertPage.php");
location.reload();
}
else{
SendAccept();
}
}
if(document.getElementById('PaymentMode1').checked && UserType =="Buyer"){
if(Balance < (Number(document.getElementById("Offer").value)/2)){
alert("You have insufficient balance,please top up");
window.open("ConvertPage.php");
location.reload();
}
else{
SendAccept();
}
}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function SendAccept(){
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
document.getElementById('Accept').style.display = "None";
document.getElementById('Reject').style.display = "None";
document.getElementById("Offer").disabled = true;
document.getElementById("DateRequired").disabled = true;
$('input[name="PaymentMode"]').attr('disabled', 'disabled');
$('input[name="DeliveryMode"]').attr('disabled', 'disabled');
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Accept=" + User + "&contractid=" + ContractID + "&usertype=" + UserType + "&User=" + User);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function Reject(){
document.getElementById('Accept').style.display = "None";
document.getElementById('Reject').style.display = "None";
document.getElementById("Offer").disabled = true;
document.getElementById("DateRequired").disabled = true;
$('input[name="PaymentMode"]').attr('disabled', 'disabled');
$('input[name="DeliveryMode"]').attr('disabled', 'disabled');
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Reject=" + User + "&contractid=" + ContractID );
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("message=" + " has rejected the offer,Transaction declined" + "&contractid=" + ContractID + "&usertype=" + UserType + "&User=" + User );
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function AcceptService(){
document.getElementById('confirmation2').style.display = "none";
if(document.getElementById('PaymentMode3').checked){

if(UserType =="Seller"){
SendAcceptService();
}

}
if(document.getElementById('PaymentMode1').checked){

if(UserType =="Seller"){
SendAcceptService();

}
}
if(document.getElementById('PaymentMode2').checked){
SendAcceptService();
}
if(document.getElementById('PaymentMode3').checked && UserType =="Buyer"){
if(Balance < Number(document.getElementById("Offer").value)){
alert("You have insufficient balance,please top up");
window.open("ConvertPage.php");
location.reload();
}
else{
SendAcceptService();
}
}
else if(document.getElementById('PaymentMode1').checked && UserType =="Buyer"){
if(Balance < (Number(document.getElementById("Offer").value)/2)+3){
alert("You have insufficient balance,please top up");
window.open("ConvertPage.php");
location.reload();
}
else{
SendAcceptService();
}
}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function SendAcceptService(){
if(document.getElementById('service') != null){
document.getElementById('service').style.display = "none";
}
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("AcceptService=" + User + "&contractid=" + ContractID + "&usertype=" + UserType + "&User=" + User);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function RequestRefund(){
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Refund=" + User + "&contractid=" + ContractID + "&usertype=" + UserType + "&User=" + User);
location.reload();
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function CancelConfirm(){
document.getElementById('confirmation3').style.display = "block";
cancelled = true;
}
function emailverificationCancel(email){
if (document.getElementById('PaymentMode3').checked && TransactionStatus =="On-Going") {
return;
}
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Email=" + email);
document.getElementById('confirmation3').style.display = "none";
document.getElementById('OTP').style.display = "block";
}
function CancelOrder(){
if(document.getElementById('PaymentMode2').checked && UserType =="Seller"){
if(Balance < Number(document.getElementById("Offer").value)){
alert("You have insufficient balance,please top up");
window.open("ConvertPage.php");
}
else{
SendCancelOrder();
}
}
else if(document.getElementById('PaymentMode1').checked && UserType =="Seller"){
if(Balance < (Number(document.getElementById("Offer").value)/2)){
alert("You have insufficient balance,please top up");
window.open("ConvertPage.php");
}
else{
SendCancelOrder();
}
}
else{
SendCancelOrder();
}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function SendCancelOrder(){
setInterval(function() {
location.reload();
}, 70000);
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Cancel=" + User + "&contractid=" + ContractID + "&usertype=" + UserType + "&User=" + User);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function RefundConfirm(){
document.getElementById('confirmation4').style.display = "block";
adminrefund = true;
}
function emailverificationRefund(email){
if (document.getElementById('PaymentMode3').checked && TransactionStatus =="On-Going") {
return;
}
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Email=" + email);
document.getElementById('confirmation4').style.display = "none";
document.getElementById('OTP').style.display = "block";
}
function Refund_Admin(){
if(document.getElementById('PaymentMode2').checked||document.getElementById('PaymentMode3').checked){

SendRefund_Admin();

}
if(document.getElementById('PaymentMode1').checked){

SendRefund_Admin();

}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function SendRefund_Admin(){
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
document.getElementById('Refund_Admin').style.display = "none";
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("RefundAdmin=" + User + "&contractid=" + ContractID + "&usertype=" + UserType + "&User=" + User);
var ajax = new XMLHttpRequest();
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function checkserviceaccepted(){
if(UserType == "Buyer"){
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("&CheckServiceAccepted=" + ContractID+ "&usertype=" + UserType+"&paymenttype=" +PaymentType);
console.log(ajax);
}
if(UserType == "Seller"){
location.reload();
}
}
function checkaccepted(){
if(UserType == "Buyer"){
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
var ajax = new XMLHttpRequest();
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("&CheckAccepted=" + ContractID+ "&usertype=" + UserType+"&paymenttype=" +PaymentType);
console.log(ajax);
}
if(UserType == "Seller"){
location.reload();
}
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var connection =  new WebSocket(webportconfig);
connection.onmessage = function (message) {
var data = JSON.parse(message.data);
console.log(data);
if(data.ContractID==document.getElementById("contractid").value.trim()){
if (typeof data.message != 'undefined') {
 document.getElementById("message-box").scrollTop = document.getElementById("message-box").scrollHeight;
// the variable is defined
var div = document.createElement("div");
var divOuter = document.createElement("div");
var author = document.createElement("span");
author.className = "author";
if(data.Type=="Admin"){
author.innerHTML = data.User+"(Administrator):";
}
else{
author.innerHTML = data.User+"</br>";
}
var message = document.createElement("span");
message.className = "messsage-text";
message.innerHTML = data.message;
if(data.User == User){
div.setAttribute("id", "User1");
}
else{
div.setAttribute("id", "User2");
}
if(data.User == User){
divOuter.setAttribute("id", "User1Outer");
}
else{
divOuter.setAttribute("id", "User2Outer");
}
div.appendChild(author);
div.appendChild(message);
divOuter.appendChild(div);
document.getElementById("message-box").appendChild(divOuter);
document.getElementById("message-box").scrollTop = document.getElementById("message-box").scrollHeight;
}
if (typeof data.offer != 'undefined') {
location.reload();
document.getElementById("DateRequired").disabled = false;
if(UserType=="Buyer"){
document.getElementById('PaymentMode3').disabled = false;
document.getElementById('PaymentMode2').disabled = false;
document.getElementById('PaymentMode1').disabled = false;
}
document.getElementById('Offer').value = data.offer;
document.getElementById('DateRequired').value = data.daterequired;
$("input[type=radio][name='PaymentMode'][value='"+data.paymentmode+"']").attr("checked", "checked");
$("input[type=radio][name='DeliveryMode'][value='"+	data.deliverymode+"']").attr("checked", "checked");
}
if (typeof data.REPLY != 'undefined') {
if (data.REPLY == 'CheckAccepted')
{
if(data.Deal=="set")
{
location.reload();
}
if(data.Deal=="error")
{
document.getElementById('statusmessage').innerHTML = "Server Error";
}
}
if (data.REPLY == 'CheckServiceAccepted') {
if(data.DealComplete=="set"){
location.reload();
}
if(data.DealComplete=="error")
{
document.getElementById('statusmessage').innerHTML = "Server Error";
}
}
if(data.REPLY=="Reject"){
document.getElementById('Accept').disabled = true;
document.getElementById('Reject').disabled = true;
document.getElementById("Offer").disabled = true;
document.getElementById("DateRequired").disabled = true;
document.getElementById('PaymentMode3').disabled = true;
document.getElementById('PaymentMode2').disabled = true;
document.getElementById('PaymentMode1').disabled = true;
document.getElementById('statusmessage').innerHTML = "Status:Offer Rejected, Transaction declined.";
location.reload();
}
if(data.REPLY=='Cancel'){
document.getElementById('Accept').disabled = true;
document.getElementById('Reject').disabled = true;
document.getElementById("Offer").disabled = true;
document.getElementById("DateRequired").disabled = true;
document.getElementById('PaymentMode3').disabled = true;
document.getElementById('PaymentMode2').disabled = true;
document.getElementById('PaymentMode1').disabled = true;
document.getElementById('statusmessage').innerHTML = "Status:Seller has cancelled the order, sorry for any inconvenience caused,amount will be returned to the buyer , as soon as possible.";
location.reload();
}
if(data.REPLY=='Refund'){
document.getElementById('Accept').disabled = true;
document.getElementById('Reject').disabled = true;
document.getElementById("Offer").disabled = true;
document.getElementById("DateRequired").disabled = true;
document.getElementById('PaymentMode3').disabled = true;
document.getElementById('PaymentMode2').disabled = true;
document.getElementById('PaymentMode1').disabled = true;
document.getElementById('statusmessage').innerHTML = "Status:Buyer refunded,await Admin assistance.";
location.reload();
}
if (data.REPLY == 'AcceptService') {
checkserviceaccepted();
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
if(UserType == "Seller"){
document.getElementById('cancel').style.display = "none";
}
document.getElementById('statusmessage').innerHTML = "Status:"+ data.Type + " has accepted service";
location.reload();
}
if (data.REPLY == 'AcceptOffer') {
checkaccepted();
document.getElementById('contractgui').style.display = "none";
document.getElementById('loadergui').style.display = "block";
document.getElementById('statusmessage').innerHTML = "Status:"+ data.Type + " has accepted offer";
location.reload();
}
if (data.REPLY == 'AdminRefund') {
document.getElementById('statusmessage').innerHTML = "Buyer has been refunded";
location.reload();
}
if (data.REPLY == 'OTPResult') {
if(User == data.User){
console.log(data.Result);
if(data.Result == "Success"){
document.getElementById('OTP').style.display = "none";
AcceptConfirmed(TransactionStatus);
}
if(data.Result == "Failed"){
alert("Invalid OTP code");
location.reload();
}
if(data.Result == "LogOut"){
alert("Max Attempt reached,you will be logged out");
ajax.open("POST", "ContractPageController.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Logout=" + ContractID);
location.replace("index.php");
}
}
}
if (data.REPLY == 'ContractInformation') {
document.getElementById('contractdetailsfromcontract').style.display = "block";
document.getElementById('contractstatus').innerHTML = data.contractdata.Status;
document.getElementById('amountpaid').innerHTML = Currency+(data.contractdata.Paid/100).toFixed(2);
console.log(data.contractdata);
}
}
}
}


</script>
<?php if($ContractObj->Status == "Buyer has accepted service"){
?>
<script>
checkserviceaccepted();
setInterval(function() {
location.reload();
}, 10000);
</script>
<?php
}
if($ContractObj->Status == "Buyer has accepted"){
?>
<script>
checkaccepted();
setInterval(function() {
location.reload();
}, 10000);
</script>
<?php
}
}
if(isset($_POST['Confirmationreport'])&& $_POST['Confirmationreport']=="No"){
exit();
header("Refresh:0");
}
if(isset($_POST['Confirmationreport'])&&$_POST['Confirmationreport']=="Yes"){
$_SESSION['Object']->ReportContract($_GET['ID']);
echo '<script> alert("Thank you for your vigilance,rest assured, the administrators will look into the matter");</script> ';
echo '<script> location.replace("index.php")</script> ';
}
if(isset($_POST['Confirmationunreport'])&& $_POST['Confirmationunreport']=="No"){
exit();
header("Refresh:0");
}
if(isset($_POST['Confirmationunreport'])&&$_POST['Confirmationunreport']=="Yes"){
$_SESSION['Object']->UnreportContract($_GET['ID']);
echo '<script> alert("Contract unreported");</script> ';
echo '<script> location.replace("index.php")</script> ';
}
?>
</body>