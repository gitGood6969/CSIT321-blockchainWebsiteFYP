<?php
require_once("Users.php");
require_once("Products.php");
session_start();
$ch = curl_init('http://localhost:3030');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");


//#############################################################
if(isset($_POST['UpdateBalance'])){
$jsonData = json_encode(['Balance'=>$_SESSION['Object']->getAccountBalance(),'PubKey'=>$_SESSION['Object']->getPubKey()]);

$query = http_build_query(['data' => $jsonData]);	
}

//#############################################################
if(isset($_POST['ServerBalance'])){
	$_SESSION['Object']->updateBalance($_POST['ServerBalance']);
}
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
