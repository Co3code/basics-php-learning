<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
</head>
<style>
    body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .password-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn {
            padding: 10px 1px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
        }
        .btn-change {
            background-color: #28a745;
            color: white;
        }
        .btn-change:hover {
            background-color: #218838;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            display: block;
        }
        .password-rules {
            background-color: #e8f4f8;
            padding: 10px;
            border-radius: 4px;
            margin-top: 20px;
            font-size: 14px;
        }
        .password-rules ul {
            margin: 5px 0;
            padding-left: 20px;
        }
</style>
<body>
    <div>
        <div class="password-container">
            <?php if (! empty($success)): ?>
                <div class="success-message">
                    <?php echo $success; ?>
                </div>
                <?php endif; ?>

        <?php if (! empty($error)): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>



        <form method="POST">
            <div class="form-group">
            <label for="new_password">New Password:</label>
            <input type="password" name="naw_password" id="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-change">Change Password</button>
                <a href="profile.php" class="btn btn-change">cancel</a>
            </div>
        </form>
        <div class="password-rules">
            <strong>Password Requirements:</strong>
            <ul>
                <li>At lease 8 characters long</li>
                <li> different from currrnt password</li>
                <li>Confirm Password must match </li>
            </ul>


        </div>


      </div>

</body>
</html>
