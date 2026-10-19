<?php
// Simple procedural MySQL connection using mysqli
$host = 'localhost';
$user = 's68160214';
$pass = 'y5UpFYz9';
$dbname = 's68160214';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
