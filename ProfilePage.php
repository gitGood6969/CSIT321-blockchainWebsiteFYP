<?php require_once("NavBar.php");
$ID = $_GET['ID'];
if(!isset($ID)){
echo '<script> location.replace("index.php")</script> ';	
}

$BaseUserObj = new BaseUser("Profile");
if(!$BaseUserObj->setUID($ID)){
echo '<script> location.replace("index.php")</script> ';	
}
?> 
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>



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
position:relative;
height:360px;
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
.sorter{
font-family: 'Roboto';font-size: 22px;

font-size:20px;
margin-top:2%;
display:block;
width:100%;
opacity:0.9;
color:white;
float:right;
}
.sorter input{
border: 2px solid white;
background-color:purple;
color:white;
border-radius:5px;
}
.sorter input:hover{

transform: scale(1.1); 
outline:60%;
filter: drop-shadow(0 0 6px white);
}
#container{
width:100%;
float:left;
margin:auto;
margin-top:2%;



}
#sortcontent{
opacity:1;
margin-left:80px;
width:400px;
height:180px;
color:purple;
text-align:left;
background-color:white;

}
#profile_gui{
background-image:black;
overflow:cover;
width:100%;
height:630px;
background-repeat: no-repeat; /* Do not repeat the image */
background-size: cover; /* Resize the background image to cover the entire container */
background-image: url('systemimages/profilewallpaper.jpg');
background-attachment: fixed;
margin:auto;
margin-top:5%;
}
#profilecontainer{
z-index:100;
box-shadow:
0 0 0 20px #fff,  /* inner white */
0 0 60px 20px #f0f, /* middle magenta */
0 0 0 20px #0ff; /* outer cyan */

background-color:white;
width:600px;
height:650px;
margin:auto;
top:50px;
margin-bottom:30px;
text-align:center;

overflow:hidden;


}
#profilecontainer input {
border:none;


background-color:purple;
color:white;
font-size:20px;
border-radius:10px;
margin-right:10px;
}
#profilecontainer input:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
#commentcontainer{
width:1400px;
height:750px;
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
</style>
<div id="profile_gui">
<?php 
echo'<div id="profilecontainer">';


if(isset($_POST['deletecomment'])){
	$_SESSION['Object']->deleteComment($ID,$_POST['reviewid'],"User");
}
if(isset($_POST['Report'])){
	

	echo'
	<form method="post" >
	<div id="confirmation">
	<div id="confirmationtext">
	<b>Are you sure you want to report this user?</b></br>
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
	<b>Are you sure you want to unreport this user?</b></br>
		<input type="submit" name="Confirmationunreport" value="Yes">
		<input type="submit" name="Confirmationunreport" value="No">
	</form>
	</div>
	</div>
	';
	
}
if(isset($_POST['Confirmationreport'])&& $_POST['Confirmationreport']=="No"){
exit();
header("Refresh:0");
}
if(isset($_POST['Confirmationreport'])&&$_POST['Confirmationreport']=="Yes"){

$_SESSION['Object']->ReportUser($ID);
echo '<script> alert("Thank you for your vigilance,rest assured, the administrators will look into the matter");</script> ';	
echo '<script> location.replace("index.php")</script> ';	
}
if(isset($_POST['Confirmationunreport'])&& $_POST['Confirmationunreport']=="No"){
exit();
header("Refresh:0");
}
if(isset($_POST['Confirmationunreport'])&&$_POST['Confirmationunreport']=="Yes"){

$_SESSION['Object']->UnreportUser($ID);
echo '<script> alert("User unreported");</script> ';
echo '<script> location.replace("index.php")</script> ';	
}

		echo'
		<h1>'.$BaseUserObj->getDisplayName().'</h1>
		<center><img src="'.$BaseUserObj->ProfilePic.'" height="300" width="300"></center>
		<div class="w3-panel w3-black">';
		for($i=0;$i<intval($BaseUserObj->Rating['Rating']);$i++){
			echo'<span class="fa fa-star checked"></span>';

		}
		echo'
		<h2 style="font-weight:bold">Rating:'.round(intval($BaseUserObj->Rating['Rating']),3).'('.$BaseUserObj->Rating['NumOfReviewers'].')</h2><br><br><br>
		</div> 
		<p style="font-weight:bold"> Name:'.$BaseUserObj->getFirstName().' '.$BaseUserObj->getLastName().'</p>
		
';
if(isset( $_SESSION['Object'])){
if($BaseUserObj->Reported==0&& $_SESSION['Object']->getAccountType()!="Administrator" && $_SESSION['ID'] != $ID ){
echo'
<form method="post">
<input type="submit" name="Report" value="Report">
</form>';	
}
if($BaseUserObj->Reported>0 && $_SESSION['Object']->getAccountType()=="Administrator"&& $_SESSION['ID'] != $ID ){
	echo'
<form method="post">
<input type="submit" name="Unreport" value="Unreport">
</form>';
}
}
		
echo'</div>
		</div>
<div class="sorter">
<div id="sortcontent">
<form method="post">
<Label style="margin-right:40px;font-size:30px;"><b>Sort</b></Label></br>
<input type="radio" id="ASC" name="Order" checked = "true" value="ASC">&nbsp;&nbsp;&nbsp;&nbsp;<label for="ASC">Ascending</label><br>
<input type="radio" id="DESC" name="Order" value="DESC">&nbsp;&nbsp;&nbsp;&nbsp;<label for="DESC">Descending</label><br>
<input type="submit" name="SortCat" value="Category">
<input type="submit" name="SortPrice" value="Price">
<input type="submit" name="SortDate" value="Date">
</form>
</div>
</div>';

if(isset($_SESSION['ID'])){
	$User =$_SESSION['ID'];
}
else{
	$User = "default";
}
if(isset($_POST['SortDate'])){
$BaseUserObj->ViewAllUserProduct("DateOfListing",$_POST['Order'],$ID,$User);
}
if(isset($_POST['SortPrice'])){
$BaseUserObj->ViewAllUserProduct("ProductInitialPrice",$_POST['Order'],$ID,$User);
}
if(isset($_POST['SortCat'])){	
$BaseUserObj->ViewAllUserProduct("ProductCategory",$_POST['Order'],$ID,$User);
}
if(!isset($_POST['SortCat'])&&!isset($_POST['SortPrice'])&&!isset($_POST['SortDate'])){
$BaseUserObj->ViewAllUserProduct("DateOfListing","ASC",$ID,$User);	
}

?>
<div id="commentcontainer" style="float:left;width:100%">
<hr>
<h1>Reviews</h1>
<?php $BaseUserObj->viewReview($ID,"User");
if(isset($_SESSION['ID'])){
if($BaseUserObj->getUID()!=$_SESSION['ID']){
?>


<form method="post" style="float:left;width:100%;text-align:center;">
<input type="text" name="reviewtext" style="width:1000px;height:100px;" placeholder="Review User">
<input type="hidden" name="reviewtextsubmit">
</form>
</div>
<?php
}
}
if(isset($_POST['reviewtextsubmit'])){
	
	$_SESSION['Object']->addNewUserReview($_POST['reviewtext'],$BaseUserObj->getUID());
	echo'<script>history.pushState({}, "", "")</script>';
	echo '<script> location.reload()</script> ';
	exit();	
}
if(isset($_SESSION['Object'])){
	if($ID!=$_SESSION['Object']->getUID()){
		$_SESSION['Object']->AddUserTags($ID);
	}
}
?>
</div>

<?php require_once("Footer.php");?>