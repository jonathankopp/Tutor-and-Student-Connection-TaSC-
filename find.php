<?php 
	session_start();
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}
	@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');
	if (isset($_POST['findstudent'])) {
		$_SESSION['s_table'] = "tutor_subjects";
		$_SESSION['tutor'] = 1;
	}
	if (isset($_POST['findtutor'])) {
		$_SESSION['s_table'] = "student_subjects";
		$_SESSION['tutor'] = 0;
	}
	$table = $_SESSION["s_table"];
?>

<!DOCTYPE html>


<html>
<head>
	<title>TaSC</title>
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
			var instances = M.Sidenav.init(elems);
		  });
		  // Initialize collapsible (uncomment the lines below if you use the dropdown variation)
		  // var collapsibleElem = document.querySelector('.collapsible');
		  // var collapsibleInstance = M.Collapsible.init(collapsibleElem, options);
		  // Or with jQuery
		  $(document).ready(function(){
        $('.sidenav').sidenav();
//        $('select').material_select();
		  });
		</script>

</head>


	<body>
	<ul id="slide-out" class="sidenav">	
		<li><a class="nav-item" href="forum.php">Discussion Forum </a></li>
		<li><a class="nav-item" href="profile.php">My Profile</a></li>
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
			<a href="profile.php"> Back to Profile </a>
			<a id="logout" href="index.php"> Logout </a>
		</div>
-->
    <div class="findPadding">
		<div class="subject">
			<form name="search" action="find.php" method="post">
				<label class="field">Choose a Subject:</label>
				<select class="browser-default" name="subject">
					<?php
					$subjectquery = 'SELECT course FROM ' . $table . ' WHERE userid="' . $_SESSION['userid'] . '"';
					$result = $db->query($subjectquery);
					while($subjects = $result->fetch_assoc()) {
						if (isset($_GET['search']) && $_GET['subject'] == $subjects['course']) {
							echo '<option value="' . $subjects['course'] . '"selected>' . $subjects['course'] . '</option?>';
						}else {
							echo '<option value="' . $subjects['course'] . '">' . $subjects['course'] . '</option?>';
						}
					}
					?>
				</select>
				<input type="submit" value="Search" id="search" name="search"/>	
			</form>
		</div>

		<div class="person">

			<?php
			if (isset($_POST['subject'])) {
				$_SESSION['searchSubject'] = $_POST['subject'];
				$opptable = "";
				if ($table == "tutor_subjects") {
					$opptable = "student_subjects";
				} else {
					$opptable = "tutor_subjects";
				}
				$matchquery = 'SELECT userid FROM ' . $opptable . ' WHERE course = "' . $_POST['subject'] . '"';
				$result = $db->query($matchquery);
				if ($result->num_rows == 0) {
					echo '<p id="nomatch"> No Matches Found </p>';
				} else {
					echo '<form class="makeconnection" action="viewprofile.php" method="post" name="connect">';
					while($row = $result->fetch_assoc()) {
							//selects the info for that user from users table to output
							$infoQuery = "SELECT userid, first_names, last_name, score from users where userid='" . $row['userid'] . "'";
							$infoResult = $db->query($infoQuery);
							$info = $infoResult->fetch_assoc();
							$name = $info["first_names"] . ' ' . $info["last_name"] . ': ' . $info['score'];
							//makes a button for each user 
							echo '<input type="submit" value="' . $name . '" id="' . $info['userid'] . '" name="' . $info['userid'] . '"/>';
							
					}
					echo '</form>';
				}
			}
			?>


		</div>
</div>
	</body>


</html>