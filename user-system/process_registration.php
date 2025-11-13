<?php
require_once 'config.php';
$username         = trim($_POST['username']);
$email            = trim($_POST['email']);
$password         = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// basic validation
if ($password !== $confirm_password) {
    die("password do not match!");
}
if (strlen($password) < 6) {
    die("password must be at least 6 characters long!");
}
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

//insert user into database

$sql  = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $email, $hashed_password);

if ($stmt->execute()) {
    echo "registration successful! <a href='login.php'>login here</a>";
} else {
    echo "  error: " . $conn->error;
}
$stmt->close();
$conn->close();
