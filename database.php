<?php
$servername = "db"; // This should match the service name in docker-compose.yml
$username = "root";
$password = "password"; // Ensure this matches your docker-compose.yml
$dbname = "ks";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select database
if (!$conn->select_db($dbname)) {
    die("Database selection failed: " . $conn->error);
}


 
?>
