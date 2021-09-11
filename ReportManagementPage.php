<?php require_once("NavBar.php");?>
<style>

.ReportedProduct_GUI{
	display:none;
}
.ReportedContract_GUI{
	display:none;
}
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
#AllReports{
	
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
<script>
$(document).ready(function(){
  $(".UserList").click(function(){
    $(".ReportedUserGUI").show();
	$(".ReportedProduct_GUI").hide();
	$(".ReportedContract_GUI").hide();
  });
    $(".ProductList").click(function(){
    $(".ReportedUserGUI").hide();
	$(".ReportedProduct_GUI").show();
	$(".ReportedContract_GUI").hide();
  });
    $(".ContractList").click(function(){
    $(".ReportedUserGUI").hide();
	$(".ReportedProduct_GUI").hide();
	$(".ReportedContract_GUI").show();
  });

});
</script>
<button class="UserList">Reported Users</button>
<button class="ProductList">Reported Products</button>
<button class="ContractList">Reported Contracts</button>

<?php
if (isset ($_GET['page3']) ) {  

?>
<script>
$(document).ready(function(){
    $(".ReportedUserGUI").hide();
	$(".ReportedProduct_GUI").hide();
	$(".ReportedContract_GUI").show();
});
</script>
<?php
} 

if (isset ($_GET['page2']) ) {  

?>
<script>
$(document).ready(function(){
 $(".ReportedUserGUI").hide();
	$(".ReportedProduct_GUI").show();
	$(".ReportedContract_GUI").hide();
});
</script>
<?php
} 
if (isset ($_GET['page']) ) {  

?>
<script>
$(document).ready(function(){
    $(".ReportedUserGUI").show();
	$(".ReportedProduct_GUI").hide();
	$(".ReportedContract_GUI").hide();
});
</script>
<?php
} 
if(!isset($_SESSION['ID'])){
	echo '<script> location.replace("index.php")</script> ';
}
if($_SESSION['Object']->getAccountType()!="Administrator"){
	echo '<script> location.replace("index.php")</script> ';
}
echo'<div class="ReportedUserGUI">';
$ArrayOfUsers = $_SESSION['Object']->ListOfReportedUsers();
echo'<h1>Reported Users</h1>';
if(!empty($ArrayOfUsers)){ 

echo'<hr><table id="AllReports" >';
echo'<tr>
<th>UserID</th>
<th>NumberOfReports</th>
<th>Action</th></tr>
';
if (!isset ($_GET['page']) ) {  
	$page = 1;  
} else {  
	$page = $_GET['page'];  
}
$Data_per_page = 5;
$number_of_page = ceil(sizeof($ArrayOfUsers)/$Data_per_page) ;
if(round($number_of_page) == 0){
	
	$number_of_page = 1;
}
$page_num = $page;
$page_num_max = $page_num *$Data_per_page ; 
$page_num_min = $page_num_max - $Data_per_page;
for($x = $page_num_min;$x<$page_num_max;$x++){
	$BaseUserObj = new BaseUser("Reported");
	if (array_key_exists($x,$ArrayOfUsers)){
	$BaseUserObj->setUID_Admin($ArrayOfUsers[$x]);
	echo'<tr><td>'.$BaseUserObj->getUID().'</td>
		<td>'.$BaseUserObj->Reported.'</td>
		<td><form method="post" action="ProfilePage.php?ID='.$BaseUserObj->getUID().'"><input type="submit" value="Go to profile"></form></td>
	</tr>';
	}
}
echo'</table>';

echo'<div class = "pagination" >';			
echo'<center><b style="bottom: 20;">Page</b></center></BR>';
echo '<a href = "ReportManagementPage.php?page=1">First </a>'; 
for($page = 1; $page<=$number_of_page; $page++) { 
	if($page==1){
		echo '<a href = "ReportManagementPage.php?page=' . $page . '">' . $page . ' </a>';  
		
	}
	else{
	echo '<a href = "ReportManagementPage.php?page=' . $page . '">' . $page . ' </a>';  
	}
} 

echo '<a href = "ReportManagementPage.php?page=' . $number_of_page . '">Last </a>';  

echo'</div>';
}
else{
echo '<b>No reported users</b>'; 
	
}
echo'</div><div class="ReportedProduct_GUI">';
$ArrayOfProducts = $_SESSION['Object']->ListOfReportedProducts();
echo'<h1>Reported Products</h1>';
if(!empty($ArrayOfProducts)){ 

echo'<hr><table id="AllReports" >';
echo'<tr>
<th>ProductID</th>
<th>Seller</th>
<th>NumberOfReports</th>
<th>Status</th>
<th>Action</th>
';
if (!isset ($_GET['page2']) ) {  
	$page2 = 1;  
} else {  
	$page2 = $_GET['page2'];  
}
$Data_per_page = 5;
$number_of_page2 = ceil(sizeof($ArrayOfProducts)/$Data_per_page) ;
if(round($number_of_page2) == 0){
	
	$number_of_page2 = 1;
}
$page_num = $page2;
$page_num_max = $page_num *$Data_per_page ; 
$page_num_min = $page_num_max - $Data_per_page;
for($x = $page_num_min;$x<$page_num_max;$x++){
	$ProductObj = new Products();
	if (array_key_exists($x,$ArrayOfProducts)){
	$ProductObj->InitialiseProduct($ArrayOfProducts[$x]);
	echo'<tr><td>'.$ProductObj->ProductID.'</td>
		<td>'.$ProductObj->SellerUserID.'</td>
		<td>'.$ProductObj->Reported.'</td>
		<td>'.$ProductObj->Status.'</td>
		<td><form  method="post" action="ProductPage.php?ID='.$ProductObj->ProductID.'"><input type="submit" value="Go to page"></form></td>
	</tr>';
	}
}
echo'</table>';

echo'<div class = "pagination" >';			
echo'<center><b style="bottom: 20;">Page</b></center></BR>';
echo '<a href = "ReportManagementPage.php?page2=1">First </a>'; 
for($page2 = 1; $page2<=$number_of_page2; $page2++) { 
	if($page2==1){
		echo '<a href = "ReportManagementPage.php?page2=' . $page2 . '">' . $page2 . ' </a>';  
		
	}
	else{
	echo '<a href = "ReportManagementPage.php?page2=' . $page2 . '">' . $page2 . ' </a>';  
	}
} 

echo '<a href = "ReportManagementPage.php?page2=' . $number_of_page2 . '">Last </a>';  

echo'</div>';
}
else{
echo '<b>No reported products</b>'; 
	
}



echo'</div><div class="ReportedContract_GUI">';
$ArrayOfUsers = $_SESSION['Object']->ListOfReportedContracts();
echo'<h1>Reported Contracts</h1>';
if(!empty($ArrayOfUsers)){ 

echo'<hr><table id="AllReports" >';
echo'<tr>
<th>ContractID</th>
<th>Status</th>
<th>NumberOfReports</th>
<th>Action</th>
';
if (!isset ($_GET['page3']) ) {  
	$page3 = 1;  
} else {  
	$page3 = $_GET['page3'];  
}
$Data_per_page = 5;
$number_of_page3 = ceil(sizeof($ArrayOfUsers)/$Data_per_page) ;
if(round($number_of_page3) == 0){
	
	$number_of_page3 = 1;
}
$page_num = $page3;
$page_num_max = $page_num *$Data_per_page ; 
$page_num_min = $page_num_max - $Data_per_page;
for($x = $page_num_min;$x<$page_num_max;$x++){
	$ContractsObj = new Contracts();
	$ProductObj = new Products();
	if (array_key_exists($x,$ArrayOfUsers)){
	$ContractsObj->InitialiseContract($ArrayOfUsers[$x]);
	echo'<tr><td>'.$ContractsObj->ContractID.'</td>
		<td>'.$ContractsObj->Status.'</td>
		<td>'.$ContractsObj->Reported.'</td>
		<td><form  method="post" action="ContractPage.php?ID='.$ContractsObj->ContractID.'"><input type="submit" value="Go to page"></form></td>
		<td><form  method="post"><input type="hidden" name="HaltContractID" value="'.$ContractsObj->ContractID.'"><input type="submit" name="Halt" value="Halt Transaction"></form></td>
	</tr>';
	}
}
echo'</table>';

echo'<div class = "pagination" >';			
echo'<center><b style="bottom: 20;">Page</b></center></BR>';
echo '<a href = "ReportManagementPage.php?page3=1">First </a>'; 
for($page3 = 1; $page3<=$number_of_page3; $page3++) { 
	if($page3==1){
		echo '<a href = "ReportManagementPage.php?page3=' . $page3 . '">' . $page3 . ' </a>';  
		
	}
	else{
	echo '<a href = "ReportManagementPage.php?page3=' . $page3 . '">' . $page3 . ' </a>';  
	}
} 

echo '<a href = "ReportManagementPage.php?page3=' . $number_of_page3 . '">Last </a>';  

echo'</div>';
}
else{
echo '<b>No reported contracts</b>';  
	
}
echo'</div>';
if(isset($_POST['Halt'])){
	$_SESSION['Object']->HaltTransaction($_POST['HaltContractID']);
	echo '<script> location.replace("ContractPage.php?ID='.$_POST['HaltContractID'].'")</script> ';
	
}

require_once("Footer.php");?>