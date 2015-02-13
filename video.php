<?php

include 'pw.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "smidtj-db", $pw, "smidtj-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else {
	echo "Connection worked - Welcome to the Video Store!!<br>";
}
if (!$mysqli->query("SHOW TABLES LIKE 'videoStore'")){
	if (!$mysqli->query("CREATE TABLE videoStore(
		id INT AUTO_INCREMENT PRIMARY KEY, 
		name VARCHAR(255) NOT NULL UNIQUE,
		category VARCHAR(255),
		length INT,
		rented INT DEFAULT 1)")) {
    		echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
}

if(isset($_POST['deleteAll'])){
	if(!$mysqli->query("DROP TABLE IF EXISTS videoStore")){
		echo "Table deletion Failed";
	}
	echo "Videos Deleted!!";
	if (!$mysqli->query("CREATE TABLE videoStore(
		id INT AUTO_INCREMENT PRIMARY KEY, 
		name VARCHAR(255) NOT NULL UNIQUE,
		category VARCHAR(255),
		length INT,
		rented INT DEFAULT 1)")) {
    		echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
}
if(isset($_POST['name'])){
	$name = $_POST['name'];
	$category = $_POST['category'];
	$length = (int)($_POST['length']);
	if ($category == ""){
		$category = "No Category";
	}
	if($length < 0){
		echo "The video length must be in minutes, cannot not contain characters, and must be positive.";
	} else {
		if (!($stmt = $mysqli->prepare("INSERT INTO videoStore(name, category, length) VALUES (?, ?, ?)"))) {
   			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}	

		if (!$stmt->bind_param("ssi", $name, $category, $length)) {
    		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}

		if (!$stmt->execute()) {
    		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		$stmt->close();
	}
}

$res = $mysqli->query("SELECT id, name, category, length, rented FROM videoStore");
$info = $res->fetch_all();
echo "<br>Total Number of videos in Store: ".count($info);


	if(isset($_POST['deleteid'])){
		$kaboom = explode("Delete ID: ", $_POST['deleteid']);
		if(!$mysqli->query("DELETE FROM videoStore WHERE id = ".$kaboom[1])){
			echo "Row deletion Failed";
		}
		$res = $mysqli->query("SELECT id, name, category, length, rented FROM videoStore");
		$info = $res->fetch_all();
		echo "<br>Total Number of videos in Store after deletion: ".count($info);
	}
 
	if(isset($_POST['checkoutid'])){
		$kaboom = explode("Check Out ID: ", $_POST['checkoutid']);
		if(!$mysqli->query("UPDATE videoStore SET rented = 0 WHERE id = ".$kaboom[1])){
			echo "Row alteration Failed";
		}
		$res = $mysqli->query("SELECT id, name, category, length, rented FROM videoStore");
		$info = $res->fetch_all();
		echo "<br>Checked out a movie!!";
	}

	if(isset($_POST['returnid'])){
		$kaboom = explode("Return ID: ", $_POST['returnid']);
		if(!$mysqli->query("UPDATE videoStore SET rented = 1 WHERE id = ".$kaboom[1])){
			echo "Row alteration Failed";
		}
		$res = $mysqli->query("SELECT id, name, category, length, rented FROM videoStore");
		$info = $res->fetch_all();
		echo "<br>Returned a movie!!";
	}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Assignment 4 Part 2</title>
  </head>
  <body>
  	<form action="video.php" method="post">
  		Name: <input type="text" name="name" required>
  		Category: <input type="text" name="category">
  		Length: <input type="text" name="length">
  		<input type="submit" value="Add Video">
  	</form>
  	<form action='video.php' method="post">
  		<input type="submit" value="Delete All Videos" name="deleteAll">
  	</form>
  	<?php
  	$dropDown = array();
  	echo '<form action="video.php" method="post"><select name="genre"><option value="All Videos">All Videos</option>';
  	for($i=0; $i<count($info); $i++){
  		if (!(in_array($info[$i][2], $dropDown, true))){
  			echo '<option value="'.$info[$i][2].'">'.$info[$i][2].'</option>';
  			$dropDown[]=$info[$i][2];
  		}
  	}
  	echo '</select><input type="submit"></input></form>';
  	echo "<table>";
  	echo "<tr><th>Video ID</th><th>Name</th><th>Category</th><th>length</th><th>Availability</th></tr>";
  	if ($_POST['genre'] && ($_POST['genre'] != 'All Videos')){
  		for($i=0; $i<count($info); $i++){
  			if($info[$i][2] == $_POST['genre']){
  				echo '<tr><td>'.$info[$i][0].'</td><td>'.$info[$i][1].'</td><td>'.$info[$i][2].'</td><td>'.$info[$i][3].'</td>';
  				if($info[$i][4]){
  					echo '<td>Available</td><td><form action="video.php" method="post">
  						<input type="submit" value="Check Out ID: '.$info[$i][0].'" name="checkoutid"></input></form>
  						</td>';
	  			} else {
  					echo '<td>Checked Out</td><td><form action="video.php" method="post">
  						<input type="submit" value="Return ID: '.$info[$i][0].'" name="returnid"></input></form>
  						</td>';
	  			}
  				echo '<td>
  					<form action="video.php" method="post">
	  				<input type="submit" value="Delete ID: '.$info[$i][0].'" name="deleteid"></input></form>
  					</td></tr>';
  			}
	  	}
	} else{
  		for($i=0; $i<count($info); $i++){
  			echo '<tr><td>'.$info[$i][0].'</td><td>'.$info[$i][1].'</td><td>'.$info[$i][2].'</td><td>'.$info[$i][3].'</td>';
  			if($info[$i][4]){
  				echo '<td>Available</td><td><form action="video.php" method="post">
  					<input type="submit" value="Check Out ID: '.$info[$i][0].'" name="checkoutid"></input></form>
  					</td>';
  			} else {
  				echo '<td>Checked Out</td><td><form action="video.php" method="post">
  					<input type="submit" value="Return ID: '.$info[$i][0].'" name="returnid"></input></form>
  					</td>';
  			}
  			echo '<td>
  				<form action="video.php" method="post">
	  			<input type="submit" value="Delete ID: '.$info[$i][0].'" name="deleteid"></input></form>
  				</td></tr>';
  		}
    }