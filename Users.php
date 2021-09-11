<?php
class BaseUser
{
	public $UID;
	public $DisplayName;
	public $PubKey;
	public $Email;
	public $FirstName;
	public $LastName;
	public $DOB;
	public $ContactNumber;
	public $Address;
	public $AccountType;
	public $AccountBalance;
	private $PrivateKey;
	public $Rating;
	public $Status;
	public $ProfilePic;
	public $results_per_page = 12;  
	public $Reported;  
	public $Currency; 
	public function __construct($Operation)
	{
		if($Operation == "SignUp"){
			$this->createEthereumAccount();
		}
	}
	public function LoginValidate($ID,$Pass)
	{	$ID = filter_var($ID, FILTER_SANITIZE_STRING);
		$ID = preg_replace('/(\'|&#0*39;)/', '', $ID);
		$sql = "SELECT * FROM users WHERE UserID='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		if ($result->num_rows == 0) 
		{
			return false;
		}
		else{
			$sql = "SELECT Password FROM users WHERE UserID='".$ID."'" ;
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			$validated = false;
			while($row = $result->fetch_assoc()){ 
				if(Password_verify($Pass,$row["Password"]))
				{	$validated = true;
				}
			}	
			if($validated)
			{	
				$this->setUID($ID);
				$sql="UPDATE `users` SET `LoginCount`= `LoginCount`+1 WHERE `UserID`='".$ID."'";
				$result = $this->connect()->query($sql) or die($this->connect()->error); 
				return true;
			}
			else{
				return false;
			}
		}
	}
	public function LogOut(){
		$sql="UPDATE `users` SET `LoginCount`= `LoginCount`-1 WHERE `UserID`='".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		echo'<style> input[name="Nav_Login"]{display:visible;}</style>';
		$_SESSION['ID']=NULL;
		session_destroy();
		echo '<script> location.replace("LoginPage.php")</script> ';
		exit();
	}
	public function CourierUpdate($TempID){
		$sql="DELETE FROM courier WHERE TempID='$TempID'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);  
	}
	public function CourierAcceptService($ContractID){
			$sql = " UPDATE `contracts` SET `Status`= 'Seller has accepted service', `TotalAccepted`= '1' WHERE `ContractID`= '".$ContractID."' ";
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function SendVerfication($email){
		$data            = "";
		$hashed_password = "";
		$header          = "";
		$msg             = "";
		$result          = "";
		$sql             = "";
		$temp_password   = "";
		$sql = "SELECT * FROM users WHERE UserID='".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 1)
		{
			$data = '1234567890';
			for ($i = 0; $i < 6; $i++) 
			{
				$temp_password .= substr(str_shuffle($data), 0, 1);
			}
			$hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
			$sql = "SELECT * FROM OTP WHERE UserID='".$this->getUID()."'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);
			if ($result->num_rows > 0)
			{
				$sql = "UPDATE `OTP` SET `Password`='".$hashed_password."',`Attempt`=`Attempt`+1 WHERE UserID='".$this->getUID()."'";
				$result = $this->connect()->query($sql) or die($this->connect()->error);
			}
			else{
				$sql = "INSERT INTO `OTP` (`UserID`, `Password`) VALUES ('".$this->getUID()."','".$hashed_password."')";
				$result = $this->connect()->query($sql) or die($this->connect()->error); 
			}
			$to_email = $email;
			$subject = "OTP password";
			$body = "Your OTP password is:".$temp_password;
			$headers = "From: S.T.I.C";
			mail($to_email, $subject, $body, $headers);
			return;
		}
	}
	public function VerifyOTP ($temporary_password)
	{
		// Initialize variables
		$new_hashed_password = "";
		$result = "";
		$row = "";
		$sql = "";
		$retrieved_hash_password = "";
		// Validation for UserID
		$sql = "SELECT * FROM OTP WHERE UserID='".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);  // Executing the query 
		while($row = $result->fetch_assoc())
		{ 
			$retrieved_hash_password = $row["Password"];
			$attempts = $row["Attempt"];
		}
		if($attempts>2){
			$sql = "DELETE FROM OTP WHERE UserID='".$this->getUID()."'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);
			return "Max Attempt";
		}
			// Compare hashed passwords
			if (password_verify($temporary_password, $retrieved_hash_password)) 
			{
				$sql = "DELETE FROM OTP WHERE UserID='".$this->getUID()."'";
				$result = $this->connect()->query($sql) or die($this->connect()->error);
				return "Success";
			} 
			else { return "Wrong OTP"; } // When Temporary password do not match		
	}
	public function ListOfAds(){
		$sql = "SELECT * FROM `advertisements` ";
		$adsarray = array();
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 	
			$temparray= array($row['AdsImage'],$row['UserID'],$row['Date']);
			array_push($adsarray,$temparray);
		}
		return $adsarray;
	}
	public function ForgetPassword ($userid, $email)
	{	
		$data            = "";
		$hashed_password = "";
		$header          = "";
		$msg             = "";
		$result          = "";
		$sql             = "";
		$temp_password   = "";
		$user_email      = "";
		$user_userid     = "";
		// Validation here AGAIN just in case this method is called elsewhere which might lack validation.
		$user_userid = filter_var($userid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  // Sanitize userid
		$user_email  = filter_var($email, FILTER_SANITIZE_EMAIL);                            // Sanitize email
		// Query to check user ID if it exists in Database (Users)
		$sql = "SELECT * FROM users WHERE UserID='".$user_userid."'";
		// Executing the query above
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		// By right since UserID is unique, should only have 1 row as result
		// Designed for Default-Deny structure
		if ($result->num_rows == 1)
		{
			// Generate temporary password 15 characters long
			// Don't use "rand" as its not secure
			$data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
			for ($i = 0; $i < 15; $i++) 
			{
				$temp_password .= substr(str_shuffle($data), 0, 1);
			}
			// Hash temporary password
			$hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);
			$sql = "SELECT * FROM temporarypassword WHERE UserID='".$user_userid."'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);
			if ($result->num_rows > 0)
			{
				$sql = "DELETE FROM temporarypassword WHERE UserID='".$user_userid."'";
				$result = $this->connect()->query($sql) or die($this->connect()->error);
			}
			$sql = "INSERT INTO `temporarypassword` (`UserID`, `Password`) VALUES ('".$user_userid."','".$hashed_password."')";
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			$header  = "From:fyp21s230@gmail.com \r\n";
			$header .= "MIME-Version: 1.0\r\n";
			$header .= "Content-type: text/html\r\n";
			// Crafting message
			$msg = "Your temporary password is: ".$temp_password."\nClick on the link below to reset your password:\n<a href ='https://01d3f4096b3e.ngrok.io/PasswordResetPage.php'>Reset Password</a>";
			// Tells the system to wrap words if longer than 70
			$msg = wordwrap($msg,70);
			// Send mail
			if(mail($user_email,"Reset Password",$msg,$header)){ return "SUCCESS"; }
			else { return "Error occured! Please retry!"; }
		}
		else
		{
			return "Error occured! Please retry!";
		}
	}
	public function ResetPassword ($userid, $temporary_password, $new_password)
	{
		// Initialize variables
		$new_hashed_password = "";
		$result = "";
		$row = "";
		$sql = "";
		$retrieved_hash_password = "";
		$user_userid = "";
		// Validation for UserID
		$user_userid = filter_var($userid, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);  // Sanitize userid
		$sql = "SELECT * FROM temporarypassword WHERE UserID='".$user_userid."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);  // Executing the query 
		// By right since UserID is unique, should only have 1 row as result
		if ($result->num_rows == 1)
		{
			$row = $result -> fetch_row();
			$retrieved_hash_password = $row[1];
			// Compare hashed passwords
			if (password_verify($temporary_password, $retrieved_hash_password)) 
			{
				// STOPPED HERE
				// Delete all temporary passwords associated with the username from database (temporaryPassword)
				$sql = "DELETE FROM temporarypassword WHERE UserID='".$user_userid."'";
				$result = $this->connect()->query($sql) or die($this->connect()->error);
				// Check if UserID exists in Database (Users)	
				$sql = "SELECT * FROM users WHERE UserID='".$user_userid."'"; 			
				$result = $this->connect()->query($sql) or die($this->connect()->error); 
				// By right since UserID is unique, should only have 1 row as result
				if ($result->num_rows == 1)
				{
					// Hash new password
					$new_hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
					// Update of new password in database (users)
					$sql = "UPDATE `users` SET `Password`='".$new_hashed_password."' WHERE `UserID`= '".$user_userid."'";					
					$result = $this->connect()->query($sql) or die($this->connect()->error); 
					// Once all functions successfully execute, then return "success"
					return "SUCCESS";
				}
				else { return "Error occured! Please retry!"; } // User would need to obtain another Temporary Password
			} 
			else { return "Error occured! Please retry!"; } // When Temporary password do not match			
		}
		else
		{
			return "Error occured! Please retry!";		
		}
	}
	public function getCurrency(){
		$this->Currency = "SGD$";
		return $this->Currency;
	}
	public function getCurrencyValue($Currency){
		$ch = curl_init('https://min-api.cryptocompare.com/data/price?fsym=ETH&tsyms=SGD,USD,EUR');
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($ch);
		$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$CurrencyVal = json_decode($response,true);
		curl_close($ch);
		return $CurrencyVal[$Currency];
	}
	public function getPubKeyFromUID($UID){
		$sql = "SELECT * FROM users WHERE UserID='".$UID."'" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{ 	
			$PubKey = $row['PublicKey'];
		}
		return $PubKey;
	}
	public function GetAccountBalanceFromServer($PubKey){
		$sql = "SELECT * FROM users WHERE PublicKey='".$PubKey."'" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{ 	
			$Bal = $row['AccountBalance'];
		}
		 $Bal = number_format($Bal, 2, '.', '');
		 $this->AccountBalance = $Bal;
		 return $Bal;
	}
	public function createEthereumAccount(){
		$host    = "localhost";
		$port    = 8080;
		$arr = array('REQUEST' => "GetNewAccount");
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$raw_data = file_get_contents('http://localhost:3004/GetNewAccount');
		$data = json_decode($raw_data, true);
		$this->PubKey =  $data['pubkey'];
		$this->PrivateKey = $data['privatekey'];
	}
	public function SignUpValidate($ID,$Email,$Pass,$FName,$LName,$ContactNumber,$DispName,$DOB,$Address,$ProfilePicCurrent,$ProfilePicDest){
		$ID = preg_replace('/(\'|&#0*39;)/', '', $ID);
		$Pass = preg_replace('/(\'|&#0*39;)/', '', $Pass);
		$Email = preg_replace('/(\'|&#0*39;)/', '', $Email);
		$FName = preg_replace('/(\'|&#0*39;)/', '', $FName);
		$LName = preg_replace('/(\'|&#0*39;)/', '', $LName);
		$DispName = preg_replace('/(\'|&#0*39;)/', '', $DispName);
		$Address = preg_replace('/(\'|&#0*39;)/', '', $Address);
		$sql = "SELECT * FROM users WHERE UserID='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		if ($result->num_rows != 0) 
		{
			return "UserID error";
		}
		$sql = "SELECT * FROM users WHERE Email='".$Email."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		if ($result->num_rows != 0) 
		{
			return "Email error";
		}
		$hashedpassword = password_hash($Pass, PASSWORD_DEFAULT);
		$Type = "Standard";
		if(isset($ProfilePicCurrent)){
		$sql = "INSERT INTO users (UserID,FirstName,LastName,Email,ContactNumber,Password,AccountType,DisplayName,Address,DateOfBirth,PublicKey,PrivateKey,ProfilePicture)VALUES('".$ID."','".$FName."','".$LName."','".$Email."' ,'".$ContactNumber."','".$hashedpassword."','".$Type."','".$DispName."','".$Address."','".date('d/m/Y', strtotime($DOB))."','".$this->PubKey."','".$this->PrivateKey."','".$ProfilePicDest."' )";
		}
		else{
		$sql = "INSERT INTO users (UserID,FirstName,LastName,Email,ContactNumber,Password,AccountType,DisplayName,Address,DateOfBirth,PublicKey,PrivateKey,ProfilePicture)VALUES('".$ID."','".$FName."','".$LName."','".$Email."' ,'".$ContactNumber."','".$hashedpassword."','".$Type."','".$DispName."','".$Address."','".date('d/m/Y', strtotime($DOB))."','".$this->PubKey."','".$this->PrivateKey."','profilepictures/default.jpg')";
		}
		$result = $this->connect()->query($sql) or die( $this->connect()->error);    	
		move_uploaded_file($ProfilePicCurrent, $ProfilePicDest);
		return "validated";
	}
	public function getUID(){
		return 	$this->UID;
	}
	public function setUID($UID){
		$sql = "SELECT * FROM users WHERE UserID='".$UID."'" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 0) 
			{
				return false;
			}	
		while($row = $result->fetch_assoc())
		{ 	
			$this->UID = $row["UserID"];
			$this->DisplayName = $row["DisplayName"];
			$this->PubKey = $row["PublicKey"];
			$this->Email = $row["Email"];
			$this->FirstName = $row["FirstName"];
			$this->LastName = $row["LastName"];
			$this->DOB = $row["DateOfBirth"];
			$this->ContactNumber = $row["ContactNumber"];
			$this->Address = $row["Address"];
			$this->AccountType = $row["AccountType"];
			$this->AccountBalance = $row["AccountBalance"];
			$this->Rating = json_decode($row["Rating"],true);
			$this->Status = $row["Status"];
			$this->ProfilePic = $row["ProfilePicture"];
			$this->Reported = $row["Reported"];
	}
	return true;
	}
	public function setUID_Admin($UID){
		$sql = "SELECT * FROM users WHERE UserID='".$UID."'" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 0) 
			{
				return false;
			}	
		while($row = $result->fetch_assoc())
		{ 	
			$this->UID = $row["UserID"];
			$this->DisplayName = $row["DisplayName"];
			$this->PubKey = $row["PublicKey"];
			$this->Email = $row["Email"];
			$this->FirstName = $row["FirstName"];
			$this->LastName = $row["LastName"];
			$this->DOB = $row["DateOfBirth"];
			$this->ContactNumber = $row["ContactNumber"];
			$this->Address = $row["Address"];
			$this->AccountType = $row["AccountType"];
			$this->Rating = json_decode($row["Rating"],true);
			$this->Status = $row["Status"];
			$this->ProfilePic = $row["ProfilePicture"];
			$this->Reported = $row["Reported"];
	}
	return true;
	}
	public function getPrivate(){
		return 	$this->PrivateKey;
	}
	public function getDisplayName(){
		return 	$this->DisplayName;
	}
	public function getPubKey(){
		return 	$this->PubKey;
	}
	public function getEmail(){
		return 	$this->Email;
	}
	public function getFirstName(){
		return $this->FirstName;
	}
	public function getLastName(){
		return $this->LastName;
	}
	public function getDOB(){
			return $this->DOB;
	}
	public function getContactNumber(){
		return $this->ContactNumber;
	}
	public function getAddress(){
		return $this->Address;
	}
	public function getAccountType(){
		return $this->AccountType;
	}
	public function getAccountBalance(){
		return $this->AccountBalance;
	}
	public function updateBalance($Bal){
	 $this->AccountBalance=$Bal;
	 $sql = "UPDATE `users` SET `AccountBalance` = '".$Bal."' Where `PublicKey` = '".$this->getPubKey()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function getStatus(){
		return $this->Status;
	}
	public function connect(){
		$servername= "localhost";
		$username = "root";
		$password = "";
		$dbname = "sticdb";
		$conn = new mysqli($servername, $username, $password, $dbname);
		return $conn;
	}
	public function getUserDisplayName($UID){
		$sql = "SELECT * FROM users WHERE UserID='".$UID."'" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{ 
			return $row["DisplayName"];
		}
	}
	public function getProductOwner($ProductID){
			$ID = trim($ProductID);
			$sql = "SELECT * FROM product WHERE ProductID='".$ProductID."'" ;
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			while($row = $result->fetch_assoc())
			{ 
				return $row["SellerUserID"];
			}
	}
	public function deleteComment($ID,$ReviewID,$Type){
		if($Type=="User"){
			$ID = trim($ID);
			$sql = "SELECT * FROM users WHERE UserID='".$ID."'" ;
		}
		if($Type=="Product"){
			$ID = trim($ID);
			$sql = "SELECT * FROM product WHERE ProductID='".$ID."'" ;
		}
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
			while($row = $result->fetch_assoc())
			{ 
				$Data = $row['Review'];
			}
			if($Data!=null&&sizeof(json_decode($Data))!=0){
				$Data = json_decode($Data, true);
				$Data[$ReviewID]=[''];
				$Data = json_encode($Data);
				if($Type=="Product"){
				$sql="UPDATE `product` SET `Review`='".$Data."' WHERE `ProductID`='".$ID."'";
				$result = $this->connect()->query($sql) or die($this->connect()->error);    
				}
				if($Type=="User"){
				$sql="UPDATE `users` SET `Review`='".$Data."' WHERE `UserID`='".$ID."'";
				$result = $this->connect()->query($sql) or die($this->connect()->error);    
				}
			}
	}
	public function viewReview($ID,$Type){
		if($Type=="User"){
			$ID = trim($ID);
			$sql = "SELECT * FROM users WHERE UserID='".$ID."'" ;
		}
		if($Type=="Product"){
			$ID = trim($ID);
			$sql = "SELECT * FROM product WHERE ProductID='".$ID."'" ;
		}
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
			while($row = $result->fetch_assoc())
			{ 
				$Data = $row['Review'];
			}
			if($Data!=null&&sizeof(json_decode($Data))!=0){
				$Data = json_decode($Data, true);
				$DuplicateData = $Data;
				if (in_array([''], $DuplicateData)) 
				{
					unset($DuplicateData[array_search([''],$DuplicateData)]);
				}
				for($i =0;$i<sizeof($Data);$i++){
				if($Data[$i] != ['']){
				echo'
				<div class="media border p-3" style="width:40%;margin:auto;margin-top:5px;">
				<div class="media-body">
				<h4>'.$Data[$i]["User"].'<small>   <i>Posted on '.$Data[$i]["Date"].'</i></small></h4>
				<p>'.$Data[$i]["Review"].'</p>';
				echo $this->UID;
				if(isset($_SESSION['ID'])){
				if(trim($Data[$i]["User"])== $_SESSION['ID']){
					echo'
					<form method="post">
					<input type="submit" name="deletecomment" id="deletebtn" style="float:right;" value="delete">
					<input type="hidden" name="reviewid" value="'.$i.'">
					</form>';
						}
				}
				echo'
				</div>
				</div></br>';
				}
			}
			if(sizeof($DuplicateData)==0){
				echo '
				<div class="media border p-3" style="margin-top:5px;width:40%;margin:auto;">
				<div class="media-body">
				<b style="margin:auto;"> No Reviews Yet</b></div>
				</div></br>';	
			}
			}
			else{
				echo '
				<div class="media border p-3" style="margin-top:5px;width:40%;margin:auto;">
				<div class="media-body">
				<b style="margin:auto;"> No Reviews Yet</b></div>
				</div></br>';	
			}
	}
	public function ViewAllProduct($sortby,$Order,$Category,$page,$pagename){
			if($Category=="All"){
					$sql = "SELECT * FROM product WHERE Status = 'Available' ORDER BY $sortby $Order" ;
			}
			else{
					$sql = "SELECT * FROM product WHERE Status = 'Available' AND ProductCategory = '".$Category."' ORDER BY $sortby $Order" ;
			}
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			$number_of_result = mysqli_num_rows($result);  
			$number_of_page = ceil ($number_of_result / $this->results_per_page);  
			if($page>$number_of_page){
				$page = 1;
			}
			$page_first_result = ($page-1) * $this->results_per_page;  
			if ($number_of_result == 0) 
			{
				echo'
					<center><b style="margin-top:5%;font-size:50px">No products in this category</b></center>';
				return;
			}
			if($Category=="All"){
					$sql = "SELECT * FROM product WHERE Status = 'Available' ORDER BY $sortby $Order LIMIT " . $page_first_result . ',' . $this->results_per_page; 
			}
			else{
					$sql = "SELECT * FROM product WHERE Status = 'Available' AND ProductCategory = '".$Category."' ORDER BY $sortby $Order LIMIT " . $page_first_result . ',' . $this->results_per_page; 
			}
			$result = mysqli_query($this->connect(), $sql);  
			echo'<div id="container">';
			while($row = $result->fetch_assoc())
			{ 
			?>
			<script>
					function clickproduct(ID){
						location.replace("ProductPage.php?ID="+ID);
					}
			</script>
			<?php
			echo'
			<div class="card" onclick="clickproduct(this.id)" id = "'.$row["ProductID"].'">
			<a href="#" class="fill-div"></a>
			<img src="'.$row["Image"].'" class="image" style="object-fit: cover;width:200px;height:200px;border-radius:20px;margin-top:20px">
			<div class="text" style="font-size:10px"><b>'.$row["ProductCategory"].'</b></div>
			<div class="text" style="font-size:15px; white-space: nowrap;text-overflow:ellipsis;overflow:hidden;"><b>'.$row["ProductName"].'</b></div>
			<div class="text">Date Listed:<i>'.date('d-m-Y',$row["DateOfListing"]).'</i></div>
			<div class="text" style="font-size:16px;"><b>'.$this->getCurrency().number_format($row["ProductInitialPrice"], 2, '.', '').'</b></div>
			</div>';
			}
			
			if($number_of_page>1){
			echo'<div class = "pagination" style="width:50%;margin-left:450px;">';	
			echo'<b style="width:50%;margin-left:200px;">Page</b></BR></BR>';
			echo '<a href = "'.$pagename.'?Ord='.$Order.'&Sb='.$sortby.'&page=1">First </a>'; 
			for($page = 1; $page<= $number_of_page; $page++) { 
				if($page==1){
				echo '<a href = "'.$pagename.'?Ord='.$Order.'&Sb='.$sortby.'&page=' . $page . '">' . $page . ' </a>';  
				}
				else{
				echo '<a href = "'.$pagename.'?Ord='.$Order.'&Sb='.$sortby.'&page=' . $page . '">' . $page . ' </a>';  
				}
			} 
			echo '<a href = "'.$pagename.'?Ord='.$Order.'&Sb='.$sortby.'&page=' . $number_of_page . '">Last </a>';  
			echo'</div>';
			}
			echo'</div>';
			}
	public function ViewSearchProduct($sortby,$Order,$Query,$page){
			$sql = "SELECT * FROM product WHERE ProductName LIKE '%$Query%' or ProductCategory LIKE '%$Query' or SellerUserID LIKE '%$Query%' or ProductID LIKE '%$Query%'  ORDER BY $sortby $Order" ;
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			$number_of_result = mysqli_num_rows($result);  
			$number_of_page = ceil ($number_of_result / $this->results_per_page); 
			if($page>$number_of_page){
				$page = 1;
			}			
	
			$page_first_result = ($page-1) * $this->results_per_page;  
			if ($result->num_rows == 0) 
			{
				echo'
					<b>No search result found</b>';
			}
			$sql = "SELECT * FROM product WHERE ProductName LIKE '%$Query%' or ProductCategory LIKE '%$Query' or SellerUserID LIKE '%$Query%' or ProductID LIKE '%$Query%' ORDER BY $sortby $Order LIMIT " . $page_first_result . ',' . $this->results_per_page; 
			$result = mysqli_query($this->connect(), $sql);  
			echo'<div id="container">';
			while($row = $result->fetch_assoc())
			{ 
			echo'
			<div class="card" onclick="clickproduct(this.id)" id = "'.$row["ProductID"].'">
			<a href="#" class="fill-div"></a>
			<img src="'.$row["Image"].'" class="image" style="object-fit: cover;width:200px;height:200px;border-radius:20px;margin-top:20px">
			<div class="text" style="font-size:10px"><b>'.$row["ProductCategory"].'</b></div>
			<div class="text" style="font-size:15px; white-space: nowrap;text-overflow:ellipsis;overflow:hidden;"><b>'.$row["ProductName"].'</b></div>
			<div class="text">Date Listed:<i>'.date('d-m-Y',$row["DateOfListing"]).'</i></div>
			<div class="text" style="font-size:16px"><b>'.$this->getCurrency().number_format($row["ProductInitialPrice"], 2, '.', '').'</b></div>
			</div>';
			?>
			<script>
					function clickproduct(ID){
						location.replace("ProductPage.php?ID="+ID);
					}
			</script>
			<?php
			}
			if($number_of_page>1){
			echo'<div class = "pagination" >';			
			echo'<b style="bottom: 20;">Page</b></BR></BR>';
			echo '<a href = "SearchPage.php?query='.$Query.'&Ord='.$Order.'&Sb='.$sortby.'&page=1">First </a>'; 
			for($page = 1; $page<= $number_of_page; $page++) { 
				if($page==1){
				echo '<a href = "SearchPage.php?query='.$Query.'&Ord='.$Order.'&Sb='.$sortby.'&page=' . $page . '">' . $page . ' </a></br>';  
				}
				else{
				echo '<a href = "SearchPage.php?query='.$Query.'&Ord='.$Order.'&Sb='.$sortby.'&page=' . $page . '">' . $page . ' </a>';  
				}
			} 
			echo '<a href = "SearchPage.php?query='.$Query.'&Ord='.$Order.'&Sb='.$sortby.'&page=' . $number_of_page . '">Last </a>';  
			echo'</div>';
			}			
	}
	public function ViewAllUserProduct($sortby,$Order,$UID,$User){
			if($User == $this->getUID()){
				$sql = "SELECT * FROM product WHERE SellerUserID = '$UID' ORDER BY Status , $sortby  $Order" ;
			}
			else{
				$sql = "SELECT * FROM product WHERE SellerUserID = '$UID' AND Status = 'Available' ORDER BY Status , $sortby   $Order" ;	
			}
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			if ($result->num_rows == 0) 
			{
				echo'
					<b>This user has not listed any products</b>';
			}	
				echo'
			<div id="container">
			';
			echo"<h1>User's Products</h1>";
			while($row = $result->fetch_assoc())
			{ 
			?>
			<script>
					function clickproduct(ID){
						location.replace("ProductPage.php?ID="+ID);
					}
			</script>
			<?php
			echo'
			<div class="card" onclick="clickproduct(this.id)" id = "'.$row["ProductID"].'">';
			if($row["Status"]=='Unlisted'){
				echo'<center><h5 style="color:red">Unlisted</h5></center>	';
			}
			if($row["Status"]=='Available'){
				echo'<center><h5 style="color:green">Available</h5></center>	';
			}
				echo'
			<a href="#" class="fill-div"></a>
			<img src="'.$row["Image"].'" class="image" style="object-fit: cover;width:200px;height:200px;border-radius:20px;margin-top:20px">
			<div class="text" style="font-size:10px"><b>'.$row["ProductCategory"].'</b></div>
			<div class="text" style="font-size:15px; white-space: nowrap;text-overflow:ellipsis;overflow:hidden;"><b>'.$row["ProductName"].'</b></div>
			<div class="text">Date Listed:<i>'.date('d-m-Y',$row["DateOfListing"]).'</i></div>
			<div class="text" style="font-size:16px"><b>'.$this->getCurrency().number_format($row["ProductInitialPrice"], 2, '.', '').'</b></div>
			</div>';
			}	
	}
	}
class StandardUser extends BaseUser 
{
	private $EscrowPrivate;
	public $notficationsize;
	private $TempCardAccount;
	private $TempAmount;
	public function __construct($Object){
		$this->UID = $Object->getUID();
		$this->DisplayName =  $Object->getDisplayName();
		$this->PubKey =  $Object->getPubKey();
		$this->Email = $Object->getEmail();
		$this->FirstName =  $Object->getFirstName();
		$this->LastName =  $Object->getLastName();
		$this->DOB =  $Object->getDOB();
		$this->ContactNumber =  $Object->getContactNumber();
		$this->Address =  $Object->getAddress();
		$this->AccountType =  $Object->getAccountType();
		$this->AccountBalance = $Object->getAccountBalance();
		$this->ProfilePic = $Object->ProfilePic;
		$this->Reported = $Object->Reported;
		}
	public function CommissionRate(){
		return 0.03;
	}
		public function addNotification($UID,$Message,$Link){
	$NotificationID = 0;
	while(true){					
		$NotificationID = rand(0,100000000);
		$result = $this->connect()->query("SELECT count(*) as 'c' FROM notification WHERE NotificationID='".$NotificationID."'");
		$count = $result->fetch_object()->c;
		if ($count==0)
		  {
			break;
		  }
	}
		$sql = "INSERT INTO `notification`(`UserID`, `Message`, `Hyperlink`,`NotificationID`) VALUES ('".$UID."','".$Message."','".$Link."','".$NotificationID."')";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		?>
		<script>
		console.log('<?php echo $NotificationID?>');
		ajax.open("POST", "RealTimeNotification.php", true);
		ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		ajax.send("Notification="+'<?php echo $UID ?>'+"&NotificationMessage="+'<?php echo $Message?>'+"&NotificationID="+"<?php echo $Message?>"+"&NotificationHyperlink="+'<?php echo $Link?>');
		console.log(ajax);	
		</script>
		<?php
	}
	public function getNotification(){
		$returnarr = array();
		$sql = "SELECT * FROM `notification`  WHERE  UserID ='".$this->getUID()."' Order BY `NotificationID` DESC ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			array_push($returnarr,$row['NotificationID']);
		}	
		return $returnarr;
	}
	public function getNotificationMessage($ID){
		$returnarr = array();
		$sql = "SELECT * FROM `notification`  WHERE  NotificationID ='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$returnarr['msg']=$row['Message'];
			$returnarr['hreflink']= $row['Hyperlink'];
		}	
		return $returnarr;
	}
	public function RemoveNotification($ID){
		$sql = "DELETE FROM notification WHERE NotificationID='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function RemoveNotificationInPage($Link){
		$sql = "DELETE FROM notification WHERE Hyperlink='".$Link."' AND UserID='".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function RemoveAllNotification(){
	
		$sql = "DELETE FROM notification WHERE UserID='".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
			
	}
	public function RecommendedProduct($Category,$Name,$ProductID){
	
		$sql = "SELECT * FROM product WHERE SellerUserID != '".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows<3)
		{
			$recommendedarray = array();
			return $recommendedarray;
			
		}
		
		$sql = "SELECT * FROM product WHERE SellerUserID != '".$this->getUID()."' AND ProductID != '".$ProductID."' AND(ProductCategory = '".$Category."' OR ProductName LIKE '%".$Name."%') ORDER BY RAND()";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$recommendedarray = array();
		$count = 0;
		while(sizeof($recommendedarray)!=3){
			$count++;
		while($row = $result->fetch_assoc())
		{
			array_push($recommendedarray,$row['ProductID']);
		}	
	
		if(sizeof($recommendedarray)==0){		
			$Name = str_split($Name, 1);
			foreach ($Name as $Val){
			$sql = "SELECT * FROM product WHERE SellerUserID != '".$this->getUID()."' AND ProductID != '".$ProductID."' AND ProductName LIKE '%".$Val."' ORDER BY RAND()";
				$result = $this->connect()->query($sql) or die($this->connect()->error); 
					while($row = $result->fetch_assoc())
			{
			array_push($recommendedarray,$row['ProductID']);
			}	
			}
		}
		if(sizeof($recommendedarray)!=3){
			$sql = "SELECT * FROM product WHERE SellerUserID != '".$this->getUID()."' AND ProductID != '".$ProductID."' ORDER BY RAND() LIMIT 3";
				$result = $this->connect()->query($sql) or die($this->connect()->error); 
					while($row = $result->fetch_assoc())
			{
				array_push($recommendedarray,$row['ProductID']);
			}
		}
		arsort($recommendedarray);
		
	    $recommendedarray= 	array_unique($recommendedarray);
		$recommendedarray = array_slice($recommendedarray, 0, 3, true);
		if($count==1000){
			break;
		}
		}
		
		return $recommendedarray;
	}
	public function UserProductBehaviourAnalysis(){
		$sql = "SELECT * FROM users  WHERE  UserID ='".$this->getUID()."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$TagsArray = json_decode($row['Tags'],true);
		}	
		$counts = array_count_values($TagsArray);
	asort($counts);

	$recommendedarray = array();
	$size = sizeof($TagsArray);
	$newones = array_slice($TagsArray, $size-2, $size, true);
	$counts = array_merge($counts,$newones);
	$productsarr = array();
	
	foreach($counts as $key=>$val){
			array_push($productsarr ,$key);
	}


		$sql = "SELECT * FROM product WHERE  SellerUserID != '".$this->getUID()."' ORDER BY RAND()";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 

		while($row = $result->fetch_assoc())
		{
			
			$counter = 0;
			$TagsArray = json_decode($row['Tags'],true);
					
			if(!empty($TagsArray)){
		
			foreach($TagsArray as $val){
				if(array_search(strtoupper($val),$productsarr)){
					$counter++;
				}
				
			}
			}
			$recommendedarray[$row['ProductID']]=$counter;
			
		}	
			krsort($counts);
			arsort($recommendedarray);
			$recommendedarray = array_slice($recommendedarray, 0,5, true);
			$counts = array_slice($counts, 0,4, true);
			arsort($recommendedarray);
			$recommendedarray = array_keys($recommendedarray);
			shuffle($recommendedarray);
			$recommendedarray = array_slice($recommendedarray, 0,4, true);			
			return $recommendedarray;
	}
	public function AddUserTags($Tag){
		$Tag = strtoupper($Tag);
		$sql = "SELECT * FROM users  WHERE  UserID ='".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$TagsArray = json_decode($row['Tags'],true);
		}
		if(empty($TagsArray )){
			$TagsArray = array();
		}
		array_push($TagsArray,$Tag);
		if(sizeof($TagsArray)>10){
			$totalremove = sizeof($TagsArray) - 10;
			$TagsArray = array_slice($TagsArray,0, $totalremove); 
		}
		$to_remove = array();
		$myfile = fopen("Wordstoremove.txt", "r") or die("Unable to open file!");
		while(($line = fgets($myfile)) !== false) {
			array_push($to_remove,preg_replace("/\s+/", "", strtoupper($line)));
		}
		fclose($myfile);
		$TagsArray = array_diff($TagsArray, $to_remove);
		$TagsArray = array_unique($TagsArray);
		$JsonData = json_encode($TagsArray);
		$sql = "UPDATE `users` SET `Tags`='".$JsonData."' WHERE  UserID='".$this->getUID()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);  
	}
	public function NewOffer($Offer,$DateRequired,$SellerID,$ProductID,$InitialOffer){
		
		while(true){					
					$ContractID = str_pad(rand(0000,9999),4,0,STR_PAD_LEFT).str_pad(rand(0000,9999),4,0,STR_PAD_LEFT).str_pad(rand(0000,9999),4,0,STR_PAD_LEFT).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).str_pad(rand(0000,9999),4,0,STR_PAD_LEFT). substr(rand(0000,9999), 2, 4);
					$result = $this->connect()->query("SELECT count(*) as 'c' FROM contracts WHERE ContractID='".$ContractID."'");
					$count = $result->fetch_object()->c;
					if ($count==0)
					  {
						break;
					  }
				}
		$sql = "INSERT INTO `contracts`(`ContractID`,`InitialOffer`,`NewOffer`,`DateRequired`, `BuyerUserID`, `SellerUserID`, `ProductID`) VALUES ('".$ContractID."','".$InitialOffer."','".$Offer."','".$DateRequired."','".$this->getUID()."','".$SellerID."','".$ProductID."')";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$this->addNotification($SellerID,"ContractID:".$ContractID.", new offer has been made","MyContractsPage.php");
		return $ContractID;
	}
	public function ListOfRecentTransactions(){
		 $sql = "SELECT * FROM transactions WHERE Receiver ='".$this->getPubKey()."' OR Sender ='".$this->getPubKey()."' ORDER BY Date DESC LIMIT 10";
		 $result = $this->connect()->query($sql) or die($this->connect()->error); 
		$ArrayOfTransactions = array();
		 	while($row = $result->fetch_assoc())
		{
			array_push($ArrayOfTransactions,$row['TransactionID']);
		}
		return $ArrayOfTransactions;
	}
	public function ListOfTransactions($Type){
		if($Type=="Receiver"){
		 $sql = "SELECT * FROM transactions WHERE Receiver ='".$this->getPubKey()."' ORDER BY Date DESC";
		}
		if($Type=="Sender"){
		$sql = "SELECT * FROM transactions WHERE Sender ='".$this->getPubKey()."' ORDER BY Date DESC";
		}
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$ArrayOfTransactions = array();
		while($row = $result->fetch_assoc())
		{
			array_push($ArrayOfTransactions,$row['TransactionID']);
		}
		return $ArrayOfTransactions;
	}
		public function ListOfTransactionsAll(){
		$sql = "SELECT * FROM transactions WHERE Receiver ='".$this->getPubKey()."' OR Sender ='".$this->getPubKey()."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$ArrayOfTransactions = array();
		while($row = $result->fetch_assoc())
		{
			array_push($ArrayOfTransactions,$row['TransactionID']);
		}
		return $ArrayOfTransactions;
	}
	public function getTransactionTitle($ID){
		$sql = "SELECT * FROM transactions WHERE TransactionID ='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			return $row['Title'];
		}
	}
	public function getTransactionSender($ID){
		$sql = "SELECT * FROM transactions WHERE TransactionID ='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$PubKey =  $row['Sender'];
		}
		$sql = "SELECT * FROM users WHERE PublicKey ='".$PubKey."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 0) 
		{
			return $ID;
		}
		while($row = $result->fetch_assoc())
		{
			 return $row['UserID'];
		}
	}
	public function getTransactionReceiver($ID){
		$sql = "SELECT * FROM transactions WHERE TransactionID ='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$PubKey = $row['Receiver'];
		}
		$sql = "SELECT * FROM users WHERE PublicKey ='".$PubKey."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 0) 
		{
			return $ID;
		}
		while($row = $result->fetch_assoc())
		{
			 return $row['UserID'];
		}
	}
	public function getTransactionDate($ID){
		$sql = "SELECT * FROM transactions WHERE TransactionID ='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			 return $row['Date'];
		}
	}
		public function getTransactionAmount($ID){
		$sql = "SELECT * FROM transactions WHERE TransactionID ='".$ID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			 return $row['Amount'];
		}
	}
	public function ListOfContracts($ContractType){
		if($ContractType=="All"){
		$sql = "SELECT * FROM contracts WHERE SellerUserID ='".$this->getUID()."' OR BuyerUserID ='".$this->getUID()."' ORDER BY TransactionOpenDate DESC" ;
		}
		if($ContractType=="Seller"){
		$sql = "SELECT * FROM contracts WHERE SellerUserID ='".$this->getUID()."' ORDER BY TransactionOpenDate DESC" ;
		}
		if($ContractType=="Buyer"){
		$sql = "SELECT * FROM contracts WHERE BuyerUserID ='".$this->getUID()."' ORDER BY TransactionOpenDate DESC" ;
		}
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 0) 
			{
				return false;
			}
		$ArrayOfContracts = array();
		while($row = $result->fetch_assoc())
		{
			array_push($ArrayOfContracts,$row['ContractID']);
		}
		return $ArrayOfContracts;
	}

	public function InsertChat($ContractID,$User,$Message,$Type){
		$Message = filter_var($Message,FILTER_SANITIZE_SPECIAL_CHARS);
	    $Message = preg_replace('/(\'|&#0*39;)/', '', $Message);
		$Message = array("Message"=>$Message , "User"=>$User , "Time"=>Time() ,"Type"=>$Type);
		$sql = "SELECT * FROM contracts WHERE ContractID='".$ContractID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		if ($result->num_rows == 0) 
		{
			$FullMessageArray = array($Message);
			$JSONdata = Json_encode($FullMessageArray);
			$sql = "INSERT INTO `contracts`(`Message`) VALUES ('".$JSONdata."')";
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
		}
		else{
			while($row = $result->fetch_assoc())
			{ 
				$FullMessageArray = Json_decode( $row["Message"],true);
			}
			array_push($FullMessageArray,$Message);
			$JSONdata = Json_encode($FullMessageArray);
			$sql = "UPDATE `contracts` SET `Message`='".$JSONdata."' WHERE ContractID='".$ContractID."'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		$sql = "SELECT * FROM contracts WHERE ContractID='".$ContractID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 
			$seller = $row["SellerUserID"];
			$buyer = $row["BuyerUserID"];
		}
		if($User!=$buyer){
			$this->addNotification($buyer,"ContractID:".$ContractID.", new message","MyContractsPage.php");
		}
		if($User!=$seller){
			$this->addNotification($seller,"ContractID:".$ContractID.", new message","MyContractsPage.php");
		}
	}
	public function RetrieveChat($ContractID){
		$sql = "SELECT * FROM contracts WHERE ContractID = '".$ContractID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$Msg = array();
		while($row = $result->fetch_assoc())
		{
			$Msg = json_decode($row['Message'],true);
		}
		if(sizeof($Msg)>0){
			for($x = 0; $x<sizeof($Msg);$x++){
				if($Msg [$x]['User']==$this->getUID()){
					echo'<div id="User1">';
				}
				else{
					echo'<div id="User2">';
				}
				if($Msg [$x]['Type']=="Admin"){
					echo'<span class="author">'.$Msg [$x]['User'].'(Administrator)</br></span>
						 <span class="messsage-text">'.$Msg [$x]['Message'].'</span></br>';
					echo'</div>';
				}
				else{
					echo'<span class="author">'.$Msg [$x]['User'].'</br></span>
						 <span class="messsage-text">'.$Msg [$x]['Message'].'</span></br>';
					echo'</div>';
				}
			}
		}
	}
	public function generateCourierLink($ContractID){
		$sql = "SELECT * FROM courier WHERE ContractID='".$ContractID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				return $row["TempID"];
			}
		}
		$TempID = rand(1,122).rand(2,122).rand(3,122).rand(4,122);
		$sql = "INSERT INTO `courier` (`ContractID`, `TempID`) VALUES ('".$ContractID."','".$TempID."')";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);    	
	 	return $TempID;
	}
	public function getContractInfoFromSmartContract($ContractID){
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$arr = array('REQUEST' => "ContractInformation",'CONTRACTID'=>$ContractID);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$raw_data = file_get_contents('http://localhost:3070/GetContractInfo');
		$data = json_decode($raw_data, true);
		return $data;
	}
	public function UpdateContract($offer,$daterequired,$paymentmode,$ContractID,$Type,$DeliveryMode){
		$offer = filter_var($offer,FILTER_SANITIZE_SPECIAL_CHARS);
		$daterequired = filter_var($daterequired,FILTER_SANITIZE_SPECIAL_CHARS);
		$sql = " UPDATE `contracts` SET `Status`= '".$Type." has updated',`NewOffer`='".$offer."',`DateRequired`= '".$daterequired."',`Payment Mode`= '".$paymentmode."',`TotalAccepted`= '0' ,`DeliveryMode` = '".$DeliveryMode."'  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$sql = " UPDATE `contracts` SET `TotalAccepted`=  0 WHERE `TotalAccepted`<0 ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function AcceptContract($ContractID,$Type){
		if($Type=="Seller"){
		$sql = " UPDATE `contracts` SET `Status`= '".$Type." has accepted', `TotalAccepted`= '1' WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		}
		if($Type=="Buyer"){
		$sql = " UPDATE `contracts` SET `Status`= '".$Type." has accepted', `TotalAccepted`= '2' WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		}
	}
	public function AcceptService($ContractID,$Type){
		if($Type=="Seller"){
			$sql = " UPDATE `contracts` SET `Status`= '".$Type." has accepted service', `TotalAccepted`= '1' WHERE `ContractID`= '".$ContractID."' ";
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
		}
		if($Type=="Buyer"){
			$sql = " UPDATE `contracts` SET `Status`= '".$Type." has accepted service', `TotalAccepted`= '2' WHERE `ContractID`= '".$ContractID."' ";
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
		}
	}
	public function RejectContract($ContractID){
		$sql = " UPDATE `contracts` SET `Status`= 'Rejected',`Transaction` = 'Complete', `TotalAccepted`= 0 WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function RequestRefund($ContractID){
		$sql = " UPDATE `contracts` SET `Status`= 'Requested Refund', `TotalAccepted`= 0 WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$sql = "SELECT * FROM contracts WHERE ContractID='".$ContractID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 
			$seller = $row["SellerUserID"];
		}
		$this->addNotification($seller ,"ContractID:".$ContractID.", Buyer has requested refund","MyContractsPage.php");
	}
	public function CancelOrder($ContractID){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{	
			$Transferfrom = $row['SellerUserID'];
			$Transferto = $row['BuyerUserID'];
			$Amount = $row['NewOffer'];
			$Type = $row['Payment Mode'];
			$TransactionMessage = $row['Transaction'];
			$Paid = $row['Paid'];
		}
		$TransactionMessage  = 'On-Going';
		$Paid = 'half';
		if($Type == "Half-STICoins")
		{
			$Amount = $Amount/2;
		}
		if($Type == "Full-STICoins"&& $TransactionMessage == "On-Going"){
			$Amount = $Amount;
		}
		if($Type != "Full-STICoins_Later"&& $TransactionMessage == "On-Going"){
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferto."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransfertoPubkey = $row['PublicKey'];
		}
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferfrom."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransferfromPubkey = $row['PublicKey'];
		}
		$host    = "localhost";
		$port    = 8080;
		if($this->getUID()==$Transferfrom){
		if($Paid!="refunded"&& $Paid!="none"){

		date_default_timezone_set('UTC');
		$this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "RefundBuyer",'CONTRACTID'=>$ContractID,'AMOUNT'=>$Amount,'BUYERPUBLICKEY'=>$TransfertoPubkey,'SELLERPUBLICKEY'=>$TransferfromPubkey  ,'ESCROWPRIVATE'=>$encryptedMessage);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$sql = " UPDATE `contracts` SET `Status`= 'Order Cancelled',`Paid`='refunded', `TotalAccepted`= 0,`Transaction` = 'Complete'  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		
		}
		}
		}
		$this->addNotification($Transferto ,"ContractID:".$ContractID.", Seller has cancelled order","MyContractsPage.php");
		return;
	}
	public function UpdateStatusDeal($ContractID){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{	
			$Type = $row['Payment Mode'];
			$SellerUID = $row['SellerUserID'];
		}
		if($Type == "Half-STICoins")
		{
			$Paid = 'half';
		}
		if($Type == "Full-STICoins"){
			$Paid = 'full';
		}
		if($Type == "Full-STICoins_Later"){
			$Paid = 'none';
		}
		$this->addNotification($SellerUID,"ContractID:".$ContractID.", deal has been made","MyContractsPage.php");
		$sql = " UPDATE `contracts` SET `Status`= 'Deal' , `Transaction` = 'On-Going',`Paid`='".$Paid."', `TotalAccepted`= 0 WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error);
	}
	public function UpdateStatusComplete($ContractID){
			$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			while($row = $result->fetch_assoc())
			{
				$BuyerID = $row['BuyerUserID'];
				$SellerID = $row['SellerUserID'];
				$Amount = $row['NewOffer'];
			}
			$Seller = $this->getPubKeyFromUID($SellerID);
			$Amount = $this->CommissionRate()*$Amount;
			$this->PayProduct($Seller,$Amount,'Commission Payment');
			$Data = array($BuyerID,$SellerID);
			$Jdata = json_encode($Data);
			$sql = " UPDATE `contracts` SET `Status`= 'Transaction Complete' ,`Paid`='full',`TransactionCloseDate`= '".date("Y-m-d")."',`RatingToken` = '".$Jdata."', `Transaction` = 'Complete' WHERE `ContractID`= '".$ContractID."' ";
			$result = $this->connect()->query($sql) or die($this->connect()->error);
			$this->addNotification($Seller,"ContractID:".$ContractID.", transaction is complete","MyContractsPage.php");
			
	}
	public function CheckAccepted($ContractID){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$NumOfAccepted = $row['TotalAccepted'];
			$Buyer = $row['BuyerUserID'];
		}
		return $NumOfAccepted ;
	}
	public function CheckServiceAccepted($ContractID){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$NumOfAccepted = $row['TotalAccepted'];
			$Buyer = $row['BuyerUserID'];
			$Seller = $row['SellerUserID'];
		}
		$Data = array($Buyer,$Seller);
		$Jdata = json_encode($Data);
		return $NumOfAccepted ;
	}
	public function InitContract($ContractID){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$Amount = $row['NewOffer'];
			$Seller = $row['SellerUserID'];
			$Buyer = $row['BuyerUserID'];
			$ProductID = $row['ProductID'];
		}
		$Seller = $this->getPubKeyFromUID($Seller);
		$Buyer = $this->getPubKeyFromUID($Buyer);
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "InitContract",'AMOUNT'=>$Amount,'PRODUCTID'=>$ProductID,'CONTRACTID'=>$ContractID,'BUYERPUBLICKEY'=>$Buyer,'SELLERPUBLICKEY'=> $Seller ,'ESCROWPRIVATE'=>$encryptedMessage);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
	}
	public function ToTransfer($ContractID){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$Seller = $row['SellerUserID'];
			$Type = $row['Payment Mode'];
			$Status = $row['Status'];
		}
		if($Seller==$this->getUID()){
			return True;
		}
		if($Type == "Half-STICoins"){
			return True;
		}
		if($Type == "Full-STICoins" && $Status == "Buyer has accepted"){
			return True;
		}
		if($Type == "Full-STICoins_Later" && $Status == "Buyer has accepted service"){
				return True;
		}
		else{
			return False;
		}
	}
	public function AmountToTransfer($ContractID){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			$Amount = $row['NewOffer'];
			$Type = $row['Payment Mode'];
			$Transactions =  $row['Transaction'];
		}
		if($Transactions == "Complete"){
			return $Amount;	
		}
		else{
			if($Type == "Half-STICoins"){
				$Amount= $Amount/2;
			}
			if($Type == "Full-STICoins" || $Type == "Full-STICoins_Later"){
				$Amount= $Amount;
			}
				return $Amount;
		}
	}
	public function TransferAmountAcceptService($ContractID,$Amount){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$Transferfrom = '';
		$Transferto = '';
		while($row = $result->fetch_assoc())
		{
			$Transferto = $row['SellerUserID'];
			$Transferfrom = $row['BuyerUserID'];
			$PaidStatus = $row['Paid'];
			$TotalAmount = $row['NewOffer'];
		}
		if($this->getUID()==$Transferfrom&&$PaidStatus!="full"){
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferto."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransfertoPubkey = $row['PublicKey'];
		}
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferfrom."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransferfromPubkey = $row['PublicKey'];
		}
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "ContractPayment",'CONTRACTID'=>$ContractID,'AMOUNT'=>$Amount,'BUYERPUBLICKEY'=>$TransferfromPubkey,'SELLERPUBLICKEY'=> $TransfertoPubkey ,'ESCROWPRIVATE'=>$encryptedMessage);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$this->UpdateStatusComplete($ContractID);	
		
		}
		return true;
	}
	public function TransferAmountAccept($ContractID,$Amount){
		
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$Transferfrom = '';
		$Transferto = '';
		while($row = $result->fetch_assoc())
		{
			$Transferto = $row['SellerUserID'];
			$Transferfrom = $row['BuyerUserID'];
			$PaidStatus = $row['Paid'];
		}
		if($this->getUID()==$Transferfrom&&$PaidStatus!="half"&&$PaidStatus!="full"){
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferto."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransfertoPubkey = $row['PublicKey'];
		}
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferfrom."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransferfromPubkey = $row['PublicKey'];
		}
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "ContractPayment",'AMOUNT'=>$Amount,'CONTRACTID'=>$ContractID,'BUYERPUBLICKEY'=>$TransferfromPubkey,'SELLERPUBLICKEY'=> $TransfertoPubkey ,'ESCROWPRIVATE'=>$encryptedMessage);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$this->UpdateStatusDeal($ContractID);
		return true;
		}
	}
		public function getEscrow(){
		$sql = "SELECT * FROM users where `AccountType` = 'Escrow' ORDER BY RAND() LIMIT 1" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{ 
			$this->EscrowPrivate = $row["PrivateKey"];
			return $row["PublicKey"];
		}
		}
		public function getEscrowPrivate(){
			return $this->EscrowPrivate;
		}
		public function addNewReview($Review,$ProductID){
			$Review = filter_var($Review,FILTER_SANITIZE_SPECIAL_CHARS);
			$sql = "SELECT * FROM product WHERE ProductID='".$ProductID."'" ;
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			while($row = $result->fetch_assoc())
			{ 
				$Data = $row['Review'];
				$Seller = $row['SellerUserID'];
			}
			$Data = json_decode($Data, true);
			$NewData= array("Review"=>$Review, "ProductID"=>$ProductID, "User"=>$this->getUID(),"Date"=>date("Y-m-d"));
			array_push($Data,$NewData);
			$JData = json_encode($Data);
			$sql="UPDATE `product` SET `Review`='".$JData."' WHERE `ProductID`='".$ProductID."'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);    
			$this->addNotification($Seller,"ProductID:".$ProductID.", ".$this->getUID()." has added a review on your product","ProductPage.php?ID=".$ProductID);
		}
		public function addNewUserReview($Review,$UserID){
			$Review = filter_var($Review,FILTER_SANITIZE_SPECIAL_CHARS);
			$sql = "SELECT * FROM users WHERE UserID='".$UserID."'" ;
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			while($row = $result->fetch_assoc())
			{ 
				$Data = $row['Review'];
			}
			$Data = json_decode($Data, true);
			$NewData= array("Review"=>$Review, "UserID"=>$UserID, "User"=>$this->getUID(),"Date"=>date("Y-m-d"));
			array_push($Data,$NewData);
			$JData = json_encode($Data);
			$sql="UPDATE `users` SET `Review`='".$JData."' WHERE `UserID`='".$UserID."'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);    
			$this->addNotification($UserID,$this->getUID()." has added a review on your profile","ProfilePage.php?ID=".$UserID);
		}
		public function RateUser($Rating,$UserID) {
		$sql = "SELECT * FROM users WHERE UserID='".$UserID."'" ;
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			while($row = $result->fetch_assoc())
			{ 
				$Data = $row['Rating'];
			}
			$Data = json_decode($Data, true);
			$Data['Rating'] = ($Rating + $Data['Rating'])/2;
			$Data['NumOfReviewers'] = 	$Data['NumOfReviewers'] +1;
			$JData = json_encode($Data);
			$sql="UPDATE `users` SET `Rating`='".$JData."' WHERE `UserID`='".$UserID."'";
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
		}
		public function PostContractRateBuyer($Rating,$UserID,$Review,$ReviewProduct,$ProductID){
			$this->RateUser($Rating,$UserID);
			if(!empty($Review)){
				$this->addNewUserReview($Review,$UserID);
			}
			if(!empty($ReviewProduct)){
				$this->addNewReview($ReviewProduct,$ProductID);
			}
		}
		public function PostContractRateSeller($Rating,$UserID,$Review){
			$this->RateUser($Rating,$UserID);
			if(!empty($Review)){
				$this->addNewUserReview($Review,$UserID);
			}
		}
		public function RemoveProduct($ProductID){
			$sql="DELETE FROM product WHERE ProductID='$ProductID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function UnreportProduct($ProductID){
			$sql="UPDATE `product` SET `Reported`= '0' WHERE ProductID='$ProductID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function ReportProduct($ProductID){
			$sql="UPDATE `product` SET `Reported`=`Reported`+1 WHERE ProductID='$ProductID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function UnreportUser($UID){
			$sql="UPDATE `users` SET `Reported`= '0' WHERE UserID='$UID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function ReportUser($UID){
			$sql="UPDATE `users` SET `Reported`=`Reported`+1 WHERE UserID='$UID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function UnreportContract($CID){
			$sql="UPDATE `contracts` SET `Reported`= '0' WHERE ContractID='$CID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function ReportContract($CID){
			$sql="UPDATE `contracts` SET `Reported`=`Reported`+1 WHERE ContractID='$CID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function UnlistProduct($ProductID){
			$sql="UPDATE `product` SET `Status`='Unlisted' WHERE ProductID='$ProductID'";
			$result = $this->connect()->query($sql) or die($this->connect()->error);  
		}
		public function checkAccountInNetwork($WalletPubKey){
			$host    = "localhost";
			$port    = 8080;
			$arr = array('REQUEST' => "CheckAccount",'PUBKEY' =>$WalletPubKey);
			$message = json_encode($arr);
			$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
			$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
			if($result) { 
			socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
			$result = socket_read ($socket, 1024) or die("Could not read server response\n");
			}
			socket_close($socket);
			$raw_data = file_get_contents('http://localhost:3001/CheckAccount');
			$data = json_decode($raw_data, true);
			if ($data['RESPONSE']){
				return true;
			}
			else{
				return false;
			}
		}
		public function creditCardOut($amount){
			$host    = "localhost";
			$port    = 8080;
			$arr = array('REQUEST' => "CreditCardPayOut",'AMOUNT'=>$amount,'UserID'=>$_SESSION['ID']);
			$message = json_encode($arr);
			$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
			$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
			if($result) { 
			socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
			$result = socket_read ($socket, 1024) or die("Could not read server response\n");
			}
			socket_close($socket);
			$raw_data = file_get_contents('http://localhost:3020/PayOut');
			$data = json_decode($raw_data, true);
			$this->TempCardAccount=$data['ACCOUNT'];
			$this->TempAmount=$data['AMOUNT'];
			return $data['RESPONSE'];
		}
			public function creditCardIn($amount){
			$host    = "localhost";
			$port    = 8080;
			$arr = array('REQUEST' => "CreditCardPayIn",'AMOUNT'=>$amount,'UserID'=>$_SESSION['ID']);
			$message = json_encode($arr);
			$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
			$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
			if($result) { 
			socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
			$result = socket_read ($socket, 1024) or die("Could not read server response\n");
			}
			socket_close($socket);
		}
		public function TopUpToSTICoin($amount,$TxID){
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage2 = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "TopUpSTIC",'AMOUNT'=>$amount,'ESCROWPRIVATE'=>$encryptedMessage2 ,'PUBKEY' =>$this->getPubKey(),'TXID'=>$TxID);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$raw_data = file_get_contents('http://localhost:3002/TopUpSTIC');
		$data = json_decode($raw_data, true);
		if($data['Transaction']== "Success"){
			return $data['Transaction'];
		}
		else{
			return $data['Transaction'];
		}
		}
		public function RedeemSTICoin(){
		if(!isset($this->TempCardAccount)){
			return false;
		}
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "RedeemSTIC",'AMOUNT'=>$this->TempAmount,'ACCOUNT'=>  $this->TempCardAccount,'ESCROWPRIVATE'=> $encryptedMessage ,'PUBKEY' =>$this->getPubKey());
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$this->TempCardAccount = '';
		$this->TempAmount = '';
		unset($this->TempCardAccount);
		unset($this->TempAmount);
		$raw_data = file_get_contents('http://localhost:3003/RedeemSTIC');
		$data = json_decode($raw_data, true);
		if($data['Transaction']== "Success"){
			return true;
		}
		else{
			return false;
		}
	}
	public function PayProduct($PubKey,$Amount,$Title){
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$EscrowPub= $this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "PayForProduct",'TITLE'=>$Title,'AMOUNT'=>$Amount,'ESCROWPRIVATE'=> $encryptedMessage ,'ESCROWPUBLIC'=> $EscrowPub ,'PUBKEY' =>$PubKey);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$raw_data = file_get_contents('http://localhost:3004/PayForProduct');
		$data = json_decode($raw_data, true);
		if($data['Transaction']== "Success"){
			return $data['Transaction'];
		}
		else{
			return $data['Transaction'];
		}
	}
	public function ListProduct($Name,$Category,$Description,$Cost,$Caption ,$FileNew){
			$expirydate = date("Y-m-d", strtotime("+6 month"));
			$Name = filter_var($Name,FILTER_SANITIZE_SPECIAL_CHARS);
			$Description = filter_var($Description,FILTER_SANITIZE_SPECIAL_CHARS);
			$Caption = filter_var($Caption,FILTER_SANITIZE_SPECIAL_CHARS);
			$TagArray = array();
			$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $Name, -1, PREG_SPLIT_NO_EMPTY);
			$TagArray = array_merge($split,$TagArray);
			$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $Category, -1, PREG_SPLIT_NO_EMPTY);
			$TagArray = array_merge($split,$TagArray);
			$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $Caption, -1, PREG_SPLIT_NO_EMPTY);
			$TagArray = array_merge($split,$TagArray);
			$JsonData = json_encode($TagArray);
			while(true){					
					$ProductID = chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).str_pad(rand(0000,9999),4,0,STR_PAD_LEFT). substr(rand(0000,9999), 2, 4);
					$result = $this->connect()->query("SELECT count(*) as 'c' FROM product WHERE ProductID='".$ProductID."'");
					$count = $result->fetch_object()->c;
					if ($count==0)
					  {
						break;
					  }
				}
			 mysqli_query($this->connect(),"INSERT INTO `product` (`ProductID`, `ProductCategory`, `ProductDescription`, `ProductCaption`, `ProductInitialPrice`, `ProductName`,`SellerUserID`,`Image`,`DateOfListing`,`DateOfExpiry`,`Tags`) VALUES ('".$ProductID."','".$Category."','".$Description."','".$Caption."','".$Cost."','".$Name."','".$this->getUID()."','".$FileNew."','".time()."','".$expirydate."','".$JsonData."')") or die(mysqli_error($this->connect()));
				return $ProductID;
	}
		public function UpdateProduct($ProductID,$Name,$Category,$Description,$Cost,$Caption,$File){
				$Name = filter_var($Name,FILTER_SANITIZE_SPECIAL_CHARS);
				$Category = filter_var($Category,FILTER_SANITIZE_SPECIAL_CHARS);
				$Description = filter_var($Description,FILTER_SANITIZE_SPECIAL_CHARS);
				$Caption = filter_var($Caption,FILTER_SANITIZE_SPECIAL_CHARS);
				$TagArray = array();
				$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $Name, -1, PREG_SPLIT_NO_EMPTY);
				$TagArray = array_merge($split,$TagArray);
				$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $Category, -1, PREG_SPLIT_NO_EMPTY);
				$TagArray = array_merge($split,$TagArray);
				$split = preg_split("/[^\w]*([\s]+[^\w]*|$)/", $Caption, -1, PREG_SPLIT_NO_EMPTY);
				$TagArray = array_merge($split,$TagArray);
				$JsonData = json_encode($TagArray);
				$sql = "UPDATE `product` SET `ProductName`= '$Name',`ProductCategory`='$Category',`ProductDescription`='$Description',`ProductCaption`='$Caption',`ProductInitialPrice`='$Cost',`Image`='$File',`Tags`='$JsonData' WHERE `ProductID` = '$ProductID'";
				$result = $this->connect()->query($sql) or die( $this->connect()->error);    	
				return true;
	}
	public function temp(){
		$sql = "SELECT * FROM product";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 	
			$ProductObj = new Products();
			$ProductObj->InitialiseProduct($row['ProductID']);
			$this->UpdateProduct($ProductObj->ProductID,$ProductObj->ProductName,$ProductObj->ProductCategory,$ProductObj->ProductDescription,$ProductObj->ProductInitialPrice,$ProductObj->ProductCaption,$ProductObj->Image);
		}	
	}
	public function EditProfileValidate($FName,$LName,$ContactNumber,$DispName,$Address,$ProfilePicCurrent,$ProfilePicDest,$fileempty){
		$FName = preg_replace('/(\'|&#0*39;)/', '', $FName);
		$LName = preg_replace('/(\'|&#0*39;)/', '', $LName);
		$DispName = preg_replace('/(\'|&#0*39;)/', '', $DispName);
		$Address = preg_replace('/(\'|&#0*39;)/', '', $Address);

		$ID = $this->getUID();
		if(! $fileempty){
		$sql = "UPDATE `users` SET `DisplayName`= '$DispName',`FirstName`= '$FName',`LastName`='$LName',`ContactNumber`='$ContactNumber',`Address`= '$Address', `ProfilePicture`='$ProfilePicDest' WHERE `UserID` = '$ID' ";
			$result = $this->connect()->query($sql) or die( $this->connect()->error);    	
			echo $this->connect()->error;
			move_uploaded_file($ProfilePicCurrent, $ProfilePicDest);
			if($this->ProfilePic!="profilepictures/default.jpg"){
				unlink($this->ProfilePic);
			}
		}
		else{
			$sql = "UPDATE `users` SET `DisplayName`= '$DispName',`FirstName`= '$FName',`LastName`='$LName',`ContactNumber`='$ContactNumber',`Address`= '$Address',`ProfilePicture`='$this->ProfilePic' WHERE `UserID` = '$ID' ";	
			$result = $this->connect()->query($sql) or die( $this->connect()->error);  
		}
		return "validated";
	}
	public function EditProfileEmailValidate($Email){
		$Email = preg_replace('/(\'|&#0*39;)/', '', $Email);
		$sql = "SELECT * FROM users WHERE Email='".$Email."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		if ($result->num_rows > 1) 
		{
			return "Email error";
		}
		$ID = $this->getUID();

		$sql = "UPDATE `users` SET `Email`= '$Email' WHERE `UserID` = '$ID' ";	
		$result = $this->connect()->query($sql) or die( $this->connect()->error);  
		
		return "validated";
	}
	public function ChangePasswordValidate($Pass,$NewPass){
			$Pass = preg_replace('/(\'|&#0*39;)/', '', $Pass);
			$sql = "SELECT Password FROM users WHERE UserID='".$this->getUID()."'" ;
			$result = $this->connect()->query($sql) or die($this->connect()->error); 
			$validated = false;
			while($row = $result->fetch_assoc()){ 
				if(Password_verify($Pass,$row["Password"]))
				{	$validated = true;
				}
			}	 
			if($validated)
			{
				$hashedpassword = password_hash($NewPass, PASSWORD_DEFAULT);
				$ID = $this->getUID();
				$sql = "UPDATE `users` SET `Password`= '$hashedpassword' WHERE `UserID` = '$ID' ";
				$result = $this->connect()->query($sql) or die( $this->connect()->error);   
				return "Validated";
			}
			else{
				return "Wrong Password";
			}
	}
}
class Admin extends StandardUser 
{
	public function __construct($Object){
		$this->UID = $Object->getUID();
		$this->DisplayName =  $Object->getDisplayName();
		$this->PubKey =  $Object->getPubKey();
		$this->Email = $Object->getEmail();
		$this->FirstName =  $Object->getFirstName();
		$this->LastName =  $Object->getLastName();
		$this->DOB =  $Object->getDOB();
		$this->ContactNumber =  $Object->getContactNumber();
		$this->Address =  $Object->getAddress();
		$this->AccountType =  $Object->getAccountType();
		$this->AccountBalance = $Object->getAccountBalance();
		$this->ProfilePic = $Object->ProfilePic;
		$this->Reported = $Object->Reported;
	}
	public function HaltTransaction($ContractID){
		$sql = " UPDATE `contracts` SET `Status`='Admin has halted this transaction' WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function ResumeTranasction($ContractID,$Transaction){
		if($Transaction=="On-Going"){
			$sql = " UPDATE `contracts` SET `Status`='Deal' WHERE `ContractID`= '".$ContractID."' ";
		}
		if($Transaction=="Negotiating"){
			$sql = " UPDATE `contracts` SET `Status`='Negotiating' WHERE `ContractID`= '".$ContractID."' ";
		}
		if($Transaction=="Complete"){
			$sql = " UPDATE `contracts` SET `Status`='Transaction Complete' WHERE `ContractID`= '".$ContractID."' ";
		}
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function ListOfAdminContracts($ContractType){
	if($ContractType=="All"){
	$sql = "SELECT * FROM contracts ORDER BY TransactionOpenDate" ;
	}
	if($ContractType=="Overdue contracts"){
	$sql = "SELECT * FROM `contracts` WHERE DATEDIFF(`DateRequired`,now()) < 0 OR DATEDIFF(`DateRequired`,`TransactionOpenDate`) > 3 AND `Transaction` != 'Complete' " ;
	}
	if($ContractType=="Requested Refund"){
	$sql = "SELECT * FROM contracts WHERE `Status` = 'Requested Refund' ORDER BY TransactionOpenDate" ;
	}
	$result = $this->connect()->query($sql) or die($this->connect()->error); 
	if ($result->num_rows == 0) 
		{
			return false;
		}
	$ArrayOfContracts = array();
	while($row = $result->fetch_assoc())
	{
		array_push($ArrayOfContracts,$row['ContractID']);
	}
	return $ArrayOfContracts;
	}
	public function Refund_Admin($ContractID,$Amount){
		$sql = "SELECT * FROM contracts  WHERE `ContractID`= '".$ContractID."' ";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		$Transferfrom = '';
		$Transferto = '';
		while($row = $result->fetch_assoc())
		{
			$Transferfrom = $row['SellerUserID'];
			$Transferto = $row['BuyerUserID'];
			$Paid = $row['Paid'];
		}
		if($Paid!="refunded"&&$Paid!="none"){
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferto."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransfertoPubkey = $row['PublicKey'];
		}
		$sql = "SELECT * FROM users WHERE UserID ='".$Transferfrom."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{
			$TransferfromPubkey = $row['PublicKey'];
		}
		$host    = "localhost";
		$port    = 8080;
		date_default_timezone_set('UTC');
		$this->getEscrow();
		$textToEncrypt = $this->getEscrowPrivate();;
		$encryptionMethod = "AES-256-CBC";
		$secret = "My32charPasswordAndInitVectorStr";  //must be 32 char length
		$iv = substr($secret, 0, 16);
		$encryptedMessage = openssl_encrypt($textToEncrypt, $encryptionMethod, $secret,0,$iv);
		$arr = array('REQUEST' => "RefundBuyer",'CONTRACTID'=>$ContractID,'AMOUNT'=>$Amount,'BUYERPUBLICKEY'=>$TransfertoPubkey ,'SELLERPUBLICKEY'=> $TransferfromPubkey ,'ESCROWPRIVATE'=>$encryptedMessage);
		$message = json_encode($arr);
		$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
		$result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");  
		if($result) { 
		socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
		$result = socket_read ($socket, 1024) or die("Could not read server response\n");
		}
		socket_close($socket);
		$this->addNotification($Transferfrom ,"ContractID:".$ContractID.", Buyer has been refunded successfully","MyContractsPage.php");
		$this->addNotification($Transferto ,"ContractID:".$ContractID.", You have been refunded successfully","MyContractsPage.php");
		return;
		}
	}
	public function ListOfUsers(){
		$sql = "SELECT * FROM users where AccountType = 'Standard'";
		$usersarray = array();
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 	
			array_push($usersarray,$row['UserID']);
		}
		return $usersarray;
	}
	public function ListOfEscrows(){
		$sql = "SELECT * FROM users where `AccountType` = 'Escrow'";
		$pubkeysarray = array();
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 	
			array_push($pubkeysarray,$row['PublicKey']);
		}
		return $pubkeysarray;
	}
	public function ListOfReportedProducts(){
		$sql = "SELECT * FROM product where `Reported` >0 ORDER BY `Reported` DESC";
		$arrayret = array();
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 	
			array_push($arrayret,$row['ProductID']);
		}
		return $arrayret;
	}
	public function ListOfReportedUsers(){
		$sql = "SELECT * FROM users where `Reported` >0 ORDER BY `Reported` DESC";
		$arrayret = array();
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 	
			array_push($arrayret,$row['UserID']);
		}
		return $arrayret;
	}
	public function ListOfReportedContracts(){
		$sql = "SELECT * FROM contracts where `Reported` >0 ORDER BY `Reported` DESC";
		$arrayret = array();
		$result = $this->connect()->query($sql) or die($this->connect()->error);    
		while($row = $result->fetch_assoc())
		{ 	
			array_push($arrayret,$row['ContractID']);
		}
		return $arrayret;
	}
	public function suspendUser($ID,$SuspensionDate){
		$data = array('Suspended',$SuspensionDate);
		$data = json_encode($data);
		$sql = "UPDATE `users` SET `Status`= '$data' WHERE `UserID` = '$ID' ";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);    	
	}
	public function Removestatus($ID){
		$data = array('Normal','');
		$data = json_encode($data);
		$sql = "UPDATE `users` SET `Status`= '$data' WHERE `UserID` = '$ID' ";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);
	}
	public function Ban($ID){
		$data = array('Banned','');
		$data = json_encode($data);
		$sql = "UPDATE `users` SET `Status`= '$data' WHERE `UserID` = '$ID' ";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);
	}
	public function MakeAdmin($ID){
		$sql = "UPDATE `users` SET `AccountType`='Administrator' WHERE `UserID` = '$ID' ";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);
	}
	public function RemoveEscrow($pubkey){
		$sql = "DELETE FROM users WHERE PublicKey='$pubkey'";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);
	}
	public function AddEscrow($pubkey,$privatekey){
		$pubkey = filter_var($pubkey,FILTER_SANITIZE_SPECIAL_CHARS);
		$privatekey = filter_var($privatekey,FILTER_SANITIZE_SPECIAL_CHARS);
		$privatekey = preg_replace('/(\'|&#0*39;)/', '', $privatekey);
		while(true){					
					$UserID = "Escrow". substr(rand(0000,9999), 2, 4);
					$result = $this->connect()->query("SELECT count(*) as 'c' FROM users WHERE UserID='".$UserID."'");
					$count = $result->fetch_object()->c;
					if ($count==0)
					  {
						break;
					  }
				}
				$sql = "INSERT INTO users (UserID,PublicKey,PrivateKey,AccountType)VALUES('".$UserID."','".$pubkey."','".$privatekey."','Escrow' )";
				$result = $this->connect()->query($sql) or die( $this->connect()->error);    	
	}
	public function AddAds($File,$UserID){
		$UserID = filter_var($UserID,FILTER_SANITIZE_SPECIAL_CHARS);
		$sql = "INSERT INTO  `advertisements` (`AdsImage`, `UserID`)VALUES('".$File."','".$UserID."')";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);    
	}
	public function RemoveAds($ImageID){
		$sql = "DELETE FROM `advertisements` WHERE AdsImage='$ImageID'";
		$result = $this->connect()->query($sql) or die( $this->connect()->error);
	}
}