<?php
header('Access-Control-Allow-Origin: *');
require_once("Users.php");
session_start();


if($_SESSION['Object']->RedeemSTICoin()){
	echo'<script>alert("Payment Success!")</script>';
	echo '<script> location.replace("index.php")</script> ';
}
else{
	echo'<script>alert("Payment Failed,please try again")</script>';
	echo '<script> location.replace("ConvertPage.php")</script> ';
}

