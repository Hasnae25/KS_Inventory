<?php
$servername = "db"; // This should match the service name in docker-compose.yml
$username = "root";
$password = "rootpassword"; // Ensure this matches your docker-compose.yml
$dbname = "ks";

// Create connection with password
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Select database (optional, since you're already passing it during connection)
if (!$conn->select_db($dbname)) {
    die("Database selection failed: " . $conn->error);
}
?>
