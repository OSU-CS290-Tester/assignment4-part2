<?php
ini_set('display_errors', "On");
include 'pw.php';
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "smidtj-db", $pw, "smidtj-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
} else {
	echo "Connection worked!<br>";
}

?>