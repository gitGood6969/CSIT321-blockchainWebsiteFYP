<?php
require_once("NavBar.php");
$ContractObj = new Contracts();
$ContractID = $ContractObj->getContractID($_GET['ID']);
if(!$ContractObj->initialiseContract($ContractID)){
	echo '<script> location.replace("index.php")</script> ';
}
if($ContractObj->DeliveryMode != "Courier Delivery"){
		echo '<script> location.replace("index.php")</script> ';	
	
}
if($ContractObj->Status == "Seller has accepted service"){
		echo '<script> location.replace("index.php")</script> ';	
	
}

$Type = "Seller";
?>


<style type="text/css">

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
button,input[type=submit],input[type=button] {
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
input[type=button]:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
button:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
</style>
<input type="hidden" class="text-box" id="UserID"  value = "<?php echo $ContractObj->SellerUserID?>">
<input type="hidden" class="text-box" id="contractid"  value = "<?php echo $ContractID ?>">
<input type="hidden" class="text-box" id="usertype"  value = "<?php echo $Type?>">
<input type="hidden" class="text-box" id="TempID"  value = "<?php echo $_GET['ID']?>">
<div id="loadergui">
<div id="loader"></div>
<div id="loaderimage"></div>
</div>

<div id="couriergui">
<hr><center><h2>Courier Delivery Details</h2></center></br>
<Label>TemporaryID:</Label><?php echo $_GET['ID'] ?></br>
<Label>DeliveryMode:</Label><b>Courier</b></br>
<hr>
<label>By clicking this button, you declare that you are the courier and that the product has been successfully delivered.</label>
</br>
<form method="post">
<?php
echo'<input type="submit" name="service" id="service"  value="Service fullfilled">';
?>
</form>
</div>
<?php
if(isset($_POST['service'])){
echo"
<script>
document.getElementById('couriergui').style.display = 'none';
document.getElementById('loadergui').style.display = 'block';
</script>
";

$BaseUserOBJ = new BaseUser("courier update");
$BaseUserOBJ->CourierAcceptService($ContractID);
$BaseUserOBJ->CourierUpdate($_GET['ID']);
echo"
<script>
alert('Thank you for your service');
location.reload();
</script>
";
}

?>
