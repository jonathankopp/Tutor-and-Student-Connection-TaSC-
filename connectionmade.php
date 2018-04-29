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
			<a id="logout" href="index.php"> Logout </a>
		</div>

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

			$subject = $_SESSION["searchSubject"];

			if ($dbOk) {
				$subjQuery = "SELECT userid from user_subjects where course='" . $subject . "'";
				$result = $db->query($subjQuery);
				$numRecords = $result->num_rows;
				$match = false;

				for ($i=0; $i<$numRecords; $i++) {
					$record = $result->fetch_assoc();
					$uid = $record["userid"];

					if (isset($_POST[$uid])) {
						if ($tutor) {
							$query = "INSERT into connections (`tutorid`, `studentid`, `subject`) VALUES (?,?,?)";
							$statement = $db->prepare($query);
							$statement->bind_param("iis", $userid, $uid, $subject);
							$statement->execute();
							$statement->close();
						} else {
							$query = "INSERT into connections (`tutorid`, `studentid`, `subject`) VALUES (?,?,?)";
							$statement = $db->prepare($query);
							$statement->bind_param("iis", $uid, $userid, $subject);
							$statement->execute();
							$statement->close();
						}
						echo "Connection made!";
					}
				}
			}
		?>
	</body>
</html>
