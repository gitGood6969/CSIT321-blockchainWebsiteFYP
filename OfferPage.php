<?php require_once("NavBar.php");

if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}
if(!isset($_SESSION['Temp_Product'])){
	echo '<script> location.replace("index.php")</script> ';
}
$submit = true;
$ProductID = $_SESSION['Temp_Product'];
$DateRequiredError = $OfferError = "";
$Owner = $_SESSION['Object']->getProductOwner($ProductID);
$ProductObj = new Products();
$ProductObj->InitialiseProduct($ProductID);

if(isset($_POST['submit'])){


if (strlen(strtotime($_POST["DateRequired"]))<1){
	
	$DateRequiredError = "Date product is required by is required";	
	$submit = false;
}
else{
	if (strtotime($_POST["DateRequired"])<=strtotime('Today')){
		$DateRequiredError = "Invalid date, date should be after today ";	
		$submit = false;
	}
}



if (empty($_POST["Offer"])){
	$OfferError= "Offer is required";	
	$submit = false;
}
	
}
	

?>
<style>

label,span{
	display:inline-block;
	width:200px;
	margin-right:5px;
	margin-left:2%;
	text-align:center;
}
.Offer_GUI h1,h2{
	margin-left:2%;
}
.Insert_GUI input[type="submit"]{
	font-size:30px;
	color:white;
	background-color:black;
}
span{
	color:red;
		width:200px;
}
hr{
	background-color:white;
}
input[type="text"]{
	font-family: arial;
	width:200px;
}

.Offer_GUI{
	width:1200px;
	height:800px;
	margin:auto;
		border-radius:20px;
	box-shadow:5px 5px gray;
		border:1px solid black;
}
button,input[type=submit] {
	
	border:none;
	background-color:purple;
	color:white;
	font-size:30px;
	border-radius:10px;
	margin-right:10px;
}
input[type=submit]:hover {
	
	 outline:60%;
    filter: drop-shadow(0 0 5px purple);
}
</style>
<div class="Offer_GUI">
<h1 style="font-size:70px"><center>Offer for <?php echo $ProductObj->ProductName;?></center></h1>	
    <form method="post" enctype="multipart/form-data">  
	
	<h2>Product Information</h2>
		<div style="width:1000px;margin:auto">
		<center><img src="<?php echo $ProductObj->Image;?>" style="width:50%;margin:auto;object-fit: cover;" width="200" height="300"></br></center>
	
		<label>Product ID:</label><b><?php echo $ProductObj->ProductID;?></b></br>
		<label>Product Owner:</label><b><?php echo $_SESSION['Object']->getUserDisplayName($ProductObj->SellerUserID)?></b></br>
		<label>Product Initial Price:</label><b><?php echo $_SESSION['Object']->getCurrency().$ProductObj->ProductInitialPrice?></b></br>
		</div>
	<hr>
		<h2>Your Offer</h2>
		<label>Date product is required by:</label>
		<input type="date" name="DateRequired" >
		<span class="error">&nbsp;&nbsp;<?php echo $DateRequiredError;?></span><br /><br />
	
		
		<label>Offer(<?php echo $_SESSION['Object']->getCurrency()?>):</label>
		<input type="number" id="Offer" name="Offer" min="0.00" step="any">
		<span class="error"><?php echo $OfferError;?></span><br />
		<br />
		
		<input type="submit" name="submit" style="float:right;margin-right:2%;" value="Submit">
    </form>
</div>

<?php

if(isset($_POST['submit'])&& $submit){
	echo'<style> .Offer_GUI{display:none;}</style>';
	$ContractID = $_SESSION['Object']->NewOffer($_POST["Offer"],$_POST["DateRequired"],$ProductObj->SellerUserID,$ProductObj->ProductID,$ProductObj->ProductInitialPrice);
	echo'<script>alert("Successfully sent offer to user");</script>
		 <script> location.replace("MyContractsPage.php")</script>';
	exit();
	
}

 require_once("Footer.php");?> 
