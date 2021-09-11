<?php require_once("NavBar.php");

if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}
$BaseUserOBJ = new BaseUser("Edit Product");
$NameErr = $CategoryErr = $CaptionErr = $DescriptionErr = $CostErr = $FileErr= "";
if($_SESSION['ID'] == $BaseUserOBJ->getProductOwner($_GET['ID'])||$_SESSION['Object']->getAccountType()=="Administrator"){
$ProductObj = new Products();
$ProductObj->InitialiseProduct($_GET['ID']);

}
else{
	if(!$_SESSION['Object']->getAccountType()=="Administrator"){
	echo '<script> location.replace("index.php")</script> ';
	}
}


$submit = true;

if(isset($_POST['submit'])){

$Name = test_input($_POST["Name"]);
$Category = $_POST["Category"];
$Caption = test_input($_POST["Caption"]); 
$Description = test_input($_POST["Description"]); 
$Cost = test_input($_POST["Cost"]);

if (empty($_POST["Category"])){
	$CategoryErr= "Category is required";	
	$submit = false;
}

if (empty($_POST["Caption"])){
	$CaptionErr= "Caption is required";	
	$submit = false;
}


if (empty($_POST["Name"])){
	$NameErr= "Name is required";	
	$submit = false;
}



if (empty($_POST["Description"])){
	$DescriptionErr= "Description is required";	
	$submit = false;
}

if (empty($_POST["Cost"])){
	$CostErr= "Initial cost is required";	
	$submit = false;
}


if (empty($_POST["file"])){

		$file = $_FILES['file'];
		$File = $_FILES['file']['name'];
		$fileTmpName = $_FILES['file']['tmp_name'];
		$fileSize = $_FILES['file']['size'];
		$FileError = $_FILES['file']['error'];
		$fileType = $_FILES['file']['type'];
		
		$fileExt = explode('.',$File);
		$fileActualExt = strtolower(end($fileExt));
		$allowed = array('jpg','jpeg','png','pdf','');
	
		if(in_array($fileActualExt, $allowed)){
		
			if($FileError == 0){
					
				if($fileSize < 5000000){	//if file size less then 50mb
					$FileNew = uniqid('', true).".".$fileActualExt;
					$submit = true;

				} else {
					
					$FileErr= "The file is too big!";
					$submit = false;
				}
			} 
			
		} else {
				
				$FileErr= "You cannot upload files of this type!";
				$submit = false;
			
		
		}
	}	
}
else{
$File = '';
$Name = '';
$Category ='';
$Caption = '';
$Description = '';
$Cost = '';

}	
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
<style>
.List_GUI{
clear: both;
margin-left:auto;
margin-right:auto;
margin-bottom:20px;
width:1000px;
height:1000px;

text-align:left;

font-size:20px;
border:1px solid black;
border-radius:20px;
box-shadow:5px 5px gray;

}
label,span{
display:inline-block;
width:200px;
margin-right:5px;
text-align:center;
}

span{
color:red;
width:200px;
}
hr{
background-color:white;
}
input[type="text"]{
font-family: arial;
width:200px;
}
.Post_Insert_GUI{
font-family: 'Roboto';font-size: 22px;
border: 2px solid purple;
border-radius: 25px;
background-color:white;
text-align: center;
display: inline-block;

height:700px;
width:700px;
cursor:pointer;
margin-left:auto;
margin-right:auto;
}

#output{
margin-top:5%;
margin:auto;
width:300px;
height:300px;
border:none;
object-fit: cover;
}
.Post_Insert_GUI #output{
margin-top:5%;
margin:auto;
width:60%;
height:60%;
border:none;
object-fit: cover;
}
button,input[type=submit],input[type=button] {
border:none;
background-color:purple;
color:white;
font-size:20px;
border-radius:10px;
margin-right:10px;
float:right;
}
input[type=submit]:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
input[type=button]:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
button:hover {

outline:60%;
filter: drop-shadow(0 0 5px purple);
}
h2{

margin-left:2%;
}
textarea {
resize: none;
}
</style>
	
 <div class="List_GUI">
 <h1 style="font-size:40px"><center>Edit Product</center></h1>
   <form method="post" enctype="multipart/form-data">  
	
	<h2>Basic Product Information</h2>
		<center><image id="output" src="<?php echo $ProductObj->Image;?>" width="300" height="300"></center>

		<label>Upload File:</label>
		<input type="file" name="file" accept="image/*" value="<?php echo $ProductObj->Image;?>" onchange="loadFile(event)">

		<script>
		var loadFile = function(event) {
		var output = document.getElementById('output');
		output.src = URL.createObjectURL(event.target.files[0]);
		output.onload = function() {
		URL.revokeObjectURL(output.src) // free memory
		}
		};
		</script>
		<span class="error"><?php echo $FileErr;?></span><br />
	
		<label>Name:</label>
		<input type="text" name="Name" value="<?php echo $ProductObj->ProductName;?>">
		<span class="error"><?php echo $NameErr;?></span><br />
		
		<label>Category:</label>
		  <select style="background-color:black;color:white;" id="Category" name="Category">
			<?php
			$myfile = fopen("Categories.txt", "r") or die("Unable to open file!");
			while(($line = fgets($myfile)) !== false) {
			$arr = explode(":",$line);
			if($arr[0]==$ProductObj->ProductCategory){
							echo 
			"<option style='background-color:black;color:white;' value='".$arr[0]."' selected>".$arr[0]."</option>";
			}
			else{
			echo 
			"<option style='background-color:black;color:white;' value='".$arr[0]."'>".$arr[0]."</option>";
			
			}}fclose($myfile);
			?>
		  </select><br />
	<hr>
	<h2>Description of product</h2>  
	
		<label>Product Caption:</label>
		<textarea id="Caption" name="Caption" rows="1" cols="50" value=""><?php echo $ProductObj->ProductCaption;?></textarea>
		<span class="error"><?php echo $CaptionErr;?></span><br />
	
		<label style=" vertical-align: middle;">Product Description:</label>
		<textarea style=" vertical-align: middle;" id="Description" name="Description" rows="4" cols="50" value=""><?php echo $ProductObj->ProductDescription?></textarea>
		<span class="error"><?php echo $DescriptionErr;?></span><br /><br />
		
		<label>Initial Cost <?php echo $_SESSION['Object']->getCurrency() ?>:</label>

		<input type="number" id="Cost" name="Cost" min="0.00" step="any" value="<?php echo $ProductObj->ProductInitialPrice;?>">
		<span class="error"><?php echo $CostErr;?></span><br />
		<br />
		
		
		<input type="submit" name="submit" value="Done">
    </form>
</div>
<script>
		function clickproduct(ID){
			location.replace("ProductPage.php?ID="+ID);
		}
</script>
<?php

if(isset($_POST['submit'])&& $submit){
	$File = $ProductObj->Image;

	if (empty($_POST["file"])){
	if(!empty($FileNew)){
		$fileDestination = 'images/'.$FileNew;
		move_uploaded_file($fileTmpName, $fileDestination);
		$File = $fileDestination;
	}
	}

	echo'<style> .List_GUI{display:none;}</style>';
	if($Name==$ProductObj->ProductName && $Category==$ProductObj->ProductCategory && $Description==$ProductObj->ProductDescription && $Cost==$ProductObj->ProductInitialPrice&& $Caption==$ProductObj->ProductCaption&& $File==$ProductObj->Image){
		
		echo'<script>alert("No changes were made")</script>';
		echo'<script>location.replace("ProductPage.php?ID='.$ProductObj->ProductID.'");</script>';
	echo'<script>history.pushState({}, "", "")</script>';
	exit();
	}
	if($_SESSION['Object']->UpdateProduct($_GET['ID'],$Name,$Category,$Description,$Cost,$Caption,$File)){
	echo'<script>history.pushState({}, "", "")</script>';
echo'<center>
		<div class="Post_Insert_GUI" onclick="clickproduct(this.id)" id = "'.$ProductObj->ProductID.'">
		<a href="#" class="fill-div"></a>
			</br>
			<center>Successfully Listed product!</center>
			<center>Product Confirmation</center>
			<img id="output"  src="'.$File.'" ></br>
			 <h2>Product ID:'.$ProductObj->ProductID.'</h2>
			<h2>'.$Name.'</h2>
			<center><h1>Head over to it\'s page now!</h1></center>
			
		</div></center>';
	echo'<script>history.pushState({}, "", "")</script>';
	exit();
	}
	
	

}



?>
<?php require_once("Footer.php");?>