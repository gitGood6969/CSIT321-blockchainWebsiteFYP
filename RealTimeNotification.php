<?php
require_once("Users.php");
require_once("Products.php");
session_start();
$ch = curl_init('http://localhost:3030');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");


//#############################################################
if(isset($_POST['Notification'])){
echo"hi";
$jsonData = json_encode(['NotificationUserID'=>$_POST['Notification'],'NotificationMessage'=>$_POST['NotificationMessage'],'NotificationHyperlink'=>$_POST['NotificationHyperlink'],'NotificationID'=>$_POST['NotificationID']]);

$query = http_build_query(['data' => $jsonData]);	
}

//#############################################################
if(isset($_POST['Delete'])){
$_SESSION['Object']->RemoveNotification($_POST['Delete']);
}

//#############################################################
if(isset($query)){
	
curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// execute
$response = curl_exec($ch);
 // close
 $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo $response;
curl_close($ch);
}
