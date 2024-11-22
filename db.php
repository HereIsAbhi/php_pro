<?php
$servername = "localhost";
$username = "root";
$password = "Abhi@2012";
$dbname = "healthTech_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
