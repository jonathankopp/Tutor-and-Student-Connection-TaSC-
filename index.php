<?php 
	session_start();
?>

<!DOCTYPE html>

<html>
<head>
	<title>TaSC Login</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
	<link href="Resources/index.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="Scripts/index.js"></script>
</head>

<body>
	<h1> Tutor and Student Connection </h1>
	<?php

		$dbOk = false;

		//creates a connection to the database
		@ $db =  new mysqli('localhost', 'root', 'password', 'tasc');

		//output errors if connection fails
		if ($db->connect_error) {
			echo '<div class="messages">Could not connect to the database. Error: ';
			echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
		} else {
			$dbOk = true; 
		}

		//if the user has signed in, check if they signed in correctly
		$havePost = isset($_POST["sign_in"]);

		$errors = '';

		if ($havePost) {
			//makes email and password to check in database
			$email = htmlspecialchars(trim($_POST["my_email"]));
			$password = htmlspecialchars(trim($_POST["my_password"]));

			$focusId = '';

			//if a field is blank, print out an error
	    if ($email == '') {
	    	$errors .= '<li>email may not be blank</li>';
	    	if ($focusId == '') $focusId = '#new_email';
	    }
	    if ($password == '') {
	    	$errors .= '<li>password may not be blank</li>';
	    	if ($focusId == '') $focusId = '#new_password';
	    }

	    if ($errors != '') {
	      echo '<div class="messages"><h4>Please correct the following errors:</h4><ul>';
	      echo $errors;
	      echo '</ul></div>';
	      echo '<script type="text/javascript">';
	      echo '  $(document).ready(function() {';
	      echo '    $("' . $focusId . '").focus();';
	      echo '  });';
	      echo '</script>';
	    } else { //if no errors
	    	if ($dbOk) {
			  	$emaildb = trim($_POST["my_email"]);
			  	$passworddb = trim($_POST["my_password"]);
			  	$encrypt = crypt($passworddb,qgwc); //encrypts the password


			  	//queries into user database and checks whether or not
			  	//the email and password match a row in the users table
			  	$query = "SELECT userid from users where email='" . $emaildb ."' AND password='" . $encrypt ."'";
			  	$result = $db->query($query);
			  	if($result->num_rows == 0) { //if no rows, the wrong email or password was entered
			  		echo '<h3> Wrong email or password </h3>';
			  	}
			  	else { //if there is a match to a user in the table
			  		$record = $result->fetch_assoc();
			  		$_SESSION["userid"] = $record['userid']; //set session userid to the corresponding value
			  		header("Location: connect.php"); //relocates to the homepage 
			  		exit;
			  	}

	    	}
	    }
		}


	?>
	<form id="old_user" name="old_user" action="index.php" method="post" onsubmit="return validateSignIn(this);">
		<fieldset>
			<legend>Sign in</legend>
			<div class="formData">

				<label class="field">Email</label>
				<div class="value"><input type="text" size="60" value="" name="my_email" id="my_email"/></div>

				<label class="field">Password</label>
				<div class="value"><input type="password" size="60" value="" name="my_password" id="my_password"/></div>

				<input type="submit" value="Sign In" id="sign_in" name="sign_in"/>
			</div>
		</fieldset>
	</form>
	<button type = "button">Forgot Email/Password?</button>
	<br>
	<a href="signup.php"> Don't have an account? Sign up here! </a>
</body>






</html>