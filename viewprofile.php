<?php
	//start the user session
	session_start();

	//if the user isn't logged in, go to index
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}

	//connect to the database
	@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

	//query for the ids and emails of all users
	$listquery = 'SELECT userid, email FROM users';
	$result = $db->query($listquery);

	//go through user info
	while ($row = $result->fetch_assoc()) {
		//if the student row is selected, give the values of the students
		$index = 'viewstudent'.$row['userid'];
		if (isset($_POST[$index])) {
			$_SESSION['viewuserid'] = $row['userid'];
			$_SESSION['viewemail'] = $row['email'];
			$_SESSION['searchSubject'] = "";
			$_SESSION['tutor'] = 1;
		}
		//if the tutot row is selected, give the values of the tutors
		$index = 'viewtutor'.$row['userid'];
		if (isset($_POST[$index])) {
			$_SESSION['viewuserid'] = $row['userid'];
			$_SESSION['viewemail'] = $row['email'];
			$_SESSION['searchSubject'] = "";
			$_SESSION['tutor'] = 0;
		}

		//if all user ids are selected, give the values
		if (isset($_POST[$row['userid']])) {
			$_SESSION['viewuserid'] = $row['userid'];
			$_SESSION['viewemail'] = $row['email'];
				break;
		}
	}

	//if a connection is trying to be made
	if (isset($_POST['connected'])) {
		$tutor = 0;
		$student = 0;
		
		//get the information id of the current user and their selected student/tutor
		if ($_SESSION['tutor']) {
			$tutor = $_SESSION['userid'];
			$student = $_SESSION['viewuserid'];
		} else {
			$tutor = $_SESSION['viewuserid'];
			$student = $_SESSION['userid'];
		}

		//add the connection to the database
		$insquery = 'INSERT INTO connections (`tutorid`, `studentid`, `subject`) VALUES (?,?,?)';
		$stmt = $db->prepare($insquery);
		$stmt->bind_param("iis", $tutor, $student, $_SESSION['searchSubject']);
		$stmt->execute();
		$stmt->close();

		//go to the profile page
		header('Location: profile.php');
	}


?>

<!DOCTYPE html>


<html>
<head>
	<title>TaSC Reviews</title>
	<!--<link href="Resources/makepost.css" rel="stylesheet" type="text/css"/>-->
	<link href="Resources/style.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="Resources/jquery-1.4.3.min.js"></script>
	<!--Compiled and minified CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">

	<!--Compiled and minified JavaScript -->
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
			<li class="bottom"><a id="bottom" href="index.php"> Logout </a></li>
		</ul>
		<div class="jumbotron">
			<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
			<div>
			  <h1 class="title">Tutor and Student Connection</h1>
			</div>
		</div>

	<?php
	/*************************************** RANKING SYSTEM ***************************************/



	//						                Calculating Percentile

	//getting single users Score
	$userid = $_SESSION["viewuserid"]; //gets user id from session
	$scorequery = "SELECT score from users where userid='". $userid ."'";
	$scoreCall = $db->query($scorequery);
	$score = $scoreCall->fetch_assoc();

	//getting avg of all scores
	$averageScoreQ= "SELECT AVG(score) from users";
	$avgScore = $db->query($averageScoreQ);
	$maybe = $avgScore->fetch_assoc();

	//getting all scores
	$allScoresQ="SELECT score FROM users";
	$allScores = $db->query($allScoresQ);
	$numScores = $allScores->num_rows;

	//Calculating Standard Deviation
	$summation = 0;
	for ($i = 0; $i<$numScores; $i++){
		$currScore = $allScores->fetch_assoc();
		$summation += pow((int)((int)$currScore['score']- (int)$maybe['AVG(score)']),(int)2);
	}
	$standardDev = sqrt(((float)$summation / (float)$numScores));


	//finding Percentile
	$percentile=0;
	$zScore = ((int)((int)$score['score'] - (int)$maybe['AVG(score)'])/(int)$standardDev);

	//Using a Z score -> Percentile on Normal Curve Chart
	if($zScore < -2.5){
		$percentile = 0;
	}else if($zScore < -2 and $zScore>= -2.5){
		$percentile = 1;
	}else if($zScore < -1.5 and $zScore>= -2){
		$percentile = 2-($zScore+2);
	}else if($zScore < -1 and $zScore>= -1.5){
		$percentile = 6-($zScore+1.5);
	}else if($zScore < -0.5 and $zScore>= -1){
		$percentile = 15-($zScore+1);
	}else if($zScore < 0 and  $zScore>=-0.5){
		$percentile = 30-($zScore+0.5);
	}else if($zScore > 0 and $zScore <= 0.5){
		$percentile = 69-(0.5-$zScore);
	}else if($zScore > 0.5 and $zScore <= 1){
		$percentile = 84-(1-$zScore);
	}else if($zScore >1 and $zScore <=1.5){
		$percentile = 93-(1.5-$zScore);
	}else if($zScore >1.5 and $zScore <=2){
		$percentile = 97-(2-$zScore);
	}else if($zScore >2 and $zScore<=2.5){
		$percentile = 99-(1.5-$zScore);
	}else if($zScore>2.5){
		$percentile = 100-(3-$zScore);
	}else{
		//ERROR SPECIAL VALUE
		$percentile=-7768;
	}

	//Catches outliers
	if($percentile>100){
		$percentile = 100;
	}else if($percentile<0){
		$percentile=0;
	}

	//										Done with Percentile

	//Array of ranks
	$ranks=['new','inactive','novice','Bronze','Reliable','Silver','Gold','Trusted','TaSC Star','Professor?'];

	//Scaling percentile to index in the array of ranks
	$userRank=$ranks[((int)$percentile/(int)10)-1];

	//query to get info on a selected user
	$infoquery = 'SELECT * FROM users WHERE userid = "' . $_SESSION['viewuserid'] . '"';
	$result = $db->query($infoquery);
	//display the user info
	$info = $result->fetch_assoc();
	echo '<div class="wrapperViewProf"><div class="right"><h2> ' . $info['first_names'] . ' ' . $info['last_name'].  ' </h2>';
	echo '<p> Email: ' . $info['email'] . '</p>';
	echo '<p> Description: ' . $info['description'] . '</p>';
	echo '<p> Year: ' . $info['year'] . '</p>';
	echo '<p> TaSC Rating: ' . $userRank . '</p>';

	//query for connection. The places to grab the neccessary info vary depending on whether the user is a student or tutor
	$conquery = '';
	if ($_SESSION['tutor']) {
		$conquery = 'SELECT 1 FROM connections WHERE tutorid="' . $_SESSION['userid'] . '" and studentid="' . $_SESSION['viewuserid'] . '" and subject="' . $_SESSION['searchSubject'] . '"';
	} else {
		$conquery = 'SELECT 1 FROM connections WHERE tutorid="' . $_SESSION['viewuserid'] . '" and studentid="' . $_SESSION['userid'] . '" and subject="' . $_SESSION['searchSubject'] . '"';
	}

	//execute the query and display it
	$isconn = $db->query($conquery);
	if (!($isconn->fetch_assoc()) && ($_SESSION['searchSubject'] != "")) {
		echo '<form action="viewprofile.php" method="post">';
		echo '<input type="submit" name="connected" value="Connect"/></form>';
	}
	echo "</div>";

	//query to get the average rating of a user
	$avgquery = 'SELECT avg(rating) as a FROM reviews WHERE reviewedemail = "' . $info['email'] . '"';

	//execute the query and display it
	$avgresult = $db->query($avgquery);
	$row = $avgresult->fetch_assoc();
	echo '<div class="left"><h4><u> Average Rating: ' . $row['a'] . '</u></h4>';
	echo '<a><h5>Reviews:<h5></a>';

	//query to select the reviews of a user
	$query = 'SELECT * FROM reviews WHERE reviewedemail = "'.$info['email'].'"';

	//execute the query and display it
	$result = $db->query($query);
	while($row = $result->fetch_assoc()) {
		$uq = 'SELECT first_names, last_name FROM users WHERE email = "' . $row["revieweremail"] . '"';
		$uresult = $db->query($uq);
		$urow = $uresult->fetch_assoc();

		echo '<h4>' . $row["rating"] . '/10</h4>';
		echo '<p>' . $row["review"] . '</p>';
		echo "By: " . $urow["first_names"] . " " . $urow["last_name"] . " " ;
		echo $row["createdat"] . "</div></div>";
	}

	?>

<a  href="makereview.php"><h6 class="review">Make a Review</h6></a>
</body>



</html>