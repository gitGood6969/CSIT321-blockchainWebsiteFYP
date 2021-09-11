<?php
date_default_timezone_set("Singapore");
require_once("Users.php");
require_once("Products.php");
require_once("Contracts.php");
session_start();
?>
<html lang="en">
<head>
<link  rel="shortcut icon" href="systemimages/Logo.jpg">
<title>STIC</title>
<meta charset="utf-8">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet'>

<style>

#navbutton:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);
}
body{
font-family: 'Roboto';
color:purple;
}
.dropdown{
width:150px;
background-color:black;

}
#navbutton{
font-family: 'Helvetica';
border:none;
background-color:purple;
color:white;
font-size:20px;
border-radius:5px;
margin-right:10px;
padding:3px;
cursor: pointer;


}
.dropdown-content {
width:100%;
margin-top:90px;
display: none;
position: absolute;

z-index: 1;
width:200px;

}
.dropdown-content input not(#reload){
width:100%;
padding: 12px 16px;
text-decoration: none;
display: block;
background-color:white;
}
.dropdown-content a {

transition: all .5s ease-in-out;
width:100%
font-size:20px;
margin:auto;
color:purple;
padding: 12px 16px;
text-decoration: none;
display: block;
border: 1px solid purple;
background-color:white;
}

.dropdown-content:before {
content: '';
position: absolute;
width: 25px;
height: 15px;
top: -2.5px;
left: 90px;
z-index:-1;
background: indigo;
transform: rotate(135deg);
}
#dropbtn2{
float:left;
background-color:white;
margin-left:20%;
border:2px dashed purple;
width:70px;
height:70px;
border-radius: 50%;
cursor:pointer;
}


#dropbtn{
border:2px dashed purple;
float:left;
left:70px;
width:70px;
height:70px;
cursor:pointer;
border-radius: 50%;
position:absolute;
}

.dropdown-content a:hover{
opacity:1;	
transform: scale(1.1);
cursor:pointer;
color:white;
background-color:purple;
}
.SearchBar{
width: 50%;
margin: auto;
padding: 3px;
}
#SearchBar{
background-color:white;
color:purple;
border-radius:5px;
width:50%;
margin-left:30%;
height:40px;
}


input[type="submit"]:hover{
cursor:pointer;
}


#homebtn:hover{
outline:60%;
filter: drop-shadow(0 0 5px purple);
}
.input-group{
width:100%;
margin-top:20px;
}
.close {
cursor: pointer;
position: absolute;
color:white;
top: 25%;
right: 0%;
padding: 12px 16px;
transform: translate(0%, -10%);
}
#notifications{
margin:auto;

max-height:170px;
width:300px;
float:right;
display:none;
overflow:scroll;
position:absolute;
margin-top:80px;
}


#notificationcontainer{
margin:auto;
margin-bottom:1%;
z-index:100;
max-height:100px;
transition: height 2s;

}

#notifications::-webkit-scrollbar {
width:3px;
}

#notifications:-webkit-scrollbar-track {
width:1px;
}

#notifications::-webkit-scrollbar-thumb {
background-color:black;
outline: 1px solid white;
}
#notifications a{

color:white;
background-color:indigo;
height:55px;
font-size:15px;
text-decoration:none;
width:100%;
margin-left:5px;
margin-top:20px;
}
#notificationmsg{
margin-top:10px;
box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
transition: 0.3s;
word-wrap: break-word;
max-height:200px;
background-color:indigo;
border:1px solid black;
height:100px;
transition: width 0.5s;
margin-right:100px;
margin-bottom:10px;
}

#notificationmsg a:hover{
opacity:1;
webkit-transform: rotate(360deg);
-webkit-animation: neon3 1s ease-in-out infinite alternate;
-moz-animation: neon3 1s ease-in-out infinite alternate;
animation: neon3 1s ease-in-out infinite alternate;	
}
#notificationmsg:hover{
opacity:1;
box-shadow:
0 0 0 20px #fff,  /* inner white */
box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}
.badge {
font-family: 'Roboto';
width:20px;
height:20px;
font-size:10px;
text-align:center;
padding: 5px;
border-radius: 50%;
background-color: purple;
color: white;
position:absolute;
}
.pagination {
display: inline-block;
padding:5%;
padding-bottom:80px; 
margin-left:40%;
left:50%;  
bottom:0;
}

.pagination a {
color: purple;
float: left;
padding: 8px 16px;
text-decoration: none;
border: 1px solid purple;
}
.pagination a:hover {
color: white;
background-color:purple;
}
#caticons{
border-radius:50%;
}
#caticons:hover{
transform: scale(1.1); 
outline:60%;
filter: drop-shadow(0 0 8px white);
}

.categories{
height:80px;
width:100%;
margin-bottom:10px;
background-color:purple;
overflow:hidden;
color:white;

}
#navbar{
position:fixed;
display:inline;
width:100%;
z-index:1000;
background-color:white;
height:150px;
box-shadow:
0 0 0 20px #fff,  /* inner white */
0 0 0 20px #f0f, /* middle magenta */
0 0 70px 20px #0ff; /* outer cyan */
}
@media screen and (max-width: 320px) { 

position: absolute;

}

@media screen and (max-width: 800px) { 

}
h1,h2,h3,h4,h5{
font-family: 'Roboto';


}
@media only screen and (max-device-width: 480px) {
div#wrapper {
width: 400px;
}

div#header {
background-image: url(media-queries-phone.jpg);
height: 93px;
position: relative;
}

div#header h1 {
font-size: 140%;
}

#content {
float: none;
width: 100%;
}

#navigation {
float:none;
width: auto;
}
}
#dropbtn {

-webkit-transition: all 0.5s;
-moz-transition: all 0.5s;
transition: all 0.5s;
}

#dropbtn:hover {
-webkit-transform: rotate(360deg);
-webkit-animation: neon2 0.5s ease-in-out infinite alternate;
-moz-animation: neon2 0.5s ease-in-out infinite alternate;
animation: neon2 0.5s ease-in-out infinite alternate;
}
#dropbtn2{

-webkit-transition: all 0.5s;
-moz-transition: all 0.5s;
transition: all 0.5s;
}

#dropbtn2:hover {
-webkit-transform: rotate(360deg);
-webkit-animation: neon2 0.5s ease-in-out infinite alternate;
-moz-animation: neon2 0.5s ease-in-out infinite alternate;
animation: neon2 0.5s ease-in-out infinite alternate;
}
@keyframes neon2 {
from {
box-shadow: 0 0 10px violet, 0 0 10px violet, 0 0 5px violet, 0 0 10px violet,
0 0 70px violet, 0 0 10px violet, 0 0 10px violet, 0 0 10px violet;
}
to {
box-shadow: 0 0 5px #fff, 0 0 10px violet, 0 0 15px #fff, 0 0 20px #fff,
0 0 70px #fff, 0 0 10px #fff, 0 0 10px violet, 0 0 20px #fff;
}
}
@keyframes neon3 {
from {
text-shadow: 0 0 10px violet, 0 0 10px violet, 0 0 5px violet, 0 0 10px violet,
0 0 70px violet, 0 0 10px violet, 0 0 10px violet, 0 0 10px violet;
}
to {
text-shadow: 0 0 5px #fff, 0 0 10px violet, 0 0 15px #fff, 0 0 20px #fff,
0 0 70px #fff, 0 0 10px #fff, 0 0 10px violet, 0 0 20px #fff;
}
}


</style>


 <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests"> 

<script>

webportconfig = 'wss://fddaa3b4a543.ngrok.io';
//webportconfig = 'ws://localhost:3030';
window.WebSocket = window.WebSocket || window.MozWebSocket;
function deletenotification(ID){
	
var ajax = new XMLHttpRequest();
ajax.open("POST", "RealTimeNotification.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("Delete="+ID);
}


</script>
</head>
<div id="navbar" >
<div id="navbarinner" >
<div class="input-group" id="topnav">
<a href="index.php">
<img src="systemimages/CompanyLogo.jpg" id="homebtn" style="border-radius:20px;margin-left:10px;margin-bottom:5%;"  width="150" height="70">
</a>

<div class="SearchBar">
	<form  method="post">
		<input type="text"   aria-label="Search" id="SearchBar" name="SearchBar" placeholder="Search">
	<input type="hidden" name="searchfunction" value="">
	</form>
</div>
<?php
if(isset($_SESSION['ID'])){

	
echo'
<form method="post">
	<div class="dropdown">
	
	<a onclick="displayprofile()"><img id="dropbtn" src="'.$_SESSION['Object']->ProfilePic.'" height="80" width="80">
	</a>
	<div id="dropdown-content" class="dropdown-content">
	<a href="">Hello, '.$_SESSION['ID'].'</a>
	<a href="ListPage.php">List a product</a>
	<a href="ProfilePage.php?ID='.$_SESSION['ID'].'">My Profile</a>
	<a href="MyContractsPage.php">My Contracts</a>
	<a href="MyTransactionsPage.php">My Transactions</a>
	<a href="SettingsPage.php">Settings</a>
	<a href="ConvertPage.php">Top-Up</a>';
if($_SESSION['Object']->getAccountType()=="Administrator"){
	echo'<a href="ReportManagementPage.php">Manage reports</a>';
	echo'<a href="EscrowManagementPage.php">Manage escrows</a>';
	echo'<a href="UserManagementPage.php">Manage accounts</a>';
	echo'<a href="AdsManagementPage.php">Manage advertisments</a>';
	echo'<a href="ContractManagementPage.php">Manage contracts</a>';
}

echo '<a style="background-color:purple;color:white" id="Account_Balance">Please wait for balance</a>';
?>
<script>


Currency = "SGD$";
function displaynotification(){
	 var x = document.getElementById("notifications");
  if (x.style.display === "block") {
  x.style.display = "none";
  } else {
	    x.style.display = "block";
    
  }

}
function displayprofile(){
	 var x = document.getElementById("dropdown-content");
  if (x.style.display === "block") {
  x.style.display = "none";
  } else {
	    x.style.display = "block";
    
  }

}
var ajax = new XMLHttpRequest();
ajax.open("POST", "RealTimeBalance.php", true);
ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
ajax.send("UpdateBalance=1");

setInterval(function() {
var ajax = new XMLHttpRequest();
	ajax.open("POST", "RealTimeBalance.php", true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.send("UpdateBalance=1");
	}, 100000);

var connection =  new WebSocket(webportconfig);

connection.onmessage = function (message) {
	var data = message.data;
	data = JSON.parse(data);
	console.log(data);

	if(data.PubKey==document.getElementById("PubKey").value){
		if(data.Balance!=undefined){
			document.getElementById("Account_Balance").innerHTML = "Balance"+"</br>"+Currency+data.Balance.toFixed(2);
			ajax.open("POST", "RealTimeBalance.php", true);
			ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			ajax.send("ServerBalance=" + data.Balance);
		}
	
		
	}
	if(data.NotificationUserID!=undefined){
	  if(data.NotificationUserID==document.getElementById("User").value){
		  
			var newnotification = document.createElement("a");
			newnotification.innerHTML = data.NotificationMessage;  
			newnotification.href = data.NotificationHyperlink;  
			newnotification.id = data.NotificationID;  
			newnotification.onclick = deletenotification(newnotification.id);
			var newdiv = document.createElement("div");
			newdiv.id = "notificationmsg";  
			newnotification.style = "background-color:purple;color:white;width:300px";
			newdiv.appendChild(newnotification);
			document.getElementById("notifications").appendChild(newdiv);  
			var notificationcount = document.getElementById("notificationbadge").innerHTML;
			if(notificationcount==""){
				notificationcount=0;
			}
			notificationcount = parseInt(notificationcount)+1;
			document.getElementById("notificationbadge").innerHTML = notificationcount;

		}
		if(document.getElementById("nonotification")!=undefined){
			document.getElementById("nonotification").remove();
		}
	}
	
}

</script>
<?php

echo'
</div>
</div>
</form>

';
echo'
	<div id="notificationcontainer">
	<a onclick="displaynotification()">
	<img id="dropbtn2" src="systemimages/Notification.png" height="70" width="70">
	</a>
	<span id="notificationbadge" class="badge"></span><div id="notifications" >';
	$NotificationArr = $_SESSION['Object']->getNotification();
	sort($NotificationArr);
		echo'<div id="notificationmsg" style="height:30px">';
		echo '<center><a id="nonotification" >Please scroll down</a></center>';
		echo'</div>';
	foreach($NotificationArr as $val){
		
		echo'<div id="notificationmsg">';
		$NotificationMessageArr = $_SESSION['Object']->getNotificationMessage($val);
		 echo '<a  id="'.$val.'" onclick="deletenotification(this.id)" href="'.$NotificationMessageArr['hreflink'].'">'.$NotificationMessageArr['msg'].'</a>';
			 echo'</div>';
	}

	if(empty($NotificationArr)){
		echo'<div id="notificationmsg">';
		echo '<a id="nonotification" >You have no notification</a>';
		echo'</div>';
	}
	else{
		
		echo'<script>document.getElementById("notificationbadge").innerHTML='.sizeof($NotificationArr).';</script>';
	}
	
	echo'
	<form method="post">
	<input type="submit" style="background-color:white;color:purple;border:1px solid purple;width:100px;margin-left:50px;height:40px;border-radius:0px;font-size:15px" id="navbutton" name="clear" value="clear all">
		</form>	
	</div>	
	</div>';

$BaseUserOBJ = new BaseUser("check status");
$BaseUserOBJ->setUID_Admin($_SESSION["ID"]);
if(json_decode($BaseUserOBJ->Status)[0]=="Suspended"){
echo'<script>alert("You have been suspended till,'.json_decode($BaseUserOBJ->Status)[1].'")</script>';	
$_SESSION["Object"]->LogOut();
}
if(json_decode($BaseUserOBJ->Status)[0]=="Banned"){
echo'<script>alert("You have been banned")</script>';	
$_SESSION["Object"]->LogOut();
}
}
else{
	echo'<style> input[name="Nav_LogOut"]{display:none;}</style>';
}
?>

<?php 
if(isset($_POST['clear'])){
$_SESSION["Object"]->RemoveAllNotification();
echo '<script> location.replace(window.location.href)</script> ';

}
if(isset($_POST['Nav_Main'])){

echo '<script> location.replace("index.php")</script> ';
exit();
}
if(isset($_POST['Nav_Login'])){

echo '<script> location.replace("LoginPage.php")</script> ';
exit();
}
if(isset($_POST['Nav_SignUp'])){


echo '<script> location.replace("SignUpPage.php")</script> ';
exit();
}

if(isset($_SESSION['ID'])){

echo'<style> input[name="Nav_SignUp"]{display:none;}</style>';
echo'<style> input[name="Nav_Login"]{display:none;}</style>';
echo'<style> input[name="Nav_LogOut"]{display:visible;}</style>';
 echo '<input type="hidden" id="User" value="'.$_SESSION['Object']->getUID().'">'; 
 echo '<input type="hidden" id="PubKey" value="'.$_SESSION['Object']->getPubKey().'">'; 
 
 
 
}


if(isset($_POST['Nav_LogOut'])){
$_SESSION["Object"]->LogOut();
}
if(isset($_POST['Refresh'])){

$_SESSION["Object"]->UpdateBalance();
}

if(isset($_POST['searchfunction'])){
	echo '<script> location.replace("SearchPage.php?query='.$_POST['SearchBar'].'")</script> ';
}

?>	

<form method="post">
	<input type="submit" id="navbutton"  name="Nav_Login"  value="Login"/>
	<input type="submit"  id="navbutton"  name="Nav_SignUp"  value="Register"/>		
<input type="submit" id="navbutton" name="Nav_LogOut"  style="margin-top:20%" value="Log Out"/>
</form>



</div>


<div class="categories">
<center>
<?php


 if (!file_exists('Categories.txt')) 
{
	fopen("Categories.txt", "w");
}
$myfile = fopen("Categories.txt", "r") or die("Unable to open file!");
while(($line = fgets($myfile)) !== false) {
$arr = explode(":",$line);
echo'<a   href="CategoryPage.php?Category='.$arr[0].'">
<img id="caticons" style="margin-right:50px;margin-top:10px" src="'.$arr[1].'" title="'.$arr[0].'"; width="60" height="60">
</a>';
}
fclose($myfile);
?>
</center>
</div>
</div>
</div>
<div style="padding-top:220px"></div>
