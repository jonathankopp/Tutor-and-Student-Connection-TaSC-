<!DOCTYPE html>
<?php 
  session_start();
  if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}
?>

<html>
<head>
	<title>TaSC</title>
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
		<li><a class="nav-item" href="find.php">Make a Connection </a></li>
		<li><a class="nav-item" href="makepost.php">Make a Post</a></li>
		<li><a class="nav-item" href="profile.php">My Profile</a></li>
		<li><a id="navlink" href="forum.php">Back</a></li>
		<li class="bottom"><a id="logout" href="index.php">Logout</a></li> 
	</ul>
	  <div class="jumbotron">
	  	<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
		<div>
		  <h1 class="title">Tutor and Student Connection</h1>
		</div>
	  </div>
  
<!--
	<div class="sidenav">
	  <a id="navlink" href="profile.php">Profile Page</a>
	  <a id="ds" href="forum.php">Back</a>
	</div>

	<h1><div id="header"> Tutor and Student Connection</div></h1>
  
-->
	<div id="discussion">
		<!-- below php dynamically pulls all relevant comments from the database -->
		<?php
			$dbOk = false;

			//Connecting to the database
			@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

			//if there is a connection error, it displays this
			if ($db->connect_error) {
				echo '<div class="messages">Could not connect to the database. Error: ';
				echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
			} else {
				$dbOk = true; 
			}

			//If the connection to the database is successful:
			if($dbOk){

				//Sets up, and executes, a query to the database that pulls the
				//subjectid that the comment is relevant to from the database using
				//"$_SESSION['course']" which was set in the previous page, "forum.php".
				$q='select subjectid from subject where course='."'". $_SESSION['course']."'";
		    	$courses=$db->query($q);
		    	$courseid=$courses->fetch_assoc();

		    	//this pulls the post id from the url, that was sent from the previous page
		    	//"forum.php", by setting each forum's href to the current page "comment.php?post=[postid]"
		    	if(isset($_GET['post'])){
		    		$_SESSION['postid']=$_GET['post'];
		    	}else{
		    		$_SESSION['postid']=1;
		    	}

					//if there was an upvote must upvote.
					//TODO: IMPLEMENT INCREMENT
					if(isset($_POST['commentUID'])){
						//have to fetch users score and increment it by one.
						// echo '<script>console.log('.$_POST['UserId'].');</script>';
						
						//check if liked:
						// echo "<h3>".$_POST['commentUID']."</h3>";
						$hasLikedQ = 'SELECT * FROM `comment_user` WHERE `postID`= '.$_GET['commentID'].';';
						$hasLikedCall = $db->query($hasLikedQ);
						if($hasLikedCall->num_rows ==  0){
							//Insert into usercomment
							$insertUCQ = 'INSERT INTO `comment_user` (`postid`,`likeID`) VALUES (?,?);';
							$insertUCCall = $db->prepare($insertUCQ);
							$insertUCCall->bind_param("ii", $_GET['commentID'],$_SESSION['userid']);
							$insertUCCall->execute();
							$insertUCCall->close();

							//update user score
							$incUserQ = 'UPDATE users SET `score` = `score` + '. 1 .' WHERE `userid`= '.$_POST['commentUID'].';';
							$incUserCall = $db->prepare($incUserQ);
							$incUserCall->execute();
							$incUserCall->close();

							//update comment score
							$insertCommQ = 'UPDATE comments SET `likes` = `likes` + '. 1 .' WHERE `commentID`= '.$_GET['commentID'].';';
							$insertCommCall = $db->prepare($insertCommQ);
							$insertCommCall->execute();
							$insertCommCall->close();
						}
	
						



					}





		    	//Query's the database for the thread that was clicked on by selecting all from forum
		    	//where the course id is equal to the subject id and where the postid is equal to the 
		    	//post id that was selected which was gotten above and placed in $_SESSION['postid']
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

	    		//prints all the comments relevant to the thread by selecting all from
	    		//the database where the postid is equal to the postid selected, which
	    		//above $_SESSION['postid'] was set to.
			    $query='select * from comments where postid='.$_SESSION['postid'];
			    $result = $db->query($query);
			    $numRecords = $result->num_rows;
			    for($i=0; $i<$numRecords; $i++){
		    		$post = $result->fetch_assoc();
		    		echo "<ul>";
						echo '<a id="discussion">' . "Comment:" . '</a>';

		    		echo '<li class="internalDisc">' . $post['comment']. '</li>';
						echo '<li class="author">'.$post['commentdate'].'</li>';
						echo '<li class="author">Likes: '.$post['likes'].'</li>';
						echo '<form class="author" action=" comment.php?post='.$post['postid'].'&commentID='.$post['commentID'].'" method="POST">';
						echo '<button name="commentUID" value="'.$post['uid'].'" type=submit>upvote</button>';
						echo '</form>';
						echo "</ul>";
					
					}
			}
		?>
	<!-- Setting up a forum in order to submit the comment to the thread that is currently being
	viewed.  The action is set to "#" so that the page doesnt refresh so that we can use the 
	postid passed (as described above) that is stored in $_SESSION['postid'] -->
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
	  @ $db = new mysqli('localhost', 'root', 'password', 'TaSC');
	  
	  //if there is a connection error, it displays this
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
	    
	    //The following detects errors that the user commits, and lets the user
	    //know what to do to avoid this next time they try to enter input.
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
	        
	        //prepared statement that posts the postid and comment the user inputed, and the day's
	        //date into the database
	        $insQuery = "insert into comments (`postid`,`comment`,`commentdate`,`uid`) values(?,?,?,?)";
	        $statement = $db->prepare($insQuery);
					$pID=$_SESSION['postid'];
					$d=date('Y-m-d');
	        $statement->bind_param("ssss",$pID,$postForDb,$d,$_SESSION['userid']);
	        // make it so:
	        $statement->execute();
	        
	        // give the user some feedback that their post went through
	        // then refreshes the page so that their comment is viewable in
	        // real time.
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