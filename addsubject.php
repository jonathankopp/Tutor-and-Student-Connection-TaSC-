<?php
	//start the user session
	session_start();

	//if the userid isn't set yet, the user should be taken to index.php
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}

	include "config.php";

	//if user is looking for a tutor, query the tutor table, else query the student table
	if (isset($_POST['tutorsubject'])) {
		$_SESSION['s_table'] = "tutor_subjects";
	}
	if (isset($_POST['studentsubject'])) {
		$_SESSION['s_table'] = "student_subjects";
	}

	$table = $_SESSION['s_table'];

	//if the user wants to add a subject
	if (isset($_POST['addsubject'])) {
		//create query to add subject and insert it into the chosen table above
		$inquery = 'INSERT INTO ' . $table . ' (`userid`, `course`) VALUES (?,?)';
		$stmt = $db->prepare($inquery);
		$stmt->bind_param("is",$_SESSION['userid'], $_POST['addsubject']);
		$stmt->execute();
		$stmt->close();

		//go to the profile page
		header('Location: profile.php');
	}
?>

<!DOCTYPE html>

<html>
<head>
	<title>Add Subject</title>
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
			var instances = M.Sidenav.init(elems);
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
		<li><a class="nav-item" href="forum.php"> Discussion Forum </a></li>
		<li><a class="nav-item" href="profile.php">My Profile</a></li>
		<li class="bottom"><a id="logout" href="index.php">Logout</a></li>
	</ul>
	 <div class="jumbotron">
		<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
		<div>
		  <h1 class="title">Tutor and Student Connection</h1>
		</div>
	</div>
  
	<form id="addSubject" action="addsubject.php" method="post">
  	<p>Add a subject:</p>
		<select class="browser-default" name="addsubject">
			<?php
				//query to get courses that the user isn't already in
				$subquery = 'SELECT course FROM subject WHERE course NOT IN (';
				$subquery .= 'SELECT course FROM ' . $table . ' WHERE userid = ' . $_SESSION['userid'] . ');';
				//execute the query and display the results
				$result = $db->query($subquery);
				while($row=$result->fetch_assoc()) {
					echo '<option value="' . $row['course'] . '">'.$row['course'] . ' </option>';
				}
			?>
		</select>
		<input type="submit" name="add" value="Add Subject"/>
	</form>
</body>


</html>