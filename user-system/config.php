<?php
$servername = "localhost";
$username   = "root";           // default xampp username
$password   = "";               // default xampp password (empty)
$dbname     = "contact_system"; // my database name

//creating connection
$conn = new mysqli($servername, $username, $password, $dbname);

//check connection
if ($conn->connect_error) {
    die("connection failed" . $conn->connect_error);

}

echo "database connected succesfully";
