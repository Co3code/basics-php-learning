<?php
/*
 * FILE: process_login.php
 * PURPOSE: Handle user login with password verification
 * AUTHOR: Co3code with Seek
 */

// Start session and include database
session_start();
require_once 'config.php';

// Get form data
$login    = trim($_POST['login']);
$password = $_POST['password'];

// Find user by username OR email
$sql  = "SELECT * FROM users WHERE username = ? OR email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $login, $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $user['password'])) {
        // Login successful - start session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        echo "🎉 Login successful! Welcome, " . $user['username'] . "!";
    } else {
        echo "❌ Invalid password!";
    }
} else {
    echo "❌ User not found!";
}

$stmt->close();
$conn->close();
