<?php 
	session_start();
?>

<!DOCTYPE html>


<html>
	<head>
		<title>TaSC Connections</title>
		<link href="Resources/connect.css" rel="stylesheet" type="text/css"/>
	</head>


	<body>
		<h1> 
			<div id="header"> Tutor and Student Connection 
			</div>
		</h1>


		<div class="sidebar">
			<a id="navlink" href="forum.php"> Discussion Forum </a>
			<a href="profile.php"> Back to Profile </a>
			<a id="logout" href="index.php"> Logout </a>
		</div>

		<div class="subject">
			<form name="search" action="find.php" method="get">
				<label class="field">Search for a subject:</label>
				<input type="text" size="60" height="40" value="" id="subject" name="subject"/>
				<input type="submit" value="Search" id="search" name="search"/>	
			</form>
		</div>

		<div class="person">

			<?php
				$dbOk = false;

				//connects us to the database
				@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

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


		</div>

	</body>


</html>