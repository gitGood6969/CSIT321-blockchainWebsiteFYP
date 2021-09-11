<?php
require_once("Users.php");
require_once("Products.php");
session_start();
$ch = curl_init('http://localhost:3030');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

// get the input
$User = trim(htmlspecialchars($_POST['User'] ?? ''));
$message = trim(htmlspecialchars($_POST['message'] ?? ''));


//#############################################################
if(isset($_POST['offer'])){
	
$_SESSION['Object']->UpdateContract($_POST['offer'],$_POST['daterequired'],$_POST['paymentmode'],$_POST['contractid'],$_POST['usertype'],$_POST['delivery']);

$jsonData = json_encode([
	'User' => $User,
	'offer' => $_POST['offer'],
	'daterequired' => $_POST['daterequired'],
	'paymentmode' => $_POST['paymentmode'],
	'deliverymode' => $_POST['delivery'],
	'ContractID' => $_POST['contractid']
]);	
$query = http_build_query(['data' => $jsonData]);	

}


//#############################################################
if(isset($_POST['Accept'])){
$_SESSION['Object']->AcceptContract($_POST['contractid'],$_POST['usertype']);
$jsonData = json_encode([
	'REPLY'=>'AcceptOffer',
	'Type' => $_POST['usertype'],
	'ContractID' => $_POST['contractid']
]);
$query = http_build_query(['data' => $jsonData]);
}


//#############################################################
if(isset($_POST['AcceptService'])){

$_SESSION['Object']->AcceptService($_POST['contractid'],$_POST['usertype']);

$jsonData = json_encode([
	'REPLY'=>'AcceptService',
	'Type' => $_POST['usertype'],
	'ContractID' => $_POST['contractid']
]);
$query = http_build_query(['data' => $jsonData]);
}


//#############################################################
if(isset($_POST['Reject'])){
	
$_SESSION['Object']->RejectContract($_POST['contractid']);
$jsonData = json_encode([
	'REPLY'=>'Reject',
	'ContractID' => $_POST['contractid']
]);

$query = http_build_query(['data' => $jsonData]);
echo $query;
}


//#############################################################
if(isset($_POST['Refund'])){
$_SESSION['Object']->RequestRefund($_POST['contractid']);
$jsonData = json_encode([
	'REPLY'=>'Refund',
	'ContractID' => $_POST['contractid']
]);

$query = http_build_query(['data' => $jsonData]);
echo $query;
}


//#############################################################
if(isset($_POST['Cancel'])){
	
$_SESSION['Object']->CancelOrder($_POST['contractid']);
$jsonData = json_encode([
	'REPLY'=>'Cancel',
	'ContractID' => $_POST['contractid']
]);
$query = http_build_query(['data' => $jsonData]);
}


//#############################################################
if(isset($message) && strlen($message)!=0){

$_SESSION['Object']->InsertChat($_POST['contractid'],$User,$message,$_POST['usertype']);

	
$jsonData = json_encode([
	'REPLY'=>'ChatSystem',
	'User' => $User,
	'Type' => $_POST['usertype'],
	'message' => $message,
	'ContractID' => $_POST['contractid']
]);
$query = http_build_query(['data' => $jsonData]);

}


//#############################################################
if(isset($_POST['chatmessage'])){
$_SESSION['Object']->InsertOrdinaryChat($_POST['User1'],$_POST['User2'],$message);

$jsonData = json_encode([
	'User1' => $_POST['User1'],
	'User2' => $_POST['User2'],
	'message' => $message,
]);
$query = http_build_query(['data' => $jsonData]);	
	
}


//#############################################################
if(isset($_POST['sendnotification'])){
$ContractObj = new Contracts();
$ContractObj->initialiseContract($ContractID);

$_SESSION['Object']->addNotification($ContractObj->SellerUserID,"ContractID:".$_POST['contractid'].", Contract cannot proceed with the transaction until you top up,please top up your balance","ConvertPage.php");
	
}

//#############################################################

if(isset($_POST['ContractInformation'])){
$data = $_SESSION['Object']->getContractInfoFromSmartContract($_POST['contractid']);

$jsonData = json_encode([
	'REPLY'=>'ContractInformation',
	'User' => $User,
	'Type' => $_POST['usertype'],
	'contractdata' => $data,
	'ContractID' => $_POST['contractid']
]);
$query = http_build_query(['data' => $jsonData]);	
	
}

//#############################################################
if(isset($_POST['CheckAccepted'])){
if($_SESSION['Object']->CheckAccepted($_POST['CheckAccepted'])==2){
$_SESSION['Object']->InitContract($_POST['CheckAccepted']);	
if($_SESSION['Object']->ToTransfer($_POST['CheckAccepted'])){
		
		if($_SESSION['Object']->TransferAmountAccept($_POST['CheckAccepted'],$_SESSION['Object']->AmountToTransfer($_POST['CheckAccepted']))){
		}
		else{
			$jsonData = json_encode([
			'REPLY'=>'CheckAccepted',
			'Deal' => "error",
			'ContractID' => $_POST['CheckAccepted']
			]);	
		}
}
if($_POST['paymenttype']=="Full-STICoins_Later"){
	$_SESSION['Object']->UpdateStatusDeal($_POST['CheckAccepted']);
}
$jsonData = json_encode([
'REPLY'=>'CheckAccepted',
'Deal' => "set",
'ContractID' => $_POST['CheckAccepted']
]);	

}
else{
	$jsonData = json_encode([
	'REPLY'=>'CheckAccepted',
	'Deal' => "notset",
	'ContractID' => $_POST['CheckAccepted']
]);
	
}
$query = http_build_query(['data' => $jsonData]);	
}

//#############################################################
if(isset($_POST['CheckServiceAccepted'])){


if($_SESSION['Object']->CheckServiceAccepted($_POST['CheckServiceAccepted'])==2){

if($_SESSION['Object']->ToTransfer($_POST['CheckServiceAccepted'])){
		if($_SESSION['Object']->TransferAmountAcceptService($_POST['CheckServiceAccepted'],$_SESSION['Object']->AmountToTransfer($_POST['CheckServiceAccepted']))){
		
		}
		else{
			$jsonData = json_encode([
			'REPLY'=>'CheckServiceAccepted',
			'DealComplete' => "error",
			'Type' => $_POST['usertype'],
			'ContractID' => $_POST['CheckServiceAccepted']
		]);	
		}
}
if($_POST['paymenttype']=="Full-STICoins"){
	$_SESSION['Object']->UpdateStatusComplete($_POST['CheckServiceAccepted']);
}
$jsonData = json_encode([
'REPLY'=>'CheckServiceAccepted',
'DealComplete' => "set",
'Type' => $_POST['usertype'],
'ContractID' => $_POST['CheckServiceAccepted']
]);	

}
else{
	$jsonData = json_encode([
	'REPLY'=>'CheckServiceAccepted',
	'DealComplete' => "notset",
	'ContractID' => $_POST['CheckServiceAccepted']
]);
	
}
$query = http_build_query(['data' => $jsonData]);
	
}



//#############################################################
if(isset($_POST['RefundAdmin'])){
	$_SESSION['Object']->Refund_Admin($_POST['contractid'],$_SESSION['Object']->AmountToTransfer($_POST['contractid']));
	$jsonData = json_encode([
	'REPLY'=>'AdminRefund',
	'ContractID' =>$_POST['contractid']
]);
$query = http_build_query(['data' => $jsonData]);
}



//#############################################################
if(isset($_POST['OTP'])){
$msg = $_SESSION['Object']->VerifyOTP($_POST['OTP']);
if($msg=="Success"){
$jsonData = json_encode([
'REPLY'=>'OTPResult',
'Result' =>'Success',
'User' => $_SESSION['Object']->getUID(),
'ContractID' =>$_POST['contractid']
]);
}
if($msg=="Wrong OTP"){
$jsonData = json_encode([
'REPLY'=>'OTPResult',
'Result' =>'Failed',
'User' => $_SESSION['Object']->getUID(),
'ContractID' =>$_POST['contractid']
]);
}
if($msg=="Max Attempt"){

$jsonData = json_encode([
'REPLY'=>'OTPResult',
'Result' =>'LogOut',
'User' => $_SESSION['Object']->getUID(),
'ContractID' =>$_POST['contractid']
]);

}
$query = http_build_query(['data' => $jsonData]);
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

curl_close($ch);
}
if(isset($_POST['Email'])){
	$_SESSION['Object']->SendVerfication($_POST['Email']);
	
	
}
if(isset($_POST['Logout'])){
	$_SESSION['Object']->LogOut();
}