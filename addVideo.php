<?php
/*
if (!($stmt = $mysqli->prepare("INSERT INTO test(id) VALUES (?)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
*/
$name = $_POST['name'];
$category = $_POST['category'];
$length = $_POST['length'];
echo $name, $category, $length; 

if(!$mysqli->query("INSERT INTO videoStore(name, category, length) VALUES ($name, $category, $length)")){
	echo "Couldn't insert"; 
}
if (!($stmt = $mysqli->prepare("INSERT INTO videoStore(name, category, length) VALUES ($name, $category, $length)"))) {
    echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if (!$stmt->bind_param("s", $name)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->bind_param("s", $category)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->bind_param("i", $length)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}

if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}

$stmt->close();
?>