<?php 
	session_start();
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}

	@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

	if (isset($_POST['findstudent'])) {
		$_SESSION['s_table'] = "tutor_subjects";
		$_SESSION['tutor'] = 1;
	}
	if (isset($_POST['findtutor'])) {
		$_SESSION['s_table'] = "student_subjects";
		$_SESSION['tutor'] = 0;
	}

	$table = $_SESSION["s_table"];

?>

<!DOCTYPE html>


<html>
<head>
	<title>TaSC</title>
	<link href="Resources/style.css" rel="stylesheet" type="text/css"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

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
		<li class="bottom"><a id="logout" href="index.php">Logout</a></li>
		</ul>
		<div class="jumbotron">
			<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
			<div>
			  <h1 class="title">Tutor and Student Connection</h1>
			</div>
		</div>
		<div class="subject">
			<form name="search" action="find.php" method="get">
				<label class="field">Search for a subject:</label>
				<input type="text" size="60" height="40" value="" id="subject" name="subject"/>

				<label class="field">Choose a Subject:</label>
				<select class="browser-default" name="subject">
					<?php
					$subjectquery = 'SELECT course FROM ' . $table . ' WHERE userid="' . $_SESSION['userid'] . '"';
					$result = $db->query($subjectquery);
					while($subjects = $result->fetch_assoc()) {
						if (isset($_GET['search']) && $_GET['subject'] == $subjects['course']) {
							echo '<option value="' . $subjects['course'] . '"selected>' . $subjects['course'] . '</option?>';
						}else {
							echo '<option value="' . $subjects['course'] . '">' . $subjects['course'] . '</option?>';
						}
					}
					?>
				</select>
				<input type="submit" value="Search" id="search" name="search"/>	
			</form>
		</div>

		<div class="person">

			<?php
			if (isset($_POST['subject'])) {
				$_SESSION['searchSubject'] = $_POST['subject'];
				$opptable = "";
				if ($table == "tutor_subjects") {
					$opptable = "student_subjects";
				} else {
					$opptable = "tutor_subjects";
				}
				$matchquery = 'SELECT s.userid FROM ' . $opptable . ' s, users u WHERE s.course = "' . $_POST['subject'] . '" and s.userid = u.userid ORDER BY u.score DESC;';

				$result = $db->query($matchquery);
				if ($result->num_rows == 0) {
					echo '<p id="nomatch"> No Matches Found </p>';
				} else {
					echo '<form class="makeconnection" action="viewprofile.php" method="post" name="connect">';
					while($row = $result->fetch_assoc()) {
							//selects the info for that user from users table to output
							$infoQuery = "SELECT userid, first_names, last_name, score from users where userid='" . $row['userid'] . "'";
							$infoResult = $db->query($infoQuery);
							$info = $infoResult->fetch_assoc();

							$name = $info["first_names"] . ' ' . $info["last_name"] . ': ' . $info['score'];
							//makes a button for each user 
							echo '<input type="submit" value="' . $name . '" id="' . $info['userid'] . '" name="' . $info['userid'] . '"/>';
							
					}
					echo '</form>';
				}
			}

			?>


		</div>

	</body>


</html>

<!-- 
				$dbOk = false;


				//if error connecting to database
				if ($db->connect_error) {
					echo '<div class="messages">Could not connect to the database. Error: ';
					echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
				} else {
					$dbOk = true; 
				}
				//pulls userid from session
				$userid = $_SESSION["userid"];
				$tutorquery = "SELECT tutor from users where userid='". $userid ."'";
				$istutor = $db->query($tutorquery);
				$tutorrecord = $istutor->fetch_assoc();
				$tutor = $tutorrecord["tutor"]; //boolean for user is tutor or student

				$haveSearch = isset($_GET["search"]); //if a search has been made

				if ($haveSearch) {
					//takes in the input subject
					$subject = htmlspecialchars(trim($_GET["subject"]));
					$_SESSION["searchSubject"] = $subject;

					//selects all users in user_subjects table 
					//if they are the opposite tutor value, it will print them 
					//out so user can see who they can connect to
					if ($dbOk) {
						//queries into user_subjects table to find all users with matching subject
						$subjQuery = "SELECT userid from user_subjects where course='" . $subject . "'";
						$result = $db->query($subjQuery);
						$numRecords = $result->num_rows;
						$match = false; //boolean for a match is made 

						//loops through each student that matches the search subject
						for ($i=0; $i<$numRecords; $i++) {
							$record = $result->fetch_assoc();
							$uid = $record["userid"];

							//selects the info for that user from users table to output
							$infoQuery = "SELECT * from users where userid='" . $uid . "'";
							$infoResult = $db->query($infoQuery);
							$info = $infoResult->fetch_assoc();

							$matchTutor = $info["tutor"];

							//only prints out the connection if the boolean value for tutor is opposite
							//ie only prints out students if user is a tutor or tutors if user is a student
							if ($tutor != $matchTutor) { 
								$match = true;

								echo $info["first_names"] . ' ' . $info["last_name"];
								echo '<p> Course: ' . $subject . '</p>';
								echo '<p> Year: ' . $info["year"] . '</p>';
								echo '<p> Description: ' . $info["description"] . '</p>';
								//makes a button for each user 
								//when clicked it brings user to connectionmade.php where the pressed uid button 
								//will be added to database
								echo '<form class="makeconnection" action="connectionmade.php" method="post">';
								echo '<input type="submit" value="Connect" id="' . $uid . '" name="' . $uid . '"/>';
								echo '</form>';
 							}
						}
						if (!$match) { //if no match to searched subject
							echo '<p id="nomatch"> No Matches Found </p>';
						}
					}
				}


 ?>
 -->