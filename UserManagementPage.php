<?php require_once("NavBar.php");?>
<style>

.formcontainer{
	width:700px;
	height:150px;
	text-align:left;
	margin:auto;
	border:1px solid black;
	border-radius:20px;
	box-shadow:5px 5px gray;
}
.formcontainer input[type="submit"]{
	margin-left:1%;
	float:left;
	
}
.formcontainer input[type="text"]{

	text-align:center;
	
}
#AllUsers{
	
	background-color:white;
	width:1700px;
	
	color:purple;
	border:1px solid grey;
	margin:auto;
	text-align:center;
}
 table,tr,th,td{
	
	border:1px solid #e8e6e6; 
 }
 tr{
	 height:50px;
	 vertical-align: text-bottom;
 }
 button,input[type=submit],input[type=button] {
	margin-top:20px;
	border:none;
	background-color:purple;
	color:white;
	font-size:20px;
	border-radius:10px;
	margin-right:10px;
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
</style>

<?php 

if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}
if($_SESSION['Object']->getAccountType()!="Administrator"){
	echo '<script> location.replace("index.php")</script> ';
}
if(!isset($_GET['ID'])){
	$_GET['ID'] = '';
}
$ArrayOfUsers = $_SESSION['Object']->ListOFUsers();
echo'<div class="formcontainer"><form method="post" action="UserManagementController.php"></br>
<center><Label>UserID : </Label><input type ="text" name="hiddenval" value="'.$_GET['ID'].'"></center></br>
<input type="submit" name="Ban" value="Ban user">
<input type="submit" name="Suspend" value="Suspend user">
<input type="submit" name="Remove" value="Unban/Unsuspend user">
<input type="submit" name="MakeAdmin" value="Make User Admin">
</form></div>
';
echo'<hr><table  id="AllUsers"  class="table table-borderless table-dark">';
echo'<tr><th>UserID</th>
<th>DisplayName</th>
<th>FirstName</th>
<th>LastName</th>
<th>Email</th>
<th>Rating</th>
<th>Status</th>
<th>Actions</th></tr>

';
echo'<h1>User Management Table</h1>';
if (!isset ($_GET['page']) ) {  
	$page = 1;  
} else {  
	$page = $_GET['page'];  
}
$Data_per_page = 10;
$number_of_page = ceil(sizeof($ArrayOfUsers)/$Data_per_page) ;
if(round($number_of_page) == 0){
	
	$number_of_page = 1;
}
$page_num = $page;
$page_num_max = $page_num *$Data_per_page ; 
$page_num_min = $page_num_max - $Data_per_page;
for($x = $page_num_min;$x<$page_num_max;$x++){
	$BaseUserObj = new BaseUser("Account Management");
	if (array_key_exists($x,$ArrayOfUsers)){
	$BaseUserObj->setUID_Admin($ArrayOfUsers[$x]);
	echo'<tr><td>'.$BaseUserObj->getUID().'</td>
	<td>'.$BaseUserObj->getDisplayName().'</td>
	<td>'.$BaseUserObj->getFirstName().'</td>
	<td>'.$BaseUserObj->getLastName().'</td>
	<td>'.$BaseUserObj->getEmail().'</td>
	<td>'.$BaseUserObj->Rating['Rating'].'</td>
	<td>'.json_decode($BaseUserObj->getStatus())[0].'</br>'.json_decode($BaseUserObj->getStatus())[1].'</td>
	
	<td><form method="post" action="UserManagementController.php">
		<input type="submit" name="Ban" value="Ban user">
		<input type="submit" name="Suspend" value="Suspend user"></br>
		<input type="submit" name="Remove" value="Unban/Unsuspend user">
		<input type="submit" name="MakeAdmin" value="Make User Admin">
		<input type="hidden" name="hiddenval" value="'.$BaseUserObj->getUID().'">
	</form></td>
	</tr>
	';
	}
}
echo'</table>';

echo'<div class = "pagination" >';			
echo'<center><b style="bottom: 20;">Page</b></center></BR>';
echo '<a href = "UserManagementPage.php?page=1">First </a>'; 
for($page = 1; $page<=$number_of_page; $page++) { 
	if($page==1){
		echo '<a href = "UserManagementPage.php?page=' . $page . '">' . $page . ' </a>';  
		
	}
	else{
	echo '<a href = "UserManagementPage.php?page=' . $page . '">' . $page . ' </a>';  
	}
} 

echo '<a href = "UserManagementPage.php?page=' . $number_of_page . '">Last </a>';  

echo'</div>';


?>
<?php require_once("Footer.php");?> 