<?php
	session_start();
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}

	@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');
	date_default_timezone_set("America/New_York");
	$date = date('Y-m-d H:i:s');
	if (isset($_POST["postreview"])) {
		if (trim($_POST['review'])=="") {
			//echo "1";
			$insertquery = 'INSERT INTO reviews (`revieweremail`, `reviewedemail`, `createdat`, `rating`) VALUES (?,?,?,?);';
			$stmt = $db->prepare($insertquery);
			$stmt->bind_param("sssi", $_SESSION['uemail'], $_SESSION['viewemail'], $date, $_POST['rating']);
			$stmt->execute();
			$stmt->close();

		} else {
			//echo "2";
			$insertquery = 'INSERT INTO reviews (`revieweremail`, `reviewedemail`, `createdat`, `rating`, `review`) VALUES (?,?,?,?,?);';
			$stmt = $db->prepare($insertquery);
			$stmt->bind_param("sssis", $_SESSION['uemail'], $_SESSION['viewemail'], $date, $_POST['rating'], $_POST['review']);
			$stmt->execute();
			$stmt->close();
			//echo "here";
		}
		$updateScoreQuery = 'UPDATE users SET `score` = `score` + '.$_POST['rating'].' WHERE `email`= "'.$_SESSION['viewemail'].'";';
		$statement = $db->prepare($updateScoreQuery);
		$statement->execute();
		$statement->close();

		header('Location: viewprofile.php');
	}
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

	<form id="makereview" action="makereview.php" method="post">
		<select name="rating">
			<option value="1" >1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5" selected>5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>
		<textarea rows="10" columns="10" name="review"></textarea>
		<input type="submit" name="postreview" value="Post Review"/>
	</form>


</body>
</html>