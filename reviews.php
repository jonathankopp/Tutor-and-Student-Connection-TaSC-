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

		$user = $_POST["reviewid"];

		$avgquery = 'SELECT avg(rating) FROM reviews WHERE uid = "' . $user . '"';

		$avgresult = $db->query($avgquery);
		echo '<h1>' . $avgresult . '</h1>';

		$query = 'SELECT * FROM reviews WHERE uid = "'.$user.'"';

		$result = $db->query($query);
		while($row = $result->fetch_assoc()) {
			echo '<h2>' . $row["rating"] . '</h2>';
			echo '<p>' . $row["review"] . '</p>';
			echo $row["date"];
		}

	?>

	<form action="makereview.php" method="post">
		<input type="submit" id="postreview" value="Leave a Review">
	</form>

</body>
</html>
