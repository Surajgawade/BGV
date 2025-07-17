<?php

$servername = $_POST['servername'];
$username = $_POST['username'];
$password = $_POST['password'];
$database = $_POST['database_name'];
// Create connection
$conn = mysqli_connect($servername, $username, $password,$database);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
else{
  echo "Connected <br>";
}

$sql = $_POST['query'];

if (mysqli_query($conn, $sql)) {
  echo "Record updated successfully";
} else {
  echo "Error updating record: " . mysqli_error($conn);
}

mysqli_close($conn);
?>
