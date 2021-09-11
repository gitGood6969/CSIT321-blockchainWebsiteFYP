<?php require_once("NavBar.php");
$BaseUserOBJ = new BaseUser("index page");	
?> 
<style>

.Carousel{
	
	width:900px;
	height:600px;
	background-color:black;
	margin:auto;
	margin-bottom:100px;


 
}


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
position:relative;
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
.card2 {
font-family: 'Roboto';font-size: 22px;
border: 2px solid purple;
border-radius: 25px;
font-size:5px;
overflow:hidden;
background-color:white;
margin: auto;
text-align: center;
display: inline-block;
margin-left:80px;
margin-right:80px;
margin-top:50px;
position:relative;
height:320px;
width:300px;
margin-bottom:50px;
}
.card:hover {
  box-shadow: 0 4px 10px 0 rgba(0, 0, 0, 1);
}



.card2 image{
position: absolute;
top:    0;
bottom:   0;	
	
}
.card2:hover {
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
.Rec_Prod_GUI{
	margin-top:2%;
	margin:auto;
	width:1900px;
	border-radius:20px;
	background-color:#23084D ;
	overflow:cover;
	justify-content: center;
	align-items: center;
	color:white;
	height:400px;
	background-repeat: no-repeat; /* Do not repeat the image */
	background-size: cover; /* Resize the background image to cover the entire container */
	background-image: url('systemimages/ReccomendedWP.jpg');
	background-attachment: fixed;
	

}	


.sorter{

	font-family: 'roboto';font-size: 22px;
	 background-color:white ;
	font-size:20px;
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

	border-radius:20px;
	width:1450px;
	border:1px solid purple;
	height:1700px;
	margin:auto;
	margin-top:5%;
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
.adsimage{

transition: 0.3s;
object-fit: contain;
width:900px;
height:600px;
border:1px solid black;
background-repeat: no-repeat; /* Do not repeat the image */
background-size: cover; /* Resize the background image to cover the entire container */
background-image: url('systemimages/adsbackground.jpg');
background-attachment: fixed;
opacity:0.9;
filter: drop-shadow(0 0 5px black);

}
.adsimage :not(img){

}

@media only screen and (max-device-width: 480px) {
	.Rec_Prod_GUI{
		display:none;
	}
}
</style>

<?php 
$ArrayOFAds = $BaseUserOBJ ->ListOfAds();
if(sizeof($ArrayOFAds )!=0){
?>

<div class="Carousel">

<div id="carousel-example-2" class="carousel slide carousel-fade" data-ride="carousel">
  <!--Indicators-->
  <ol class="carousel-indicators">
	   <li data-target="#carousel-example-2" data-slide-to="0" class="active"></li>
  <?php for($x = 1; $x<sizeof($ArrayOFAds );$x++){?>
 
    <li data-target="#carousel-example-2" data-slide-to="1"></li>
  <?php } ?>
  </ol>
   <div class="carousel-inner" role="listbox">
   <div class="carousel-item active">
      <div class="view">
        <img class="adsimage"  src="<?php echo $ArrayOFAds[0][0];?>"
         >
        <div class="mask rgba-black-light" style="background-color:black"></div>
      </div>
    </div>
<?php
for($x = 1;$x<sizeof($ArrayOFAds);$x++){
?>
 
    <div class="carousel-item">
      <div class="view">
        <img class="adsimage" src="<?php echo $ArrayOFAds[$x][0];?>"
          >
        <div class="mask rgba-black-light"></div>
      </div>
    </div>

<?php
}
?>
  </div>
  <a class="carousel-control-prev" href="#carousel-example-2" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carousel-example-2" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>
 </div>
</div>

<script>
					function clickproduct(ID){
						location.replace("ProductPage.php?ID="+ID);
					}
</script>


<hr style="border:3px solid purple">

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
<center><Label style="font-size:50px;"><b>Products</b></Label></center>
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
if (!isset ($_GET['Cat']) ) {  
	$Category = "All";	
} else {  
	$Category = "All";	
}


if(isset($_POST['SortDate'])){

$BaseUserOBJ->ViewAllProduct("DateOfListing",$_POST['Order'],"All",1,"index.php");
}
if(isset($_POST['SortPrice'])){
	
$BaseUserOBJ->ViewAllProduct("ProductInitialPrice",$_POST['Order'],"All",1,"index.php");
}
if(isset($_POST['SortCat'])){
	
$BaseUserOBJ->ViewAllProduct("ProductCategory",$_POST['Order'],"All",1,"index.php");
}


if(!isset($_POST['SortCat'])&&!isset($_POST['SortPrice'])&&!isset($_POST['SortDate'])){
$BaseUserOBJ->ViewAllProduct($Sortby,$Order,$Category,$page,"index.php");	
}

   
?>

	
<?php
}

if(isset($_SESSION['ID'])){
	echo"<center><h1 style='margin-top:30px'>Specially picked for you!</h1></center>";
	echo"<div class='Rec_Prod_GUI'>
	";
	$ArrayOfRecProducts = $_SESSION['Object']->UserProductBehaviourAnalysis();
	foreach($ArrayOfRecProducts as $val){
		$ProductObj = new Products();
		$ProductObj->InitialiseProduct($val);
		echo'

			<div class="card2"  onclick="clickproduct(this.id)" id = "'.$val.'">
			<img src="'.$ProductObj->Image.'" class="image" style="object-fit: cover;width:200px;height:200px;border-radius:20px;margin-top:20px">
			<div class="text" style="font-size:10px"><b>'.$ProductObj->ProductCategory.'</b></div>
			<div class="text" style="font-size:15px; white-space: nowrap;text-overflow:ellipsis;overflow:hidden;"><b>'.$ProductObj->ProductName.'</b></div>
			<div class="text">Date Listed:<i>'.$ProductObj->DateOfListing.'</i></div>
			<div class="text" style="font-size:20px;"><b>'.$_SESSION['Object']->getCurrency().number_format($ProductObj->ProductInitialPrice, 2, '.', '').'</b></div>
		
			</div>';
		
		
	}
	echo'</div>';
}
?>
<?php require_once("Footer.php");?> 