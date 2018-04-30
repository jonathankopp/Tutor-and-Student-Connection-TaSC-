<?php
	session_start();
?>

<!DOCTYPE html>


<html>
<head>
	<title>TaSC Connections</title>
	<link href="Resources/connect.css" rel="stylesheet" type="text/css"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <script type="text/javascript" src="Resources/jquery-1.4.3.min.js"></script>
</head>


<body>
	<h1> Tutor and Student Connection </h1>

	<div class="sidebar">
		<a id="navlink" href="forum.php"> Discussion Forum </a>
		<a href="find.php">Make a Connection </a>
		<a id="logout" href="index.php"> Logout </a>
	</div>

	<div class="connections">
		<h2> Connections </h2>

			<?php

				$dbOk = false;

				//connects to database 
				@ $db =  new mysqli('localhost', 'root', 'password', 'tasc');

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
				$tutorquery = "SELECT tutor from users where userid='". $userid ."'";
				$istutor = $db->query($tutorquery);
				$tutorrecord = $istutor->fetch_assoc();
				$tutor = $tutorrecord["tutor"]; //boolean for user being a tutor

				//if the user is a tutor, find all connections where the tutorid
				//matches the userid to output connections
				if ($tutor) {
					$query = "SELECT * from connections where tutorid='" . $userid . "'";
					$result = $db->query($query);
					$numRecords = $result->num_rows;

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


		<div class="person"></div>

	</div>

</body>


</html>