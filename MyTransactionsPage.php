<?php require_once("NavBar.php");

if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}


?>
<style>

.Sent_GUI{
	display:none;
}
.card {
	box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
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
button {
	border:none;
	background-color:purple;
	color:white;
	font-size:20px;
	border-radius:10px;
	margin-right:10px;
}
button:hover {
	outline:60%;
	filter: drop-shadow(0 0 5px purple);
}
</style>
<center>
	<button class="Recieved" id="Receiver"   disabled>Recieved Transaction</button>
	<button class="Sent" id="Sender" >Sent Transaction</button>
</center>
<div class="Recieved_GUI">
	<center><h1>Recieved Transactions</h1></center>
	<?php
	$array = $_SESSION['Object']->ListOfTransactions('Receiver');
	if(!empty($array)){
	foreach ($array as &$ID) {
	?>
	<div class="card">
		<h2>TransactionID:<?php echo  $ID ?></h2>
		<hr>
		<b style="color:green">Amount:<?php echo   " SGD$".number_format($_SESSION['Object']->getTransactionAmount($ID)/100, 2, '.', '')?></b>
		<b>Sender:<?php echo  $_SESSION['Object']->getTransactionSender($ID) ?></b>
		<b>Reciever:<?php echo $_SESSION['Object']->getTransactionReceiver($ID) ?></b>
		<b>Transaction Date:<?php  echo $_SESSION['Object']->getTransactionDate($ID) ?></b>	
		<b>Transaction Title:<?php  echo$_SESSION['Object']->getTransactionTitle($ID) ?></b>	
	</div>
	</br>
<?php
	}
}
else{
	echo'<h2>You currently have no transactions under this section</h2>';
}
?>
</div>

<div class="Sent_GUI">
<center><h1>Sent Transactions</h1></center>
<?php
$array = $_SESSION['Object']->ListOfTransactions('Sender');
if(!empty($array)){
foreach ($array as &$ID) {
?>
<div class="card">
<h2>TransactionID:<?php echo  $ID ?></h2><hr>
<b style="color:red">Amount:<?php echo   " SGD$".number_format($_SESSION['Object']->getTransactionAmount($ID)/100, 2, '.', '')?></b>
<b>Sender:<?php echo  $_SESSION['Object']->getTransactionSender($ID) ?></b>
<b>Reciever:<?php echo $_SESSION['Object']->getTransactionReceiver($ID) ?></b>
<b>Transaction Date:<?php  echo $_SESSION['Object']->getTransactionDate($ID) ?></b>	
<b>Transaction Title:<?php  echo$_SESSION['Object']->getTransactionTitle($ID) ?></b>	
</div></br>
<?php
}
}
else{
	echo'<h2>You currently have no transactions under this section</h2>';
}
?>
</div>



<script >

var table;
$(document).ready(function(){
  $(".Recieved").click(function(){
	$('.Recieved').attr('disabled','disabled');
	$('.Sent').removeAttr('disabled');
    $(".Recieved_GUI").show();
	$(".Sent_GUI").hide();

  });
    $(".Sent").click(function(){
	$('.Sent').attr('disabled','disabled');
	$('.Recieved').removeAttr('disabled');
    $(".Recieved_GUI").hide();
	$(".Sent_GUI").show();
  });


});




</script>

<?php require_once("Footer.php");?>