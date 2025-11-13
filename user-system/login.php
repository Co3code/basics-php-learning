<?php
    /*
 * FILE: login.php
 * PURPOSE: User login form
 * AUTHOR: Co3code with Seek
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .form-group { margin: 15px 0; }
        input[type="text"], input[type="password"] {
            width: 300px; padding: 8px; margin: 5px 0;
        }
    </style>
</head>
<body>
    <h2>🔐 User Login</h2>

    <form method="POST" action="process_login.php">
        <div class="form-group">
            <label>Username or Email:</label><br>
            <input type="text" name="login" required>
        </div>

        <div class="form-group">
            <label>Password:</label><br>
            <input type="password" name="password" required>
        </div>

        <input type="submit" value="🚀 Login">
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
