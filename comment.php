<!DOCTYPE html>
<?php 
  session_start();
?>
<html>
<head>
	<title>TaSC</title>
	<link href="Resources/forum.css" rel="stylesheet" type="text/css"/>
</head>
<body>
	<div class="sidenav">
	  <a id="navlink" href="connect.php">Connect Page</a>
	  <a id="ds" href="forum.php">Back</a>

	</div>
	<h1><div id="header"> Tutor and Student Connection</div></h1>
	<div id="discussion">
		<?php
			$dbOk = false;

			@ $db =  new mysqli('localhost', 'root', 'Mets2014', 'TaSC');

			if ($db->connect_error) {
				echo '<div class="messages">Could not connect to the database. Error: ';
				echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
			} else {
				$dbOk = true; 
			}
			if($dbOk){
				$q='select subjectid from subject where course='."'". $_SESSION['course']."'";
		    	$courses=$db->query($q);
		    	$courseid=$courses->fetch_assoc();
		    	if(isset($_GET['post'])){
		    		$_SESSION['postid']=$_GET['post'];
		    	}else{
		    		$_SESSION['postid']=1;
		    	}
		    	//displays thread
				$query = 'select * from forum where courseid='.$courseid["subjectid"].' and postid='.$_SESSION['postid'];
    			$result = $db->query($query);
		    	$post = $result->fetch_assoc();
				echo "<ul>";
	   			echo '<a id="discussion">' . $post['topic'] . '</a>';
	    		echo '<li class="internalDisc">' . $post['post']. '</li>';
	    		$q='select first_names from users where userid='. $post['userid'];
	    		$fn=$db->query($q);
	    		$fname=$fn->fetch_assoc();
	    		echo '<li class="author">' . "Posted by ".$fname['first_names'] ." ".$post['postdate'].'</li>';
	    		echo "</ul>";

	    		//print comments
			    $query='select * from comments where postid='.$_SESSION['postid'];
			    $result = $db->query($query);
			    $numRecords = $result->num_rows;
			    for($i=0; $i<$numRecords; $i++){
		    		$post = $result->fetch_assoc();
		    		echo "<ul>";
		   			echo '<a id="discussion">' . "Comment:" . '</a>';
		    		echo '<li class="internalDisc">' . $post['comment']. '</li>';
		    		echo '<li class="author">'.$post['commentdate'].'</li>';
		    		echo "</ul>";
			    }

			}
		?>
	</div>
	<form id="old_user" name="new_user" action="#" method="post" onsubmit="return validate(this);">
		<fieldset>
			<div class="formData">
	            <label class="field">Comment:</label>
	            <div class="value">
	            	<textarea rows=4 cols=80 value="" name="context" id="context">
	            	</textarea>
	            </div>

	            <input type="submit" value="save" id="save" name="save"/>
			</div>
		</fieldset>
	</form>

	<?php
	  // We'll need a database connection both for retrieving records and for 
	  // inserting them.  Let's get it up front and use it for both processes
	  // to avoid opening the connection twice.  If we make a good connection, 
	  // we'll change the $dbOk flag.
	  $dbOk = false;
	  
	  /* Create a new database connection object, passing in the host, username,
	     password, and database to use. The "@" suppresses errors. */
	  @ $db = new mysqli('localhost', 'root', 'Mets2014', 'TaSC');
	  
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
	    $context = htmlspecialchars(trim($_POST["context"]));
	    
	    // special handling for the date of birth
	   	//$dobTime = strtotime($dob); // parse the date of birth into a Unix timestamp (seconds since Jan 1, 1970)
	    //$dateFormat = 'YYYY'; // the date format we expect, yyyy-mm-dd
	    // Now convert the $dobTime into a date using the specfied format.
	    // Does the outcome match the input the user supplied?  
	    // The right side will evaluate true or false, and this will be assigned to $dobOk
	    //$dobOk = date($dateFormat, $year) == $dob;  
	    
	    $focusId = ''; // trap the first field that needs updating, better would be to save errors in an array
	    
	    if ($context == '') {
	      $errors .= '<li>Cannot submit a blank field</li>';
	      if ($focusId == '') $focusId = '#context';
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
	        // Let's trim the input for inserting into mysql
	        // Note that aside from trimming, we'll do no further escaping because we
	        // use prepared statements to put these values in the database.
	        $postForDb=trim($_POST["context"]);
	        // Setup a prepared statement. Alternately, we could write an insert statement - but 
	        // *only* if we escape our data using addslashes() or (better) mysqli_real_escape_string().
	        $insQuery = "insert into comments (`postid`,`comment`,`commentdate`) values(?,?,?)";
	        $statement = $db->prepare($insQuery);
			$pID=$_SESSION['postid'];
			$d=date('Y-m-d');
	        $statement->bind_param("sss",$pID,$postForDb,$d);
	        // make it so:
	        $statement->execute();
	        
	        // give the user some feedback
	        echo '<div class="makepost">';
	        echo "Comment posted". '</div>';
	        echo '<meta http-equiv="refresh" content="1" />';
	        
	        // close the prepared statement obj 
	        $statement->close();
	      }
	    } 
	  }
	?>
</body>
</html>