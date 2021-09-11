<?php
class Contracts
{

	public $TransactionID;							

	public $TransactionOpenDate;				

	public $TransactionCloseDate;				

	public $ContractID;				

	public $BuyerUserID;		

	public $SellerUserID;

	public $ProductID;			

	public $DateRequired;			

	public $InitialOffer;				

	public $NewOffer;				
	
	public $Status;
	
	public $PaymentMode;
	
	public $TotalAccepted;
	
	public $Transaction;
	
	public $RatingToken; 
	
	public $Reported; 
	
	public $Paid;
	
	public $DeliveryMode;
	
	
	public function connect(){
		$servername= "localhost";
		$username = "root";
		$password = "";
		$dbname = "sticdb";
		$conn = new mysqli($servername, $username, $password, $dbname);
		return $conn;
		
	}
	
	public function InitialiseContract($ContractID)
	{
		$sql = "SELECT * FROM contracts WHERE ContractID ='".$ContractID."'" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 0) 
			{
				return false;

			}	
		while($row = $result->fetch_assoc())
		{ 	
			
			$this->TransactionID = $row["TransactionID"];		
 
			$this->TransactionOpenDate	= $row["TransactionOpenDate"];	

			$this->TransactionCloseDate	= $row["TransactionCloseDate"];			

			$this->ContractID  = $row["ContractID"];		

			$this->BuyerUserID = $row["BuyerUserID"];

			$this->SellerUserID	= $row["SellerUserID"];

			$this->ProductID  = $row["ProductID"];

			$this->DateRequired  = $row["DateRequired"];			

			$this->InitialOffer	 = $row["InitialOffer"];		

			$this->NewOffer = $row["NewOffer"];		
			
			$this->Status = $row["Status"];	

			$this->PaymentMode = $row["Payment Mode"];	
			
			$this->TotalAccepted = $row["TotalAccepted"];	
			
			$this->Transaction = $row["Transaction"];	
			
			$this->Reported = $row["Reported"];	
			
			$this->Paid = $row["Paid"];	
			
			$this->DeliveryMode = $row["DeliveryMode"];	
			
			$this->RatingToken = json_decode($row["RatingToken"],true);
	}

		return true;
	}
	public function ReduceToken($ID){
		if (($key = array_search($ID, $this->RatingToken)) !== false) {
			unset($this->RatingToken[$key]);
		}
		$Jdata = json_encode($this->RatingToken);
		$sql="UPDATE `contracts` SET `RatingToken`= '".$Jdata."' WHERE `ContractID`='".$this->ContractID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
	}
	public function getContractID($TempID){
		$sql = "SELECT * FROM courier WHERE TempID='".$TempID."'";
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		while($row = $result->fetch_assoc())
		{
			return $row["ContractID"];
		}
	}
}
	
?>