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
	<!--<link href="Resources/makepost.css" rel="stylesheet" type="text/css"/>-->
	<link href="Resources/style.css" rel="stylesheet" type="text/css"/>
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
			<li><a id="logout" href="index.php"> Logout </a></li>
		</ul>
		<div class="jumbotron">
			<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
			<div>
			  <h1 class="title">Tutor and Student Connection</h1>
			</div>
		</div>
<section>
  <form id="makereview" action="makereview.php" method="post">
		<select class="browser-default" name="rating">
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
</section>


</body>
</html>