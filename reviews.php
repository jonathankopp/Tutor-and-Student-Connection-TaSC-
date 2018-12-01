<?php
	session_start();
?>

<!DOCTYPE html>


<html>
<head>
	<title>TaSC Reviews</title>
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
		//setting up database connection
		@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

		$user = $_SESSION['uemail'];

		$avgquery = 'SELECT avg(rating) as a FROM reviews WHERE reviewedemail = "' . $user . '"';

		$avgresult = $db->query($avgquery);
		$row = $avgresult->fetch_assoc();
		echo '<h1>' . $row['a'] . '</h1>';

		$query = 'SELECT * FROM reviews WHERE reviewedemail = "'.$user.'"';

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
