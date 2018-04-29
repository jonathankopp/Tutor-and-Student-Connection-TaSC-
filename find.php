<?<?php 
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
			<a id="logout" href="index.html"> Logout </a>
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


				@ $db =  new mysqli('localhost', 'root', 'ITWS661650063aletar', 'tasc');

				if ($db->connect_error) {
					echo '<div class="messages">Could not connect to the database. Error: ';
					echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
				} else {
					$dbOk = true; 
				}
				$userid = $_SESSION["userid"];
				$tutorquery = "SELECT tutor from users where userid='". $userid ."'";
				$istutor = $db->query($tutorquery);
				$tutorrecord = $istutor->fetch_assoc();
				$tutor = $tutorrecord["tutor"];

				$haveSearch = isset($_GET["search"]);

				if ($haveSearch) {
					$subject = htmlspecialchars(trim($_GET["subject"]));
					$_SESSION["searchSubject"] = $subject;

					if ($dbOk) {
						$subjQuery = "SELECT userid from user_subjects where course='" . $subject . "'";
						$result = $db->query($subjQuery);
						$numRecords = $result->num_rows;
						$match = false;

						for ($i=0; $i<$numRecords; $i++) {
							$record = $result->fetch_assoc();
							$uid = $record["userid"];

							$infoQuery = "SELECT * from users where userid='" . $uid . "'";
							$infoResult = $db->query($infoQuery);
							$info = $infoResult->fetch_assoc();

							$matchTutor = $info["tutor"];

							if ($tutor != $matchTutor) {
								$match = true;

								echo $info["first_names"] . ' ' . $info["last_name"];
								echo '<p> Course: ' . $subject . '</p>';
								echo '<p> Year: ' . $info["year"] . '</p>';
								echo '<p> Description: ' . $info["description"] . '</p>';
								echo '<form class="makeconnection" action="connectionmade.php" method="post">';
								
								echo '<input type="submit" value="Connect" id="' . $uid . '" name="' . $uid . '"/>';
								echo '</form>';
 							}
						}
						if (!$match) {
							echo '<p id="nomatch"> No Matches Found </p>';
						}
					}
				}



			?>


		</div>

	</body>


</html>