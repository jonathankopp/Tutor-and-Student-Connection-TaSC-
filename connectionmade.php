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
			<a href="connect.php"> Back to Connections </a>
			<a id="logout" href="index.php"> Logout </a>
		</div>

		<?php
			$dbOk = false;

			//makes connection to database
			@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

			//if error connecting to database
			if ($db->connect_error) {
				echo '<div class="messages">Could not connect to the database. Error: ';
				echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
			} else {
				$dbOk = true; 
			}
			//pulls the userid from session
			$userid = $_SESSION["userid"];
			$tutorquery = "SELECT tutor from users where userid='". $userid ."'";
			$istutor = $db->query($tutorquery);
			$tutorrecord = $istutor->fetch_assoc();
			$tutor = $tutorrecord["tutor"]; //boolean if user is a tutor

			$subject = $_SESSION["searchSubject"]; //subject that was searched on the connect page

			if ($dbOk) {
				//finds all users that have the matching subject to the search subject
				$subjQuery = "SELECT userid from user_subjects where course='" . $subject . "'";
				$result = $db->query($subjQuery);
				$numRecords = $result->num_rows;

				//loops through all found users
				//when a userid matches the id of the button is pressed
				//it will add them to the connections database and print out that a connections is made
				for ($i=0; $i<$numRecords; $i++) {
					$record = $result->fetch_assoc();
					$uid = $record["userid"];

					if (isset($_POST[$uid])) { //if the userid button has been pressed, connect them
						if ($tutor) { //if user is a tutor, add them to tutor
							$query = "INSERT into connections (`tutorid`, `studentid`, `subject`) VALUES (?,?,?)";
							$statement = $db->prepare($query);
							$statement->bind_param("iis", $userid, $uid, $subject);
							$statement->execute();
							$statement->close();
						} else { //assume they are a student and add them to student column
							$query = "INSERT into connections (`tutorid`, `studentid`, `subject`) VALUES (?,?,?)";
							$statement = $db->prepare($query);
							$statement->bind_param("iis", $uid, $userid, $subject);
							$statement->execute();
							$statement->close();
						}
						echo '<p id="found"> Connection made! </p>';
					}
				}
			}
		?>
	</body>
</html>
