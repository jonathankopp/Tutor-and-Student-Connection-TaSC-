<?php
	session_start();
?>

<!DOCTYPE html>


<html>
<head>
	<title>TaSC Connections</title>
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
		<li><a class="nav-item" href="forum.php"> Discussion Forum </a></li>
		<li><a class="nav-item" href="reviews.php">Review</a></li>
		<li><a class="nav-item" href="addsubject.php">Add a Subject</a></li>
		<li><a class="nav-item" href="addsubject.php">Add a Subject to tutor in</a></li>
		<li><a class="nav-item" href="profile.php">My Profile</a></li>
		<li class="bottom"><a id="logout" href="index.php">Logout</a></li>
	</ul>
	 <div class="jumbotron">
		<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
		<div>
		  <h1 class="title">Tutor and Student Connection</h1>
		</div>
	  </div>
	<div class="wrapperProfile">
		<div class="connections">
		<div class="box">
			<h2> Personal Info </h2>
			<?php

				$dbOk = false;

				//connects to database 
				@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

				//error message if connection to database fails
				if ($db->connect_error) {
					echo '<div class="messages">Could not connect to the database. Error: ';
					echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
				} else {
					$dbOk = true; 
				}

				//pulls the user id from the session and accesses user table 
				//to find if the user is a tutor or a student 
				$userid = $_SESSION["userid"]; //gets user id from session
				$namequery = "SELECT first_names,last_name from users where userid='". $userid ."'";
				$nameCall = $db->query($namequery);
				$resCall = $nameCall->fetch_assoc();

				//if the user is a tutor, find all connections where the tutorid
				//matches the userid to output connections
					echo "<h3>".$resCall["first_names"]." ".$resCall["last_name"]."</h3>";
					echo "<p>Hello I am a new student trying to learn how to code</p>";

			?>
			
		</div>
		</div>
		<div class="connections">
			<h2> Connections </h2>

				<?php

					$dbOk = false;

					//connects to database 
					@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

					//error message if connection to database fails
					if ($db->connect_error) {
						echo '<div class="messages">Could not connect to the database. Error: ';
						echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
					} else {
						$dbOk = true; 
					}

					//pulls the user id from the session and accesses user table 
					//to find if the user is a tutor or a student 
					$userid = $_SESSION["userid"]; //gets user id from session
					$tutorquery = "SELECT * from users where userid='". $userid ."'";
					$istutor = $db->query($tutorquery);
					$tutorrecord = $istutor->fetch_assoc();
					$tutor = $tutorrecord["tutor"]; //boolean for user being a tutor

					//if the user is a tutor, find all connections where the tutorid
					//matches the userid to output connections
					if ($tutor) {
						$query = "SELECT * from connections where tutorid='" . $userid . "'";
						$result = $db->query($query);
						$numRecords = $result->num_rows;
						echo '<h4>Who I tutor:</h4>';
						//for every connection the tutor has, print out the information
						//of the other user
						for ($i=0; $i < $numRecords; $i++) {
							$record = $result->fetch_assoc();
							$sid = $record["studentid"];

							$infoQuery = "SELECT * from users where userid='" . $sid . "'";
							$infoResult = $db->query($infoQuery);
							$info = $infoResult->fetch_assoc();

							echo "<h3> " . htmlspecialchars($info["first_names"]) . " ";
							echo htmlspecialchars($info["last_name"]) . "</h3>";
							echo '<p> Email: ' . $info["email"] . '</p>';
							echo "<p> Course(s): ";

							//If the user is in multiple subjects, output all of the subjects that 
							//the user is in
							$subjquery = "SELECT course from user_subjects where userid='" . $sid . "'";
							$subjresults = $db->query($subjquery);
							$numSubjects = $subjresults->num_rows;

							for ($j=0; $j < ($numSubjects-1); $j++) {
								$subj = $subjresults->fetch_assoc();
								echo $subj["course"] . ", ";
							}
							$subj = $subjresults->fetch_assoc();
							echo $subj["course"] . "</p>";

							echo "<p> Year: " . $info["year"] . "</p>";
							echo "<p> " . $info["description"] . "</p>";
						}

					} else { //assume the user is a student
						//uses the same method as above but matches userid to studentid instead of tutorid
						$query = "SELECT * from connections where studentid='" . $userid . "'";
						$result = $db->query($query);
						$numRecords = $result->num_rows;
						echo "<h4>Tutors:</h4>";
						//prints out info for each connection made by user to tutors
						for ($i=0; $i < $numRecords; $i++) {
							$record = $result->fetch_assoc();
							$tid = $record["tutorid"];

							$infoQuery = "SELECT * from users where userid='" . $tid . "'";
							$infoResult = $db->query($infoQuery);
							$info = $infoResult->fetch_assoc();

							echo "<h3> " . htmlspecialchars($info["first_names"]) . " ";
							echo htmlspecialchars($info["last_name"]) . "</h3>";
							echo '<p> Email: ' . $info["email"] . '</p>';
							echo "<p> Course(s): ";

							$subjquery = "SELECT course from user_subjects where userid='" . $tid . "'";
							$subjresults = $db->query($subjquery);
							$numSubjects = $subjresults->num_rows;

							for ($j=0; $j < ($numSubjects-1); $j++) {
								$subj = $subjresults->fetch_assoc();
								echo $subj["course"] . ", ";
							}
							$subj = $subjresults->fetch_assoc();
							echo $subj["course"] . "</p>";

							echo "<p> Year: " . $info["year"] . "</p>";
							echo "<p> " . $info["description"] . "</p>";
						}
					}

				?>

		</div>
	</div>
</body>


</html>