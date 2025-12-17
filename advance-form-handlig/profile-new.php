<?php
    
 
    // Start session and check login
    session_start();
    if (! isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Include database connection
    require_once 'db_config.php';

    // Get current user data
    $user_id = $_SESSION['user_id'];
                                                                                       // old $sql = "SELECT id, name, email, age, created_at FROM users WHERE id = ?";
    $sql  = "SELECT id, name, email, age, avatar, created_at FROM users WHERE id = ?"; // new
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; margin: 0; padding: 20px; }
        .profile-container { max-width: 800px; margin: 30px auto; background: white; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); overflow: hidden; }
        .profile-header { background: #007bff; color: white; padding: 30px; text-align: center; }
        .profile-content { padding: 30px; }
        .info-card { background: #f8f9fa; border-left: 4px solid #007bff; padding: 15px; margin-bottom: 15px; border-radius: 4px; }
        .action-buttons { display: flex; gap: 10px; margin-top: 30px; }
        .btn { padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-edit { background: #28a745; color: white; }
        .btn-password { background: #ffc107; color: #212529; }
        .btn-logout { background: #dc3545; color: white; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <h1>User Profile</h1>
            <p>Manage your account information</p>
        </div>

        <div class="profile-content">
            <h2>Personal Information</h2>

            <!-- AVATAR SECTION -->
            <div style="text-align: center; margin-bottom: 30px;">
                <?php if (! empty($user['avatar']) && file_exists($user['avatar'])): ?>
                    <img src="<?php echo $user['avatar']; ?>"
                         alt="Profile Picture"
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff;">
                <?php else: ?>
                    <div style="width: 150px; height: 150px; background: #ddd; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: #666;">
                        No Photo
                    </div>
                <?php endif; ?>
                <p style="margin-top: 10px;"><a href="upload_avatar.php">Upload/Change Photo</a></p>
            </div>
            <!-- END AVATAR SECTION -->

            <div class="info-card">
                <strong>User ID:</strong>                                                                                   <?php echo $user['id']; ?>
            </div>

            <div class="info-card">
                <strong>Full Name:</strong>                                                                                       <?php echo htmlspecialchars($user['name']); ?>
            </div>

            <div class="info-card">
                <strong>Email Address:</strong>                                                                                               <?php echo htmlspecialchars($user['email']); ?>
            </div>

            <div class="info-card">
                <strong>Age:</strong>                                                                           <?php echo $user['age']; ?>
            </div>

            <div class="info-card">
                <strong>Member Since:</strong>                                                                                             <?php echo date('F j, Y', strtotime($user['created_at'])); ?>
            </div>

            <div class="action-buttons">
                <a href="edit_profile.php" class="btn btn-edit">Edit Profile</a>
                <a href="change_password.php" class="btn btn-password">Change Password</a>
                <a href="dashboard.php" class="btn" style="background: #6c757d; color: white;">Back to Dashboard</a>
                <a href="logout.php" class="btn btn-logout">Logout</a>
            </div>
        </div>
    </div>
</body>
</html>