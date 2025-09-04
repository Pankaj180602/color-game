<?php
$host = "localhost";
$user = "root";
$password = ""; // default password for XAMPP is empty
$database = "color_game";

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
