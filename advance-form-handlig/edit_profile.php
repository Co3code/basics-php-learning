<?php

    if (! isset($_SESSION['user_id'])) {
        header("location: login.php");
        exit();
    }

    require_once 'db_config.php';

    $user_id = $_SESSION['user_id'];
    $success = '';
    $error   = '';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <title>Document</title>
</head>
 <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 20px; }
        .edit-container { max-width: 500px; margin: 30px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { color: #333; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="number"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-save { background: #28a745; color: white; }
        .btn-cancel { background: #6c757d; color: white; }
        .success { background: #d4edda; color: #155724; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="edit-container">
        <h2>Edit Profile Information</h2>

        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Full Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($current_user['name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email Address:</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($current_user['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Age:</label>
                <input type="number" name="age" value="<?php echo $current_user['age']; ?>" min="1" max="120" required>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-save">Save Changes</button>
                <a href="profile.php" class="btn btn-cancel">Cancel</a>
            </div>
        </form>
    </div>

</body>
</html>
