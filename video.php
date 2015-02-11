<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Assignment 4 Part 2</title>
  </head>
  <body>
  	<form action="addVideo.php" method="post">
  		Name: <input type="text" name="name">
  		Category: <input type="text" name="category">
  		Length: <input type="text" name="length">
  		<input type="submit" value ="Add Video">
  	</form>


<?php
ini_set('display_errors', "On");
include 'pw.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "smidtj-db", $pw, "smidtj-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else {
	echo "Connection worked!<br>";
}

/* Non-prepared statement */
if (!$mysqli->query("DROP TABLE IF EXISTS videoStore") || !$mysqli->query("CREATE TABLE videoStore(
	id INT AUTO_INCREMENT PRIMARY KEY, 
	name VARCHAR(255) NOT NULL UNIQUE,
	category VARCHAR(255),
	length INT,
	rented INT DEFAULT 1)")) {
    echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 1: prepare 
if (!($stmt = $mysqli->prepare("INSERT INTO test(id) VALUES (?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

/* Prepared statement, stage 2: bind and execute 
$id = 1;
if (!$stmt->bind_param("i", $id)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

for ($id = 2; $id < 5; $id++) {
    if (!$stmt->execute()) {
        echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
    }
}
*/
?>