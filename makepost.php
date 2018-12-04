<!DOCTYPE html>

<!-- Starting the session to give access to use and creation of $_SESSION[NAME] 
variables -->
<?php 
  session_start();
?>

<html>
<head>
	<title>TaSC</title>
	<!--<link href="Resources/makepost.css" rel="stylesheet" type="text/css"/>-->
	<link href="Resources/style.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="Resources/jquery-1.4.3.min.js"></script>
		  <!-- Compiled and minified CSS -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

		<!-- Compiled and minified JavaScript -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
		  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		
		
		<script>
		  document.addEventListener('DOMContentLoaded', function() {
			var elems = document.querySelectorAll('.sidenav');
			var instances = M.Sidenav.init(elems, options);
		  });
		  // Initialize collapsible (uncomment the lines below if you use the dropdown variation)
		  // var collapsibleElem = document.querySelector('.collapsible');
		  // var collapsibleInstance = M.Collapsible.init(collapsibleElem, options);
		  // Or with jQuery
		  $(document).ready(function(){
			 $('.sidenav').sidenav();
		  });
		</script>
</head>
<body>
  		<ul id="slide-out" class="sidenav">
			<li><a id="navlink" href="forum.php"> Discussion Forum </a></li>
			<li><a class="nav-item" href="profile.php">My Profile</a></li>
			<li><a id="logout" href="index.php"> Logout </a></li>
		</ul>
		<div class="jumbotron">
			<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
			<div>
			  <h1 class="title">Tutor and Student Connection</h1>
			</div>
		</div>
  
  <section>
	<form id="old_user" name="new_user" action="makepost.php" method="post" onsubmit="return validate(this);">
		<fieldset>
			<legend>New Post</legend>
			<div class="formData">
				<label class="field">Subject:</label>
	            <div class="value"><input type="text" size="60" value="" name="subject" id="subject"/></div>
	            
	            <label class="field">Description:</label>
	            <div class="value">
	            	<textarea class="formData" rows=4 cols=80 value="" name="context" id="context">
	            	</textarea>
	            </div>

	            <input type="submit" value="save" id="save" name="save"/>
			</div>
		</fieldset>
	</form>
</section>
	<?php
	  // We'll need a database connection both for retrieving records and for 
	  // inserting them.  Let's get it up front and use it for both processes
	  // to avoid opening the connection twice.  If we make a good connection, 
	  // we'll change the $dbOk flag.
	  $dbOk = false;
	  
	  /* Create a new database connection object, passing in the host, username,
	     password, and database to use. The "@" suppresses errors. */
	  @ $db = new mysqli('localhost', 'root', 'password', 'TaSC');
	  
	  //if cannot connect to the database
	  if ($db->connect_error) {
	    echo '<div class="messages">Could not connect to the database. Error: ';
	    echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
	  } else {
	    $dbOk = true; 
	  }

	  // Now let's process our form:
	  // Have we posted?
	  $havePost = isset($_POST["save"]);
	  
	  // Let's do some basic validation
	  $errors = '';
	  if ($havePost) {
	    
	    // Get the output and clean it for output on-screen.
	    // First, let's get the output one param at a time.
	    // Could also output escape with htmlentities()
	    $subject = htmlspecialchars(trim($_POST["subject"])); 
	    $context = htmlspecialchars(trim($_POST["context"]));
	    
	    
	    
	    $focusId = ''; // trap the first field that needs updating, better would be to save errors in an array
	    
	    //user input error checking, with a respective message to tell the user
	    //how to fix the errors for next input attempt.
	    if ($subject == '') {
	      $errors .= '<li>First name may not be blank</li>';
	      if ($focusId == '') $focusId = '#subject';
	    }
	    if ($context == '') {
	      $errors .= '<li>First name may not be blank</li>';
	      if ($focusId == '') $focusId = '#context';
	    }
	  
	  	//displays all errors ran into above
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

	      //if the connection was succesful	
	      if ($dbOk) {

	        // Let's trim the input for inserting into mysql
	        // Note that aside from trimming, we'll do no further escaping because we
	        // use prepared statements to put these values in the database.
	        $topicForDb = trim($_POST["subject"]); 
	        $postForDb=trim($_POST["context"]);

	        // Setup a prepared statement. Alternately, we could write an insert statement - but 
	        // *only* if we escape our data using addslashes() or (better) mysqli_real_escape_string().
	        $insQuery = "insert into forum (`courseid`,`topic`,`post`,`postdate`,`userid`) values(?,?,?,?,?)";
	        $statement = $db->prepare($insQuery);

	        //querying the database for the subject id for the current course($_SESSION['course'])
	        //from the table 'subject'
	        $qa='select subjectid from subject where course='."'". $_SESSION['course']."'";
		    $courses=$db->query($qa);
		    $courseid=$courses->fetch_assoc();

		    //setting the date of the post and the id to the current user, stored in
		    //$_SESSION['userid'], and then binds the parameter for injection into the
		    //database to the questionmarks
			$d=date('Y-m-d');$id=$_SESSION['userid'];
	        $statement->bind_param("sssss",$courseid["subjectid"],$topicForDb,$postForDb,$d,$id);
	       
	        // Then executes the statment, submitting the infromation into the database
	        $statement->execute();
	        
	        // give the user some feedback
	        // Tells them that their thread has been created
	        echo '<div class="makepost">';
	        echo "Thread: "."'".$topicForDb."'"." has been created". '</div>';
	        
	        // close the prepared statement obj 
	        $statement->close();
	      }
	    } 
	  }
	?>
</body>
</html>