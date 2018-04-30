<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/> 
  <script type="text/javascript" src="resources/jquery-1.4.3.min.js"></script>
  <link href="Resources/index.css" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="Scripts/index.js"></script>
  <title> TaSC Login </title>
</head>
<body>
	<h1>
		<div id="header"> Tutor and Student Connection 
		</div>
	</h1>
	<?php
		$dbOk = false;

		//connects to the database
		@ $db =  new mysqli('localhost', 'root', 'password', 'tasc');

		//error if connection fails
		if ($db->connect_error) {
	    echo '<div class="messages">Could not connect to the database. Error: ';
	    echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
	  } else {
	    $dbOk = true; 
	  }

	  //if the user entered the info already
	  $havePost = isset($_POST["save"]);

	  $errors = '';


	  if ($havePost) {
	  	//takes in all the input fields 
	  	$firstNames = htmlspecialchars(trim($_POST["firstNames"]));  
 		  $lastName = htmlspecialchars(trim($_POST["lastName"]));
	  	$password = htmlspecialchars(trim($_POST["new_password"]));
	  	$email = htmlspecialchars(trim($_POST["new_email"]));
	  	$year = htmlspecialchars(trim($_POST["year"]));
	  	$subjects = htmlspecialchars(trim($_POST["subject"]));
	  	$description = htmlspecialchars(trim($_POST["description"]));
	  	$tutor = 0;
	  	if (isset($_POST["tutor"])) {
	  		$tutor = 1;
	  	}

	  	$focusId = '';

	  	//if any of the input fields are empty, they must be filled out
			if ($firstNames == '') {
	      $errors .= '<li>First name may not be blank</li>';
	      if ($focusId == '') $focusId = '#firstNames';
	    }
	    if ($lastName == '') {
	      $errors .= '<li>Last name may not be blank</li>';
	      if ($focusId == '') $focusId = '#lastName';
	    }
	    if ($password == '') {
	    	$errors .= '<li>password may not be blank</li>';
	    	if ($focusId == '') $focusId = '#new_password';
	    }
	    if ($email == '') {
	    	$errors .= '<li>email may not be blank</li>';
	    	if ($focusId == '') $focusId = '#new_email';
	    }
	    if ($year == '') {
	    	$errors .= '<li>year may not be blank</li>';
	    	if ($focusId == '') $focusId = '#year';
	    }
	    if ($subjects == '') {
	    	$errors .= '<li>Subject may not be blank</li>';
	    	if ($focusId == '') $focusId = '#subject';
	    } 
	    if ($description == '') {
	    	$errors .= '<li>Description may not be blank</li>';
	    	if ($focusId == '') $focusId = '#userName';
	    }
	    if ($tutor == 0 and !isset($_POST["student"])) {
	    	$errors .= '<li>You must be a tutor or a student</li>';
	    }
	    if ($errors != '') { //prints out any errors
	      echo '<div class="messages"><h4>Please correct the following errors:</h4><ul>';
	      echo $errors;
	      echo '</ul></div>';
	      echo '<script type="text/javascript">';
	      echo '  $(document).ready(function() {';
	      echo '    $("' . $focusId . '").focus();';
	      echo '  });';
	      echo '</script>';
	    } else { //no errors
	    	if ($dbOk) { //if connected to database
	    		//trims all input to be inserted into users table
	    		$firstNamesdb = trim($_POST["firstNames"]);  
	   		  $lastNamedb = trim($_POST["lastName"]);
			  	$passworddb = trim($_POST["new_password"]);
			  	$encrypt = crypt($passworddb,"qgwc"); //encrypts the password
			  	$emaildb = trim($_POST["new_email"]);
			  	$yeardb = trim($_POST["year"]);
			  	$subjectsdb = trim($_POST["subject"]);
			  	$descriptiondb = trim($_POST["description"]);

			  	//inserts new user into the users table
			  	$insQuery = ("INSERT into users (`first_names`, `last_name`, `year`, `email`, `password`, 
			  	`description`, `tutor`) VALUES (?,?,?,?,?,?,?)");
			  	$statement = $db->prepare($insQuery);
			  	$statement->bind_param("ssssssi",$firstNamesdb,$lastNamedb,$yeardb,$emaildb,$encrypt,$descriptiondb,$tutor);
			  	$statement->execute();		        
	        // close the prepared statement obj 
	        $statement->close();

	        //gets the userid from the newly created row in the users table
	        $query = "SELECT userid from users where email='" . $emaildb ."' and password='" . $passworddb ."'";
	       	$result = $db->query($query);
	       	$record = $result->fetch_assoc();

	       	//splits the subjects by comma, so user can join multiple subjects
	        $subjectlist = explode(",", $subjectsdb);

	        //inserts each subject into user_subjects table 
	        for ($i=0; $i < count($subjectlist); $i++) {

		       	$ins2 = "INSERT into user_subjects (`userid`, `course`) VALUES (?,?)";
		       	$state2 = $db->prepare($ins2);
		       	$state2->bind_param("ss",$record["userid"],trim($subjectlist[$i]));
		       	$state2->execute();
		       	$state2->close();

		      }

		      //relocates to login page once their account is created
		      //Note: they will need to login with their new information
	        header("Location: index.php");
			  	exit;
	    	}
	    }
	  }
	?>
	<form id="new_user" name="new_user" action="signup.php" method="post" onsubmit="return validateSignUp(this);">
    <fieldset> 
      <legend>No Account? No Problem!</legend>
      <div class="formData">
              
        <label class="field">First Names</label>
        <div class="value">
            <input type="text" size="60" value="" name="firstNames" id="firstNames"/>
        </div>

        <label class="field">Last Name</label>
        <div class="value">
            <input type="text" size="60" value="" name="lastName" id="lastName"/>
        </div>
        
        <label class="field tooltip">Password</label></br>
        <div class="value tooltip">
            <span class="righttooltiptext">Your password should be a combination of letters and symbols</span>
            <input type="password" size="60" value="" name="new_password" id="new_password"/>
        </div></br>
        
        <label class="field tooltip">Email Address</label></br>
        <div class="value tooltip">
            <span class="bottomtooltiptext"> psst...you should use your RPI email </span>
            <input type="text" size="60" value="" name="new_email" id="new_email"/>
        </div></br>

        <label class="field tooltip">Year</label></br>
        <div class="value">
            <input type="text" size="60" value="" name="year" id="year"/>
        </div></br>

        <label class="field tooltip">Subject(s)</label></br>
        <div class="value">
            <input type="text" size="60" value="" name="subject" id="subject"/>
        </div></br>

        <label class="field">Description</label></br>
        <div class="value">
        	<textarea type="text" rows="4" cols="60" value="" name="description" id="description">
        </textarea></div></br>


        <label class="field">What Are You?</label>
        <div class="value">
            <input type="checkbox" size="60" value="student" name="student" id="student"/>I'm a Student!</br></div>
        <div class="value">
            <input type="checkbox" size="60" value="tutor" name="tutor" id="tutor"/>I'm a Tutor!</br></div>

        <input type="submit" value="save" id="save" name="save"/>
      </div>
    </fieldset>
  </form>
</body>
</html>