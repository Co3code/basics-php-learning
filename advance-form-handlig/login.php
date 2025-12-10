
    <?php // Start session to track logged-in users
        session_start();

        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: dashboard.php");
            exit();
        }

        // Include database connection
        require_once 'db_config.php';

        // Initialize error/success messages
        $error   = '';
        $success = '';

        // Process login form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email    = trim($_POST['email']);
            $password = $_POST['password'];

            // Basic validation
            if (empty($email) || empty($password)) {
                $error = "Please enter both email and password.";
            } else {
                // Find user by email
                $sql  = "SELECT id, name, email, password FROM users WHERE email = ?";
                $stmt = $conn->prepare($sql);

                if ($stmt) {
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 1) {
                        $user = $result->fetch_assoc();

                        // Verify password
                        if (password_verify($password, $user['password'])) {
                            // Login successful - set session
                            $_SESSION['user_id']    = $user['id'];
                            $_SESSION['user_name']  = $user['name'];
                            $_SESSION['user_email'] = $user['email'];

                            // Redirect to dashboard
                            header("Location: dashboard.php");
                            exit();
                        } else {
                            $error = "Incorrect password.";
                        }
                    } else {
                        $error = "No account found with that email.";
                    }

                    $stmt->close();
                } else {
                    $error = "Database error. Please try again.";
                }
            }
        }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Document</title>
      <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 20px; }
        .login-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 400px; margin: 50px auto; }
        h2 { text-align: center; color: #333; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .submit-btn { width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .submit-btn:hover { background: #0056b3; }
        .register-link { text-align: center; margin-top: 20px; }
    </style>
    </head>
    <body>
      <div class="login-box">
         <h2>User Login</h2>
         <?php if (! empty($error)): ?>
            <div class="error"><?php echo $success; ?></div>
         <?php endif; ?>

         <?php if (! empty($success)): ?>
            <div class="success"><?echo $success;?></div>
         <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="submit-btn">Login</button>
        </form>
             <div class="register-link">
            <p>Don't have an account? <a href="advance_forms.php">Register here</a></p>
        </div>




      </div>

    </body>
    </html>