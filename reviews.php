<?php
	session_start();
?>
<html>
	<head>
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
		<li><a class="nav-item" href="find.php">Make a Connection </a></li>
		<li><a class="nav-item" href="forum.php"> Discussion Forum </a></li>
		<li><a class="nav-item" href="makereview.php">Make a Review</a></li>
		<li><a class="nav-item" href="profile.php">My Profile</a></li>
		<li class="bottom"><a id="logout" href="index.php">Logout</a></li>
	</ul>
	 <div class="jumbotron">
		<a href="#" data-target="slide-out" class="sidenav-trigger menu"><i class="small material-icons menu">menu</i></a>
		<div>
		  <h1 class="title">Tutor and Student Connection</h1>
		</div>
	  </div>

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
