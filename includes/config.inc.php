<?php
session_start();

// Database settings
$servername = "localhost";
$username = "root";
$password = "";
$database = "web";

// Account settings (NOTE: Never change auth or token!!)
$salt = "ABCDEFG";
$peper = "ABCDEFG";

// Registering options
$regrank = 0;    // User rank after registering
$regactive = 1;  // User active after registering  (0 = not active / 1 = active)

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
