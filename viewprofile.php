<?php
	session_start();
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}
	@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

	if (isset($_POST['connect'])) {
		$listquery = 'SELECT userid, email FROM users';
		$result = $db->query($listquery);
		while ($row = $result->fetch_assoc()) {
			if (isset($_POST[$row['userid']])) {
				$_SESSION['viewuserid'] = $row['userid'];
				$_SESSION['viewemail'] = $row['email'];
 				break;
			}
		}
	}

	if (isset($_POST['connected'])) {
		$tutor = 0;
		$student = 0;
		if ($_SESSION['tutor']) {
			$tutor = $_SESSION['userid'];
			$student = $_SESSION['viewuserid'];
		} else {
			$tutor = $_SESSION['viewuserid'];
			$student = $_SESSION['userid'];
		}

		$insquery = 'INSERT INTO connections (`tutorid`, `studentid`, `subject`) VALUES (?,?,?)';
		$stmt = $db->prepare($insquery);
		$stmt->bind_params($tutor, $student, $_SESSION['searchSubject']);
		$stmt->execute();
		$stmt->close();

		header('Location: profile.php');
	}


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
	<h1> 
		<div id="header"> Tutor and Student Connection 
		</div>
	</h1>
	<?php
	$infoquery = 'SELECT * FROM users WHERE userid = "' . $_SESSION['viewuserid'] . '"';
	$result = $db->query($infoquery);
	$info = $result->fetch_assoc();
	echo '<h2> ' . $info['first_names'] . ' ' . ' </h2>';
	echo '<p> Email: ' . $info['email'] . '</p>';
	echo '<p> Description: ' . $info['description'] . '</p>';
	echo '<p> Year: ' . $info['year'] . '</p>';
	echo '<p> Score: ' . $info['score'] . '</p>';

	$conquery = '';
	if ($_SESSION['tutor']) {
		$conquery = 'SELECT 1 FROM connections WHERE tutorid="' . $_SESSION['userid'] . '" and studentid="' . $_SESSION['viewuserid'] . '" and subject="' . $_SESSION['searchSubject'] . '"';
	} else {
		$conquery = 'SELECT 1 FROM connections WHERE tutorid="' . $_SESSION['viewuserid'] . '" and studentid="' . $_SESSION['userid'] . '" and subject="' . $_SESSION['searchSubject'] . '"';
	}
	$isconn = $db->query($conquery);
	if (!($isconn->fetch_assoc())) {
		echo '<form action="viewprofile.php" method="post">'
		echo '<input type="submit" name="connected" value="Connect"/></form>';
	}

	$avgquery = 'SELECT avg(rating) as a FROM reviews WHERE reviewedemail = "' . $info['email'] . '"';

	$avgresult = $db->query($avgquery);
	$row = $avgresult->fetch_assoc();
	echo '<h2> Average Rating: ' . $row['a'] . '</h2>';

	$query = 'SELECT * FROM reviews WHERE reviewedemail = "'.$info['email'].'"';

	$result = $db->query($query);
	while($row = $result->fetch_assoc()) {
		$uq = 'SELECT first_names, last_name FROM users WHERE email = "' . $row["revieweremail"] . '"';
		$uresult = $db->query($uq);
		$urow = $uresult->fetch_assoc();

		echo '<h2>' . $row["rating"] . '</h2>';
		echo '<p>' . $row["review"] . '</p>';
		echo "By: " . $urow["first_names"] . " " . $urow["last_name"];
		echo $row["createdat"];
	}

	?>

<a href="makereview.php">Make a Review</a>
</body>



</html>