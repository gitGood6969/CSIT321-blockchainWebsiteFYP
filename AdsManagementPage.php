<?php 
	require_once("NavBar.php");
	
	checkIfSessionIdIsSet();
	checkIfUserIsAnAdministrator();
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
span {
color:red;
}

.AddGUI {
display:none;
width:1000px;
height:1000px;
text-align:center;
margin:auto;
border:1px solid black;
border-radius:20px;
box-shadow:5px 5px gray;
}

.AddGUI input[type="submit"] {
margin-right:5%;
float:right;	
}

.AddGUI input[type="file"] {
margin-top:3%;
text-align:center;
}

table, tr, th, td {
text-align:center;
border:1px solid #e8e6e6; 
}

tr {
height:50px;
vertical-align: text-bottom;
}

button, input[type=submit], input[type=button] {
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

#adsgui {
width:100%;
margin:auto;
}

table {
width:80%;
margin-left: auto;
margin-right: auto;
}

th {
width:200px;
}

#output {
margin-top:5%;
width:900px;
height:600px;
border:none;
}

h1 {
text-align: center;
}

.AddButton {
margin-left: auto;
margin-right: auto;
}
</style>	
<title>Ads Management Page</title>
</head>
	<body>
		<h1>Advertisment Management</h1>
		
		<hr>
		
		<div id="adsgui">
			<table>
				<tr>
					<th>Ads Preview</th>
					<th>No.</th>
					<th>User</th>
					<th>Date</th>
					<th>Action</th>
				</tr>
				
				<?php 
					$listOfAdvertisements = getListOfAdvertisements();
					
					display($listOfAdvertisements);
					displayAddAdvertisementsIfListIsEmpty($listOfAdvertisements);
					
					// Event Listeners
					removeAdvertisingRecordIfRemoveButtonIsSelected();
					$systemMessage = "";
					try {
						checkIfAddSubmitButtonIsClicked();
					} catch (Exception $e) {
						$systemMessage = $e->getMessage() . "\n";
					}
					
					displayTheAddButton();
				?>	
			</table>
		</div>
		
		<script>
			$(document).ready(function() {
				$(".AddButton").click(function() {
					$(".AddGUI").show();
					$(".AddButton").hide();
				});
			});
		</script>
		
		<br>
		<br>
		
		<button class="AddButton">Add</button>
		
		<div class="AddGUI">
			
			<h1>Preview</h1>
			
			<form method="post" enctype="multipart/form-data">
				<img id="output">
				
				<br>
				
				<label for="file">Image:</label>
				<input type="file" name="file" id="file" accept="image/*" value="default" onchange="loadFile(event)" required>
				<script>
					var loadFile = function(event) {
						var output = document.getElementById('output');
						output.src = URL.createObjectURL(event.target.files[0]);
						output.onload = function() {
							URL.revokeObjectURL(output.src) // free memory
						}
					};
				</script>
				
				<br>
				<br>
				
				<label for="UserIDInput">UserID:</label>
				<input type="text" name="UserIDInput" id="UserIDInput" required>
				
				<br>
				<br>
				
				<input type="submit" name="AddSubmit" value="Add">
				
				<span class="error"><?php echo $systemMessage ?></span>
			</form>
		</div>
		<?php 
			require_once("Footer.php");
		?>
	</body>
</html>

<?php 
	function checkIfSessionIdIsSet() {
		if(!isset($_SESSION['ID'])) {
			echo '<script> location.replace("index.php")</script> ';
		}
	}
	
	function checkIfUserIsAnAdministrator() {
		if($_SESSION['Object']->getAccountType()!="Administrator") {
			echo '<script> location.replace("index.php")</script> ';
		}
	}
	
	function getListOfAdvertisements() {
		return $_SESSION['Object']->ListOfAds();
	}
	
	function display($listOfAdvertisements) {
		for($x=0; $x<sizeof($listOfAdvertisements); $x++) {
			
			$imageLocation = $listOfAdvertisements[$x][0];
			$recordNumber = $x;
			$userID = $listOfAdvertisements[$x][1];
			$date = $listOfAdvertisements[$x][2];
			
			echo'<tr>
					<td>
						<img src="'.$imageLocation.'" width:"400px" height="400px" >
					</td>
					<td>
						'.$recordNumber.'
					</td>
					<td>
						'.$userID.'
					</td>	
					<td>
						'.$date.'
					</td>
					<td>
						<form method="post">
							<input type="submit" name="Remove" value="Remove">
							<input type="hidden" name="ImageID" value="'.$imageLocation.'">
						</form>
					</td>
					
					</tr>
			';
		}
	}
	
	function displayAddAdvertisementsIfListIsEmpty($listOfAdvertisements) {
		if(sizeof($listOfAdvertisements)==0) {
			echo'<td colspan="5">
					Add advertisments
				</td>';	
		}
	}
	
	function removeAdvertisingRecordIfRemoveButtonIsSelected() {
		if(isset($_POST['Remove'])) {
			$_SESSION['Object']->RemoveAds($_POST['ImageID']);
			
			refreshWebPage();
		}
	}
	
	function refreshWebPage() {
		echo '<script> location.replace("AdsManagementPage.php")</script> ';
		exit();
	}
	
	function checkIfAddSubmitButtonIsClicked() {
		if(isset($_POST['AddSubmit'])) {
			processForm();
		} 
	}
	
	function processForm() {
		
		$success = true;
		$fail = false;
		
		if(fileValidation() == $success) {
			storeFileAndAddRecordToDatabase();
		} else {
			throw new Exception("File Validation Failed! Please Try Again!");
		}
	}
	
	function fileValidation() {
		
		$yes = true;
		$no = false;
		
		$outcomeOfFileValidation = false;
		
		if (fileUploadProperly() == $yes) {
			if (fileIsEmpty() == $yes) {
				if (fileExtensionMatches() == $yes) {
					if (fileSizeIsWithinRange() == $yes) {
						$outcomeOfFileValidation = true;
					} else {
						throw new Exception("The file is too big! Max file size is 500kB");
					}
				} else {
					throw new Exception("File Extension is Invalid! Only jpg, jpeg, png and pdf are allowed.");
				}
			} else {
				throw new Exception("File is Empty!");
			}	
		} else {	
			throw new Exception("There was an error uploading the file! Please try again!");
		}
		return $outcomeOfFileValidation;
	}

	function fileUploadProperly() {
		$fileUploadStatus = $_FILES["file"]["error"];
		
		$successful = 0;
		$fail = 1;
		
		if($fileUploadStatus == $successful) {
			return true;
		} else {
			return false;
		}
	}
	
	function fileIsEmpty() {
		return empty($_POST["file"]);
	}
	
	function fileExtensionMatches() {
		$file = $_FILES['file']['name'];
		$fileExt = explode('.',$file);
		$fileActualExt = strtolower(end($fileExt));
		
		$allowedFileExtensions = array("jpg", "jpeg", "png", "pdf");
		
		if(in_array($fileActualExt, $allowedFileExtensions)) {
			return true;
		} else {
			return false;
		}
	}
	
	function fileSizeIsWithinRange() {
		$fileSize = $_FILES['file']['size'];
		
		// file size less then 500kB (Kilobytes)
		if($fileSize > 0 and $fileSize < 500000) {	
			return true;
		} else {
			return false;
		}
	}
	
	function storeFileAndAddRecordToDatabase() {
		
		$originalNameOfFile = $_FILES['file']['name'];
		$filePath = explode('.',$originalNameOfFile);
		$fileExtension = strtolower(end($filePath));
		$newFileName = uniqid('', true).".".$fileExtension;
		
		$fileDestination = 'systemads/'.$newFileName;
		$fileTmpNameOnServer = $_FILES['file']['tmp_name'];
		
		$success = true;
		$fail = false;
		
		if (storeFile($fileTmpNameOnServer, $fileDestination) == $success) {
			addRecordToDatabase($fileDestination);
			refreshWebPage(); 
		} else {
			throw new Exception("Unable to Store File! Please try again!");
		}
	}
	
	function storeFile($fileTmpNameOnServer, $fileDestination) {
		return move_uploaded_file($fileTmpNameOnServer, $fileDestination);
	}
	
	function addRecordToDatabase($fileDestination) {
	
		$_SESSION['Object'] ->AddAds($fileDestination ,$_POST["UserIDInput"]);
	
	}
	
	function displayTheAddButton() {
		echo"<style>.AddButton{display:block}</style>";
	}
?>

