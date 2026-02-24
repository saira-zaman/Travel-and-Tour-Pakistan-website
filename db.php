<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "travel_pakistan";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed");
}
?>
