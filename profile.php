<?php
	session_start();
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}
?>

<!DOCTYPE html>


<html>
<head>
<head>
	<title>TaSC Profile</title>
	<link href="Resources/style.css" rel="stylesheet" type="text/css"/>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

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
</head>


<body>
	<ul id="slide-out" class="sidenav">	
		<li><a class="nav-item" href="forum.php">Discussion Forum </a></li>
		<li><a class="nav-item" href="find.php" name="findstudent" value="Find a Student">Find a Student</a></li>
		<li><a class="nav-item" href="find.php" name="findtutor" value="Find a Tutor">Find a Tutor</a></li>
		<li><a class="nav-item" href="addsubject.php">Add a subject to tutor</a></li>
		<li><a class="nav-item" href="addsubject.php">Add a subject to find help in</a></li>
		<li class="bottom"><a id="bottom" href="index.php"> Logout </a></li>
	</ul>
  <div class="jumbotron">
		<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
		<div>
		  <h1 class="title">Tutor and Student Connection</h1>
		</div>
  </div>
  
<!--
	<div class="sidebar">
		<a id="navlink" href="forum.php"> Discussion Forum </a>
		<a id="logout" href="index.php"> Logout </a>
		<form action="find.php" method="post">
			<input type="submit" name="findstudent" value="Find a Student"/>
		</form>
    
		<form action="find.php" method="post">
			<input type="submit" name="findtutor" value="Find a Tutor"/>
		</form>
    
		<form action="addsubject.php" method="post">
			<input type="submit" name="tutorsubject" value="Add a subject to tutor"/>
		</form>
    
		<form action="addsubject.php" method="post">
			<input type="submit" name="studentsubject" value="Add a subject to find help in"/>
		</form>
	</div>
  
--><div class="wrapperProfile">
    <div class="connections">
    	<div class="box">
        <h2> Personal Info </h2>
        <?php

            $dbOk = false;

            //connects to database 
            @ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

            //error message if connection to database fails
            if ($db->connect_error) {
                echo '<div class="messages">Could not connect to the database. Error: ';
                echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
            } else {
                $dbOk = true; 
            }

            //pulls the user id from the session and accesses user table 
            //to find if the user is a tutor or a student 
			$userid = $_SESSION["userid"]; //gets user id from session
            $namequery = "SELECT first_names,last_name from users where userid='". $userid ."'";
            $nameCall = $db->query($namequery);
            $resCall = $nameCall->fetch_assoc();

            //if the user is a tutor, find all connections where the tutorid
            //matches the userid to output connections
            echo "<h3>".$resCall["first_names"]." ".$resCall["last_name"]."</h3>";
			

			/*************************************** RANKING SYSTEM ***************************************/
			
			
			
			//						                Calculating Percentile
			
			//getting single users Score
			$userid = $_SESSION["userid"]; //gets user id from session
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
			//debugging
			//echo"<p>".$summation."<p>";

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


			//DISPLAYING RANK
			echo "<p>TaSC Rank: ".$userRank."</p>"; 
			/*********************************** END OF RANKING SYSTEM ***********************************/
			
			//Student description

			//Fetching Description (HAVE TO CHANGE TABLE 'description' IS A RESERVED WORD CANNOT QUEREY IT)
			$descQ = "SELECT * from users where userid='" . $userid . "'";
			$decCall = $db->query($descQ);
			$des = $decCall->fetch_assoc();

			echo "<p>About Me: ".$des['description']."</p>";
        ?>
        </div>
    </div>
	<div class="connections">
        <h2> Classes </h2>
        <?php

            $dbOk = false;

            //connects to database 
            @ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

            //error message if connection to database fails
            if ($db->connect_error) {
                echo '<div class="messages">Could not connect to the database. Error: ';
                echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
            } else {
                $dbOk = true; 
            }
			$studentQ='SELECT `course` FROM student_subjects WHERE userid='.$_SESSION['userid'].';';
			$studentCall = $db->query($studentQ);
			echo "<h3>Student Classes:</h3>";
			while($row=$studentCall->fetch_assoc()){
				echo "<p>". $row["course"]. "</p>";
			}

			$tutorQ='SELECT `course` FROM tutor_subjects WHERE userid='.$_SESSION['userid'].';';
			$tutorCall = $db->query($tutorQ);
			echo "<h3>Tutor Classes:</h3>";
			while($row=$tutorCall->fetch_assoc()){
				echo "<p>". $row["course"]. "</p>";
			}
            
            
			
        ?>
        
    </div>
	<div class="connections">
		<h2> Connections </h2>

			<?php

				$dbOk = false;

				//connects to database 
				@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

				//error message if connection to database fails
				if ($db->connect_error) {
					echo '<div class="messages">Could not connect to the database. Error: ';
					echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
				} else {
					$dbOk = true; 
				}

				//pulls the user id from the session and accesses user table 
				//to find if the user is a tutor or a student 
				$userid = $_SESSION["userid"]; //gets user id from session
				$tutorquery = "SELECT * from users where userid='". $userid ."'";
				$istutor = $db->query($tutorquery);
				$tutorrecord = $istutor->fetch_assoc();

				$query = "SELECT * from connections where tutorid='" . $userid . "'";
				$result = $db->query($query);
				$numRecords = $result->num_rows;
                  echo '<h4>Who I tutor:</h4>';
				//for every connection the tutor has, print out the information
				//of the other user
				for ($i=0; $i < $numRecords; $i++) {
					$record = $result->fetch_assoc();
					$sid = $record["studentid"];

					$infoQuery = "SELECT * from users where userid='" . $sid . "'";
					$infoResult = $db->query($infoQuery);
					$info = $infoResult->fetch_assoc();

					echo "<h3> " . htmlspecialchars($info["first_names"]) . " ";
					echo htmlspecialchars($info["last_name"]) . "</h3>";
					echo '<p> Email: ' . $info["email"] . '</p>';
					echo "<p> Connected Course(s): ";

					//If the user is in multiple subjects, output all of the subjects that 
					//the user is in
					$subjquery = "SELECT subject from connections where studentid=" . $sid . " and tutorid=".$_SESSION['userid'];
					$subjresults = $db->query($subjquery);
					$numSubjects = $subjresults->num_rows;

					for ($j=0; $j < ($numSubjects-1); $j++) {
						$subj = $subjresults->fetch_assoc();
						echo $subj["subject"] . ", ";
					}
					$subj = $subjresults->fetch_assoc();
					echo $subj["subject"] . "</p>";

					echo "<p> Year: " . $info["year"] . "</p>";
					echo "<p> " . $info["description"] . "</p>";
					echo '<form name="viewtutor" action="viewprofile.php" method="post">';
					echo '<input type="submit" name="'.$info['userid'] .'" value="View Profile"/>';
					echo '</form>';
				}


				//uses the same method as above but matches userid to studentid instead of tutorid
				$query = "SELECT * from connections where studentid='" . $userid . "'";
				$result = $db->query($query);
				$numRecords = $result->num_rows;
                  echo "<h4>Tutors:</h4>";
				//prints out info for each connection made by user to tutors
				for ($i=0; $i < $numRecords; $i++) {
					$record = $result->fetch_assoc();
					$tid = $record["tutorid"];

					$infoQuery = "SELECT * from users where userid='" . $tid . "'";
					$infoResult = $db->query($infoQuery);
					$info = $infoResult->fetch_assoc();

					echo "<h3> " . htmlspecialchars($info["first_names"]) . " ";
					echo htmlspecialchars($info["last_name"]) . "</h3>";
					echo '<p> Email: ' . $info["email"] . '</p>';

					echo "<p> Connected Course(s): ";

					$subjquery = "SELECT subject from connections where tutorid=" . $tid . " and studentid=".$_SESSION['userid'];
					$subjresults = $db->query($subjquery);
					$numSubjects = $subjresults->num_rows;

					for ($j=0; $j < ($numSubjects-1); $j++) {
						$subj = $subjresults->fetch_assoc();
						echo $subj["subject"] . ", ";
					}
					$subj = $subjresults->fetch_assoc();
					echo $subj["subject"] . "</p>";

					echo "<p> Year: " . $info["year"] . "</p>";
					echo "<p> " . $info["description"] . "</p>";
					echo '<form name="viewstudent" action="viewprofile.php" method="post">';
					echo '<input type="submit" name="'.$info['userid'] .'" value="View Profile" id="viewprofile"/>';
					echo '</form>';
        }


			?>

		</div>
	</div>

</body>


</html>