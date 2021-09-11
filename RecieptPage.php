<?php
require_once("NavBar.php");
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
	if($_SESSION['Object']->getAccountType()!="Administrator"){
	echo '<script> location.replace("index.php")</script> ';
}
}
$TransactionsArr = json_decode($ContractObj->TransactionID,true);

$ProductObj = new Products();
$ProductObj->InitialiseProduct($ContractObj->ProductID);
?>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
.card {

  transition: 0.3s;
  width: 80%;
  margin:auto;
 
}

.card:hover {
  box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.container {
  padding: 2px 16px;
}
#invoicecontainer{
	width:1200px;
	margin:auto;
color:black;
	box-shadow:5px 5px gray;
		border:1px solid black;
}
label,h2{
margin-left:10%;
}

</style>


<div id="invoicecontainer">

<img src="systemimages/CompanyLogo.jpg" width="200px" height="100px" style="style=float:left"><h1>Invoice</h1>

<h2>Details</h2>
<label>ContractID:</label><b><?php echo $ContractID?></b></br>
<label>Seller:</label><b><?php echo $ContractObj->SellerUserID ?></b></br>
<label>Buyer:</label><b><?php echo $ContractObj->BuyerUserID ?></b></br>
<label>Product Name: </label><b><?php echo $ProductObj->ProductName;?></b></br>
<label>Product ID: </label><b><?php echo $ProductObj->ProductID;?></b></br>
<label>Product Owner: </label><b><?php echo $_SESSION['Object']->getUserDisplayName($ProductObj->SellerUserID)?></b></br>
<label>Total Price: </label><b><?php echo $_SESSION['Object']->getCurrency().number_format($ContractObj->NewOffer, 2, '.', '')?></b></br>
<label>Delivery Mode: </label><b><?php echo $ContractObj->DeliveryMode?></b></br>

<hr>
<h2>Transactions</h2>
<?php
for($x=0;$x<sizeof($TransactionsArr);$x++) {

?>

<div class="card">
<h5>TransactionID:<?php echo  $TransactionsArr[$x][0] ?></h5><hr>
<b>ContractID:<?php echo   $ContractObj->ContractID; ?></b>
<b>ProductID:<?php echo   $ContractObj->ProductID; ?></b>
<b>Amount:<?php echo   " SGD$".number_format($TransactionsArr[$x][2], 2, '.', '') ?></b>
<b>Sender:<?php echo  $TransactionsArr[$x][3] ?></b>
<b>Reciever:<?php echo $TransactionsArr[$x][4] ?></b>
<b>Transaction Date:<?php  echo $TransactionsArr[$x][1]?></b>
</div></br>
<?php
}
?>
</div>
<?php require_once("Footer.php");?>