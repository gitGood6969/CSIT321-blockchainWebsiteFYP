<?php require_once("NavBar.php");
$ProductID = $_GET['ID'] ;
$BaseUserOBJ = new BaseUser("View Product");	
$ProductObj = new Products();

if(isset($_SESSION['ID'])){
$Owner = $_SESSION['Object']->getProductOwner($ProductID);
$_SESSION['Object']->RemoveNotificationInPage('ProductPage.php?ID='.$ProductID);
}
if(!$ProductObj->InitialiseProduct($ProductID)){
	echo '<script> location.replace("index.php")</script> ';
}

?>

<style>
#confirmation{

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
#container{
width:1500px;
height:800px;
background-repeat: no-repeat; /* Do not repeat the image */
background-size: cover; /* Resize the background image to cover the entire container */

background-attachment: fixed;
background-position:  50% 50%;

filter: drop-shadow(0 0 5px grey);
margin:auto;
margin-top:2%;


}
#buttonscontainer{
width:20%;
display:inline-block;
background-color:white;
opacity:0.9;
float:right;
height:100%;
font-family: 'Roboto';
color:purple;
float:right;
margin-bottom:5%;

}
#valuecontainer{
width:40%;
display:inline-block;
background-color:white;
opacity:0.9;
float:right;
height:100%;
font-family: 'Roboto';
color:purple;
word-wrap: break-word;
margin-bottom:5%;
overflow:scroll;
border:1px solid purple;
}
#valuecontainer::-webkit-scrollbar {
width: 0.2em;

}

#valuecontainer:-webkit-scrollbar-track {
-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}

#valuecontainer::-webkit-scrollbar-thumb {
background-color: black;
outline: 1px solid black;

}
#imagecontainer{
float: left;
cursor: pointer;
width:40%;

height:100%;

}
#productimage{
transition: 0.3s;
top:50%;
object-fit:cover;
width:90%;
height:100%;

filter: drop-shadow(0 0 5px black);
}
#buttonscontainer input[type=submit]{

display:inline-block;
border:none;
font-family: 'Roboto';
background-color:purple;
color:white;
height:50px;
font-size:20px;
width:200px;
margin-top:10px;
border-radius:40px;
margin-right:10px;
margin-left:5%;
float:left;

}
#deletebtn{
display:inline-block;
border:none;
font-family: 'Roboto';
background-color:purple;
color:white;
height:30px;
font-size:20px;
width:70px;

border-radius:40px;
margin-right:10px;
margin-left:5%;
float:left;
}
#deletebtn:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);

border-radius:20px;

}
#buttonscontainer input[type=submit]:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);

border-radius:20px;

}
#preview	{
width: 100%;
height: 100%;
display:none;
z-index:100;

}
#main{

}
#commentcontainer{
width:1400px;
height:400px;
margin:auto;
margin-top:2%;
overflow:scroll;
}
#commentcontainer::-webkit-scrollbar {
width: 0.2em;

}

#commentcontainer:-webkit-scrollbar-track {
-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
}

#commentcontainer::-webkit-scrollbar-thumb {
background-color: black;
outline: 1px solid black;

}
.modal {

display: none; /* Hidden by default */
position: fixed; /* Stay in place */
z-index: 1; /* Sit on top */
padding-top: 200px; /* Location of the box */
left: 0;
top: 0;
width: 100%; /* Full width */
height: 100%; /* Full height */
overflow: auto; /* Enable scroll if needed */
background-color: rgb(0,0,0); /* Fallback color */
background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
margin: auto;
display: block;
width: 80%;

max-width: 700px;
}



/* Add Animation */
.modal-content, #caption {  
-webkit-animation-name: zoom;
-webkit-animation-duration: 0.6s;
animation-name: zoom;
animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
from {-webkit-transform:scale(0)} 
to {-webkit-transform:scale(1)}
}

@keyframes zoom {
from {transform:scale(0)} 
to {transform:scale(1)}
}

/* The Close Button */
.close {
width: 100%; /* Full width */
height: 100%;
}

.close:hover,
.close:focus {
color: #bbb;
text-decoration: none;
cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
.modal-content {
width: 100%;
}
}
.card {
font-family: 'Roboto';font-size: 22px;
border: 2px solid purple;
border-radius: 25px;
font-size:5px;
overflow:hidden;
background-color:white;
margin: auto;
text-align: center;
float:left;
display: inline-block;
margin-left:50px;
margin-top:50px;
height:320px;
width:300px;
margin-bottom:50px;

}
.card:hover {
box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 1);
}



.card image{
position: absolute;
top:    0;
bottom:   0;	

}
.card:hover {
cursor:pointer;
transform: scale(1.5); 
z-index:1;
}

.text {

background-color: white;
color:purple;
font-size: 16px;
width:80%;
margin:auto;
}
.Rec_Prod_GUI{

width: 1500px;
display:inline;
margin:auto;
border-radius:20px;
background-color:#23084D ;
display: flex;
justify-content: center;
align-items: center;
color:white;
background-repeat: no-repeat; /* Do not repeat the image */
background-size: cover; /* Resize the background image to cover the entire container */
background-image: url('systemimages/ReccomendedWP.jpg');
background-attachment: fixed;

}	

.Rec_Prod_GUI .card{
margin-right:20px;

}
@media only screen and (max-device-width: 480px) {
#container{
width:700px;

margin-left:80%;
}
.Rec_Prod_GUI{

display:none;
}

}
</style>
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>
<div id="container">
<div id="imagecontainer">
<img id="productimage" src="<?php echo $ProductObj->Image;?>" title="click to preview"  >
</div>
<div id="valuecontainer">
<center><h1 style="font-size:40px" ><?php echo  $ProductObj->ProductName; ?></h1></center>
<hr style="background-color:purple">
<h2 style="text-align:center;color:black"><?php echo $ProductObj->ProductID ?></h2>
<h2 style="text-align:center;margin-left:2%"><i><?php echo$ProductObj->ProductCategory ?></i></h2>
<p style="margin-left:2%;font-size:40px;color:grey"><?php echo$ProductObj->ProductCaption?></p>
<h2 style="margin-left:2%">Description</h2>
<p style="margin-left:2%;font-size:20px;color:grey"><?php echo$ProductObj->ProductDescription?></p>

<p style="margin-left:2%;font-size:25px;">Status: <?php echo$ProductObj->Status?></p>
<center><h2 style="text-align:center;margin-left:2%;color:green;font-size:40px"><b><?php echo$BaseUserOBJ->getCurrency().$ProductObj->ProductInitialPrice?></b></h2></center></br>
<i style="float:right;margin-right:2%;">Price is subjected to changes upon negotiating</i>
</br></br></br></br></br>
<p style="float:right;margin-right:2%;font-size:25px;">Product Expiry:<?php echo $ProductObj->DateOfExpiry?></p></br>

<script>

// Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById("productimage");
var modalImg = document.getElementById("img01");
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script>


<?php

if($ProductObj->ProductCategory == "Home and Lifestyle"){
		echo"<style> #container{background-image: url('systemimages/Home.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Sports"){
		echo"<style> #container{background-image: url('systemimages/Sports.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Music"){
		echo"<style> #container{background-image: url('systemimages/Music.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Clothing"){
		echo"<style> #container{background-image: url('systemimages/Clothes.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Computer and Accessories"){
		echo"<style> #container{background-image: url('systemimages/Electronics.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Jobs and Services"){
		echo"<style> #container{background-image: url('systemimages/Services.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Vehicles and Accessories"){
		echo"<style> #container{background-image: url('systemimages/Vehicle.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Mobile Phones and Gadgets"){
		echo"<style> #container{background-image: url('systemimages/Mobile.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "General Electronics"){
		echo"<style> #container{background-image: url('systemimages/gelectronics.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Beauty and Skincare"){
		echo"<style> #container{background-image: url('systemimages/Beauty.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Tickets and Vouchers"){
		echo"<style> #container{background-image: url('systemimages/Tickets.jpg');}</style>";
	
}
if($ProductObj->ProductCategory == "Pet Supplies and Accessories"){
		echo"<style> #container{background-image: url('systemimages/Pets.jpg');}</style>";
	
}
?>
</div>
<div id="buttonscontainer">

<?php
if(isset($_SESSION['ID'])){

$Owner = $_SESSION['Object']->getProductOwner($ProductID);
if($ProductObj->Reported>0 && $_SESSION['Object']->getAccountType()=="Administrator"&& $_SESSION['ID'] != $Owner ){
	echo'
<form method="post">
<input type="submit" name="Unreport" value="Unreport">
</form>';
}
if($ProductObj->Reported==0&& $_SESSION['Object']->getAccountType()!="Administrator" && $_SESSION['ID'] != $Owner ){
echo'
<form method="post">
<input type="submit" name="Report" value="Report">
</form>';	
}
if($_SESSION['ID'] == $Owner || $_SESSION['Object']->getAccountType()=="Administrator"){

echo'
<form method="post" action="EditProductPage.php?ID='.$ProductID.'">
<input type="submit" name="Edit" value="Edit">
</form>
<form method="post">
<input type="submit" name="Remove" value="Remove">
</form>';
if($ProductObj->Status=="Available"){
echo'
<form method="post">
<input type="submit" name="Unlist" value="Unlist">
</form>';
}
}
else{
if($ProductObj->Status=="Available"){
echo'
<form method="post" >
<input type="submit" name="SendOffer" value="Send Offer">
</form>';
}
}

}
if(isset($_POST['reviewtextsubmit'])){
	
	$_SESSION['Object']->addNewReview($_POST['reviewtext'],$ProductID);
	echo'<script>history.pushState({}, "", "")</script>';
	echo '<script> location.reload()</script> ';
	exit();	
}
if(isset($_POST['Remove'])){
	

	echo'
	<form method="post" >
	<div id="confirmation">
	<div id="confirmationtext">
	<b>Are you sure you want to remove this product?</b></br>
		<input type="submit" name="Confirmation" value="Yes">
		<input type="submit" name="Confirmation" value="No">
	</form>
	</div>
	</div>
	';
	
}
if(isset($_POST['Report'])){
	

	echo'
	<form method="post" >
	<div id="confirmation">
	<div id="confirmationtext">
	<b>Are you sure you want to report this product?</b></br>
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
	<b>Are you sure you want to unreport this product?</b></br>
		<input type="submit" name="Confirmationunreport" value="Yes">
		<input type="submit" name="Confirmationunreport" value="No">
	</form>
	</div>
	</div>
	';
	
}
if(isset($_POST['Unlist'])){


	echo'
	<form method="post" >
	<div id="confirmation">
	<div id="confirmationtext">
	<b>Are you sure you want to unlist this product?</b></br>
		<input type="submit" name="ConfirmationUnlist" value="Yes">
		<input type="submit" name="ConfirmationUnlist" value="No">
	</form>
	</div>
	</div>
	';
	
}
if(isset($_POST['Confirmation'])&& $_POST['Confirmation']=="No"){
echo '<script> location.replace("ProductPage.php?ID='.$ProductID.'")</script> ';
}
if(isset($_POST['Confirmation'])&&$_POST['Confirmation']=="Yes"){

$_SESSION['Object']->RemoveProduct($ProductID);
echo '<script> location.replace("index.php")</script> ';		
}
if(isset($_POST['Confirmationreport'])&& $_POST['Confirmationreport']=="No"){
echo '<script> location.replace("ProductPage.php?ID='.$ProductID.'")</script> ';	
}
if(isset($_POST['Confirmationreport'])&&$_POST['Confirmationreport']=="Yes"){

$_SESSION['Object']->ReportProduct($ProductID);
echo '<script> alert("Thank you for your vigilance,rest assured, the administrators will look into the matter");</script> ';		
echo '<script> location.replace("index.php")</script> ';	

}
if(isset($_POST['Confirmationunreport'])&& $_POST['Confirmationunreport']=="No"){
echo '<script> location.replace("ProductPage.php?ID='.$ProductID.'")</script> ';
}
if(isset($_POST['Confirmationunreport'])&&$_POST['Confirmationunreport']=="Yes"){
$_SESSION['Object']->UnreportProduct($ProductID);
echo '<script> alert("Product unreported");</script> ';
echo '<script> location.replace("index.php")</script> ';		
}
if(isset($_POST['ConfirmationUnlist'])&& $_POST['ConfirmationUnlist']=="No"){
echo '<script> location.replace("ProductPage.php?ID='.$ProductID.'")</script> ';
}
if(isset($_POST['ConfirmationUnlist'])&&$_POST['ConfirmationUnlist']=="Yes"){
$_SESSION['Object']->UnlistProduct($ProductID);
echo '<script> location.replace("ProductPage.php?ID='.$ProductID.'")</script> ';
}
if(isset($_POST['SendOffer'])){
$_SESSION['Temp_Product']=$_GET['ID'];
echo '<script> location.replace("OfferPage.php")</script> ';	

}
if(isset($_POST['deletecomment'])){
	$_SESSION['Object']->deleteComment($ProductID,$_POST['reviewid'],"Product");
}
if(isset($_SESSION['Object'])){
$Owner = $_SESSION['Object']->getProductOwner($ProductID);
if($_SESSION['ID']!=$Owner){
$TagArray = array();
$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $ProductObj->ProductName, -1, PREG_SPLIT_NO_EMPTY);
$TagArray = array_merge($split,$TagArray);
$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $ProductObj->ProductCategory, -1, PREG_SPLIT_NO_EMPTY);
$TagArray = array_merge($split,$TagArray);
$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $ProductObj->ProductCaption, -1, PREG_SPLIT_NO_EMPTY);
$TagArray = array_merge($split,$TagArray);
$TagArray = array_unique($TagArray);
foreach ($TagArray as $Val){
	
	$_SESSION['Object']->AddUserTags($Val);
}
}
}
?>
<center>
<h3 style="margin-top:500px" id="productby">Product By</h3>
<p style="margin-left:2%;font-size:25px;"><a href="ProfilePage.php?ID=<?php echo$ProductObj->SellerUserID?>"><?php echo$BaseUserOBJ->getUserDisplayName($ProductObj->SellerUserID)?></a></p>
</center>
</div>
</div>
<center>
<div id="commentcontainer">
<h1>Reviews</h1>
<div style="float:left;width:100%">
<hr>
<?php $BaseUserOBJ->viewReview($ProductID,"Product");
if(isset($_SESSION['ID'])){
if($_SESSION['ID'] != $Owner){
?>
<form method="post" style="text-align:center;">
<input type="text" name="reviewtext" style="width:1000px;height:100px;" placeholder="Review Product">
<input type="hidden" name="reviewtextsubmit">
</form>
<?php } }?>
</div>
</div>
</center>
<script>
					function clickproduct(ID){
						location.replace("ProductPage.php?ID="+ID);
					}
</script>

<?php 
if(isset($_SESSION['ID'])){

	echo"<div class='Rec_Prod_GUI'>
<center><h1 style='margin-top:30px'>You will like</h1></center>
	";	
	
	$ArrayOfRecProducts = $_SESSION['Object']->RecommendedProduct($ProductObj->ProductCategory,$ProductObj->ProductName,$ProductID);

	foreach($ArrayOfRecProducts as $val){
		$ProductObj = new Products();
		$ProductObj->InitialiseProduct($val);
		echo'

			<div class="card" onclick="clickproduct(this.id)" id = "'.$val.'">
			<img src="'.$ProductObj->Image.'" class="image" style="object-fit: cover;width:200px;height:200px;border-radius:20px;margin-top:20px">
			<div class="text" style="font-size:10px"><b>'.$ProductObj->ProductCategory.'</b></div>
			<div class="text" style="font-size:15px"><b>'.$ProductObj->ProductName.'</b></div>
			<div class="text">Date Listed:<i>'.$ProductObj->DateOfListing.'</i></div>
			<div class="text" style="font-size:20px;"><b>'.$_SESSION['Object']->getCurrency().number_format($ProductObj->ProductInitialPrice, 2, '.', '').'</b></div>
		
			</div></br>';
		
		
		
	}
	echo'</div>';
}

?>
<script>
document.getElementById("valuecontainer").scrollTop = document.getElementById("valuecontainer").scrollHeight;
</script>
<?php require_once("Footer.php");?> 