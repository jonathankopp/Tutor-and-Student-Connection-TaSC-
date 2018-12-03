<?php
	session_start();
	if (!isset($_SESSION['userid'])) {
		header('Location: index.php');
	}

	@ $db =  new mysqli('localhost', 'root', 'password', 'TaSC');

	if (isset($_POST['tutorsubject'])) {
		$_SESSION['s_table'] = "tutor_subjects";
	}
	if (isset($_POST['studentsubject'])) {
		$_SESSION['s_table'] = "student_subjects";
	}

	$table = $_SESSION['s_table'];

	if (isset($_POST['addsubject'])) {
		$inquery = 'INSERT INTO ' . $table . ' (`userid`, `course`) VALUES (?,?)';
		$stmt = $db->prepare($inquery);
		$stmt->bind_param($_SESSION['userid'], $_POST['addsubject']);
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

	Add a subject:
	<form action="addsubject.php" method="post">
		<select name="addsubject">
			<?php
			$subquery = 'SELECT course FROM subject WHERE course NOT IN (';
			$subquery .= 'SELECT course FROM ' . $table . ' WHERE userid = ' . $_SESSION['userid'] . ');';
			echo $subquery;
			$result = $db->query($subquery);
			while($row=$result->fetch_assoc()) {
				echo '<option value="' . $row['course'] . '">'.$row['course'] . ' </option>';
			}
			?>
		</select>
		<input type="submit" name="add" value="Add Subject"/>
	</form>
</body>


</html>