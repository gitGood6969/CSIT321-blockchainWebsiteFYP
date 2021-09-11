<?php
class Products
{

	public $ProductID;				

	public $ProductName;				

	public $ProductCategory;				

	public $ProductDescription;				

	public $ProductCaption;				

	public $ProductInitialPrice;		

	public $DateOfListing;

	public $DateOfExpiry;			

	public $SellerUserID;			

	public $Status;				

	public $Image;				

	public $Review;
	
	public $Reported;
	
	public function connect(){
		$servername= "localhost";
		$username = "root";
		$password = "";
		$dbname = "sticdb";
		$conn = new mysqli($servername, $username, $password, $dbname);
		return $conn;
		
	}
	
	public function InitialiseProduct($ProductID)
	{
		$sql = "SELECT * FROM product WHERE ProductID ='".$ProductID."'" ;
		$result = $this->connect()->query($sql) or die($this->connect()->error); 
		if ($result->num_rows == 0) 
			{
				return false;

			}	
		while($row = $result->fetch_assoc())
		{ 	
			
			$this->ProductID = $row["ProductID"];		
 
			$this->ProductName	= $row["ProductName"];	

			$this->ProductCategory	= $row["ProductCategory"];			

			$this->ProductDescription  = $row["ProductDescription"];		

			$this->ProductCaption = $row["ProductCaption"];

			$this->ProductInitialPrice	= $row["ProductInitialPrice"];

			$this->DateOfListing  = date('d-m-Y',$row["DateOfListing"]);

			$this->DateOfExpiry  = $row["DateOfExpiry"];			

			$this->SellerUserID	 = $row["SellerUserID"];		

			$this->Status = $row["Status"];		

			$this->Image  = $row["Image"];				
			
			$this->Reported  = $row["Reported"];
			
			$this->Review  = $row["Review"];
	
	}

		return true;
	}

}
	
?>