<?php require_once("NavBar.php");
if(empty($_GET['query'])){
        echo "<h3> Enter a search query </h3>";
    }
else{
 $_SESSION['Searchquery'] = $_GET['query'];

echo "<h1>Search results for :". $_SESSION['Searchquery']."</h1>";


?> 

<style>


.card {
font-family: 'Roboto';font-size: 22px;
border: 2px solid purple;
border-radius: 25px;
font-size:5px;
overflow:hidden;
background-color:white;
margin: auto;
text-align: center;
float:left;
display: inline-block;
margin-left:50px;
margin-top:50px;
height:320px;
width:300px;
margin-bottom:50px;

}
.card:hover {
box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 1);
}



.card image{
position: absolute;
top:    0;
bottom:   0;	

}
.card:hover {
cursor:pointer;
transform: scale(1.5); 
z-index:1;
}

.text {

background-color: white;
color:purple;
font-size: 16px;
width:80%;
margin:auto;
}
.sorter{
font-family: 'Roboto';font-size: 22px;
background-color:white ;
font-size:20px;
margin-top:2%;
display:block;
width:100%;
opacity:0.9;
color:white;
float:right;
}
.sorter input{
border: 2px solid white;
background-color:purple;
color:white;
border-radius:5px;
}
.sorter input:hover{

transform: scale(1.1); 
outline:60%;
filter: drop-shadow(0 0 6px white);
}
#container{

width:1880px;
position:relative;
left:1%;

}
#sortcontent{
opacity:1;
margin-left:80px;
width:400px;
height:180px;
color:purple;
text-align:left;
background-color:white;

}
</style>
<div class="sorter">
<div id="sortcontent">
<form method="post" style="">
<Label style="margin-right:40px;font-size:30px;"><b>Sort</b></Label></br>
<input type="radio" id="ASC" name="Order" checked = "true" value="ASC">&nbsp;&nbsp;&nbsp;&nbsp;<label for="ASC">Ascending</label><br>
<input type="radio" id="DESC" name="Order" value="DESC">&nbsp;&nbsp;&nbsp;&nbsp;<label for="DESC">Descending</label><br>
<input type="submit" name="SortCat" value="Category">
<input type="submit" name="SortPrice" value="Price">
<input type="submit" name="SortDate" value="Date">
</form>
</div>
</div>
<hr>
<?php
if (!isset ($_GET['page']) ) {  
	$page = 1;  
} else {  
	$page = $_GET['page'];  
}
if (!isset ($_GET['Ord']) ) {  
	$Order = "DESC";  
} else {  
	$Order =$_GET['Ord'];  
}
if (!isset ($_GET['Sb']) ) {  
	$Sortby = "DateOfListing";	
} else {  
	$Sortby = $_GET['Sb'];
}
if(isset($_SESSION['Object'])){
	$_SESSION['Object']->AddUserTags($_SESSION['Searchquery']);
}
$BaseUserOBJ = new BaseUser("Search page");	

if(isset($_POST['SortDate'])){

$BaseUserOBJ->ViewSearchProduct("DateOfListing",$_POST['Order'], $_SESSION['Searchquery'],1);
}
if(isset($_POST['SortPrice'])){
	
$BaseUserOBJ->ViewSearchProduct("ProductInitialPrice",$_POST['Order'], $_SESSION['Searchquery'],1);
}
if(isset($_POST['SortCat'])){

$BaseUserOBJ->ViewSearchProduct("ProductInitialPrice",$_POST['Order'], $_SESSION['Searchquery'],1);
}


if(!isset($_POST['SortCat'])&&!isset($_POST['SortPrice'])&&!isset($_POST['SortDate'])){
$BaseUserOBJ->ViewSearchProduct($Sortby,$Order, $_SESSION['Searchquery'],$page);		
}


}
?>
</div>
<?php require_once("Footer.php");?> 