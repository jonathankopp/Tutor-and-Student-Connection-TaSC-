<?php 
	session_start();
?>

<!DOCTYPE html>

<html>
<head>
	<title>TaSC Login</title>
	<link href="Resources/index.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="Scripts/index.js"></script>
</head>

<body>
	<h1> Tutor and Student Connection </h1>
	<?php
		$dbOk = false;


		@ $db =  new msysqli('localhost', 'root', 'password', 'tasc');

		if ($db->connect_error) {
			echo '<div class="messages">Could not connect to the database. Error: ';
			echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
		} else {
			$dbOk = true; 
		}

		$havePost = isset($_POST["sign_in"]);

		$errors = '';

		if ($havePost) {
			$email = htmlspecialchars(trim($_POST["my_email"]));
			$password = htmlspecialchars(trim($_POST["my_password"]));

			$focusId = '';

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
	    } else {
	    	if ($dbOk) {
			  	$emaildb = trim($_POST["my_email"]);
			  	$passworddb = trim($_POST["my_password"]);

			  	$query = "SELECT " . $emaildb . " from users WHERE password=" . $passworddb;
			  	$result = $db->query($query);
			  	$record = $result->fetch_assoc();
			  	if(is_null($record)) {
			  		echo '<h3> Wrong email or password';
			  	}
			  	else {
			  		$_SESSION["userid"] = $record['userid'];
			  		header("Location: connect.php");
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

				<input type="submit" value="I'm Ready!" id="sign_in" name="sign_in"/>
			</div>
		</fieldset>
	</form>
	<button type = "button">Forgot Email/Password?</button>
	<br>
	<a href="signup.php"> Don't have an account? Sign up here! </a>
</body>






</html>