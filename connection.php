<?php
$servername = "localhost";
$username = "photon";
$password = "12345678";
$dbname = "photon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}
?>