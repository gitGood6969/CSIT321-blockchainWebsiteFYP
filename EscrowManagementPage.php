<?php require_once("NavBar.php");?>
<style>
table, th, td {
border: 1px solid black;
}

span{

color:red;
}
.ReportedProduct_GUI{
display:none;
}
.ReportedContract_GUI{
display:none;
}
.AddGUI{
display:none;
width:700px;
height:250px;
text-align:center;
margin:auto;
border:1px solid black;
border-radius:20px;
box-shadow:5px 5px gray;
}
.AddGUI input[type="submit"]{
margin-right:5%;
float:right;

}
.AddGUI input[type="text"]{
margin-top:3%;
text-align:center;

}
#AllReports{

background-color:white;
width:1700px;

color:purple;
border:1px solid grey;
margin:auto;
text-align:center;
}
table,tr,th,td{

border:1px solid #e8e6e6; 
}
tr{
height:50px;
vertical-align: text-bottom;
}
button,input[type=submit],input[type=button] {
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

<div><center><h1>Escrow Management</h1><center><hr>
<table>
<tr>

<th>Escrow Account</th>
<th>Action</th>

</tr>
<?php 

if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}
if($_SESSION['Object']->getAccountType()!="Administrator"){
	echo '<script> location.replace("index.php")</script> ';
}

$ArrayOFEscrows = $_SESSION['Object']->ListOfEscrows();
for($x = 0;$x<sizeof($ArrayOFEscrows);$x++){
	echo'<tr><td>'.$ArrayOFEscrows[$x].'</td>
	<td><form method="post">
	<input type="submit" name="Remove" value="Remove">
	<input type="hidden" name="PubKey" value="'.$ArrayOFEscrows[$x].'">
	</form></td></tr>
	';
}
if(sizeof($ArrayOFEscrows)==0){
echo'<td colspan="2">Add Escrow Account</td>';	
}
if(isset($_POST['Remove'])){
		$_SESSION['Object']->RemoveEscrow($_POST['PubKey']);
		echo '<script> location.replace("EscrowManagementPage.php")</script> ';
		exit();
}
$PubKeyErr = $PrivateKeyErr = '';
$Submit = false;
if(isset($_POST['AddSubmit'])){
	if(empty($_POST['PublicKeyInput'])){
		$PubKeyErr  = "Please enter public key";
	}
	else{
		if(!$_SESSION['Object'] -> checkAccountInNetwork($_POST['PublicKeyInput'])){
			$PubKeyErr  = "Public key is invalid";
		}
		else{
			
			$Submit = false;		
		}
	
	}
	if(empty($_POST['PrivateKeyInput'])){
		$PrivateKeyErr  = "Please enter private key";
	}
	else{
		if(strlen($_POST["PrivateKeyInput"])<64){
			$PrivateKeyErr = "Private key is invalid";
		}
		else{
			$Submit = true;
		}
	
	}
	echo"<style>.AddGUI{display:block}</style>";
	echo"<style>.AddButton{display:none}</style>";
	
	if($Submit){
		
		$_SESSION['Object'] ->AddEscrow($_POST['PublicKeyInput'],$_POST["PrivateKeyInput"]);
		echo '<script> location.replace("EscrowManagementPage.php")</script> ';
		exit();
	}
}
else{
	echo"<style>.AddButton{display:block}</style>";
}
?>
</table>
</div>
<script>
$(document).ready(function(){
  $(".AddButton").click(function(){
    $(".AddGui").show();
	  $(".AddButton").hide();
  });
});
</script>
<center>
<br /><br />
<button class="AddButton">Add</button>
<div class="AddGUI">
<form method="post">
  <label>Public Key:</label>
  <input type="text"  name="PublicKeyInput"><br>
  <span class="error"><?php echo $PubKeyErr ?></span><br /><br />
  <label>Private Key:</label>
  <input type="text" name="PrivateKeyInput"><br><br>
  <span class="error"><?php echo $PrivateKeyErr?></span><br />
  <input type="submit" name="AddSubmit" value="Add">

</form> 
</div>
</center>
<?php require_once("Footer.php");?>