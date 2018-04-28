<!DOCTYPE html>
<?php 
  session_start();
?>
<html>
<head>
	<title>TaSC</title>
	<link href="Resources/forum.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="Resources/jquery-1.4.3.min.js"></script>
    <script type="text/javascript" src="Scripts/forum.js"></script>
</head>


<body>
	<h1> 
		<div id="header"> Tutor and Student Connection 
		</div>
	</h1>

	<div class="sidenav">
	  <a id="navlink" href="connect.html">Connect Page</a>
	  <a id ="post" href="makepost.html">New Post</a>
	  <!-- add php to pull classes that user is in ($select * from courses where userid = $_SESSION['userid'])-->
	  <?php
	  	@ $db =  new mysqli('localhost', 'root', 'Mets2014', 'TaSC');
	  	$q="select course from user_subjects where userid=".'1';
	  	$prepCourses=$db->query($q);
		$numRecords = $prepCourses->num_rows;
		for($i=0; $i<$numRecords; $i++){
			$course=$prepCourses->fetch_assoc();
			echo "<a href='#selected'>".$course['course']."</a>";
		}
	  ?>
	  <a id="logout" href="index.html"> Logout </a>

	</div>
	<div id="discussion">
		<?php
			$dbOk = false;

			@ $db =  new mysqli('localhost', 'root', 'Mets2014', 'TaSC');

			if ($db->connect_error) {
				echo '<div class="messages">Could not connect to the database. Error: ';
				echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
			} else {
				$dbOk = true; 
			}
			if($dbOk){
				$query = 'select * from forum where courseid=1 order by postdate DESC';
    			$result = $db->query($query);
    			$numRecords = $result->num_rows;
    			for($i=0; $i <$numRecords; $i++) {
			    	$post = $result->fetch_assoc();
					echo "<ul>";
		   			echo '<li id="discussion">' . $post['topic'] . '</li>';
		    		echo '<li class="internalDisc">' . $post['post']. '</li>';
		    		$q='select first_names from users where userid='. $post['userid'];
		    		$fn=$db->query($q);
		    		$fname=$fn->fetch_assoc();
		    		echo '<li class="author">' . "Posted by ".$fname['first_names'] ." ".$post['postdate'].'</li>';
		    		echo "</ul>";
			    }
			}
		?>


	</div>



</html>