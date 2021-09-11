
<html lang="en">
<head>
<style>
#innercontainer1{
float:left;
width:50%;
height:100%;
margin-bottom:20%;
}

#logoimage{
width:200px;
height:200px;
margin:auto;
}

#innercontainer2 {
float:right;
margin: auto;
width:30%;
margin-top:10%;
margin-right:20%;
height:70%;

border-radius:20px;
padding: 10px;
}

#container{
margin-left:10%;
}

#innercontainer2	 input[type=submit]{
display:inline-block;
border:none;
font-family: 'Roboto';
background-color:purple;
color:white;
height:30px;
font-size:20px;
width:100px;
margin-top:10px;
border-radius:20px;
margin-right:10px;
margin-left:5%;
float:left;
}

#innercontainer2 input[type=submit]:hover {
outline:60%;
filter: drop-shadow(0 0 5px purple);
border-radius:20px;
}

#googleMap{
width:80%;
height:50%;
margin-top:5%;
}

</style>
		<?php
			// Include the navigation bar
			require_once("NavBar.php"); 
	
			// Define Variables
			$header = $recipient = $subject = $user_email = $user_message = $user_name = $message = "";
			
			// strip special characters
			function clean_input($data)
			{
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
				return $data;
			}
		
			// If Submit button is clicked (Event Listener)
			if(isset($_POST['submit']))
			{
				echo $_POST["Subject"];
				$header       = "From: test@email.com";
				$recipient    = "fyp21s230@gmail.com";
				$subject      = $_POST["Subject"];
				$user_email   = clean_input($_POST["email"]);
				$user_message = clean_input($_POST["message"]); // Gets the "Name" of the field
				$user_name    = clean_input($_POST["name"]);
				
				$message = "Feedback is from: ".$user_name.
				           "\nEmail: ".$user_email.
						   "\n\nMessage: \n".$user_message; 
				
				// Using try-catch block to catch for errors
				try 
				{
					mail($recipient, $subject, $message, $header);
					echo "<script type='text/javascript'>alert('Feedback sent!');</script>";
				}
				// catch exception
				catch (Exception $e)
				{
					echo 'Message: ' . $e->getMessage();
				}
			}
			
		?>	
	</head>
	  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<body>
	
	<div id="container">

	<div id="innercontainer1">
		<img id="logoimage" src="systemimages/Logo.jpg"  ></br></br>
		<h2>Locate Us</h2>
		<div id="googleMap" ></div>

<script>
let map;

function initMap() {
  map = new google.maps.Map(document.getElementById("googleMap"), {
    center: { lat: 1.3302378239567225, lng: 103.77572388869598 },
    zoom: 15,
  }); 
   const marker = new google.maps.Marker({
    position: { lat: 1.3302378239567225, lng: 103.77572388869598 },
    map: map,
  });
}

</script>
<script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAHRwmnI-pKgfrGONILfgZZtbp4a6631FQ&callback=initMap">
</script></br></br>
<h2>Contact Us</h2>
<p>Email:fyp21s230@gmail.com</p>
<p>Phone:+65 9222 2222</p>
</div>

		<div id="innercontainer2">
			<form method="POST" name="EmailForm">
				<h2>Feedback Form</h2>
				<label for="name">Name:</label><br> 
				<input id="name" type="text" name="name" size="20"  placeholder="JohnDoeAnderson" autocomplete="off" required><br><br>
				
				<label for="email">Enter your email:</label><br>
				<input type="email" id="email" name="email" placeholder="abc@gmail.com" autocomplete="off" required><br><br> 
				
				<select style="background-color:purple;color:white;" id="Subject" name="Subject">
				<option style='background-color:purple;color:white;' value="Advertising">Advertising</option>
				<option style='background-color:purple;color:white;' value="General Feedback">General Feedback</option>
				<option style='background-color:purple;color:white;' value="Career with us">Career with us</option>
				<option style='background-color:purple;color:white;' value="Report malicious activity	">Report malicious activity</option>
			    </select><br /><br>
				  
				<label for="message">Message:</label><br>
				<!--Careful for textarea, as closing tag needs to be on same line if not there will be some whitespace -->
				<textarea id="message" name="message" rows="6" cols="30" placeholder="Type Message Here..." autocomplete="off" required></textarea><br><br> 
				<input type="submit" value="Feedback" name="submit">
			</form>
		</div>
		<!--Include the footer -->
		<?php require_once 'Footer.php';?>
		
		<!--Prevent form resubmission -->
		<script>
			if ( window.history.replaceState ) 
			{
				window.history.replaceState( null, null, window.location.href );
			}
		</script>
	</div>
	
	</body>
</html>
