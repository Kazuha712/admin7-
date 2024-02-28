<?php

$sname = "localhost";
$uname = "root";
$password = ""; 
$db_name = "ipt101";

// Create connection
$conn = new mysqli($sname, $uname, $password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE TABLE IF NOT EXISTS user (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(45) NOT NULL,
    password VARCHAR(45) NOT NULL,
    first_name VARCHAR(45) NOT NULL,
    middle_name VARCHAR(45),
    last_name VARCHAR(45) NOT NULL,
    email VARCHAR(45) UNIQUE NOT NULL,
    status VARCHAR(45),
    active VARCHAR(45)
)";

if ($conn->query($sql) === false) {
    echo "Error creating table: " . $conn->error;
}

?>
