<?php

    session_start();
    if (! isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    require_once 'db_config.php';

    $user_id = $_SESSION['user_id'];
    $success = '';
    $error   = '';

    // Create uploads directory if it doesn't exist
    $upload_dir = 'uploads/avatars/';
    if (! file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Process file upload
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['avatar'])) {
        $file = $_FILES['avatar'];

        // File properties
        $file_name  = $file['name'];
        $file_tmp   = $file['tmp_name'];
        $file_size  = $file['size'];
        $file_error = $file['error'];

        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // Allowed file types
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        // Validation
        if ($file_error !== UPLOAD_ERR_OK) {
            switch ($file_error) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $error = "File size too large. Maximum 2MB allowed.";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $error = "File upload was incomplete.";
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $error = "No file was selected.";
                    break;
                default:
                    $error = "File upload error occurred.";
            }
        } elseif (! in_array($file_ext, $allowed_ext)) {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        } elseif ($file_size > 2097152) { // 2MB limit
            $error = "File size must be less than 2MB.";
        } else {
            // Generate unique filename
            $new_filename     = "user_" . $user_id . "_" . time() . "." . $file_ext;
            $file_destination = $upload_dir . $new_filename;

            // Move uploaded file
            if (move_uploaded_file($file_tmp, $file_destination)) {
                // Update database with avatar path
                $sql  = "UPDATE users SET avatar = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $file_destination, $user_id);

                if ($stmt->execute()) {
                    $success = "Profile picture uploaded successfully!";
                } else {
                    $error = "Database update failed: " . $conn->error;
                }
                $stmt->close();
            } else {
                $error = "Failed to save uploaded file.";
            }
        }
    }

    // Get current avatar if exists
    $current_avatar = '';
    $sql            = "SELECT avatar FROM users WHERE id = ?";
    $stmt           = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc() && ! empty($row['avatar'])) {
        $current_avatar = $row['avatar'];
    }
    $stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Picture</title>
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
        .upload-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        .current-avatar {
            margin-bottom: 30px;
        }
        .avatar-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #007bff;
            margin-bottom: 15px;
        }
        .no-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            color: #666;
            font-size: 14px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 2px dashed #ddd;
            border-radius: 4px;
            background: #f9f9f9;
            cursor: pointer;
        }
        .file-info {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-upload {
            background-color: #007bff;
            color: white;
        }
        .btn-upload:hover {
            background-color: #0056b3;
        }
        .btn-cancel {
            background-color: #6c757d;
            color: white;
        }
        .requirements {
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: left;
            font-size: 14px;
        }
        .requirements ul {
            margin: 5px 0;
            padding-left: 20px;
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h2>Upload Profile Picture</h2>

        <div class="current-avatar">
            <h3>Current Picture:</h3>
            <?php if (! empty($current_avatar) && file_exists($current_avatar)): ?>
                <img src="<?php echo $current_avatar; ?>" alt="Profile Picture" class="avatar-img">
                <p>Current profile picture</p>
            <?php else: ?>
                <div class="no-avatar">
                    No picture set
                </div>
                <p>Upload your first profile picture</p>
            <?php endif; ?>
        </div>

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

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" name="avatar" id="avatar" accept=".jpg,.jpeg,.png,.gif" required>
                <div class="file-info">Select a JPG, PNG, or GIF image (max 2MB)</div>
            </div>

            <button type="submit" class="btn btn-upload">Upload Picture</button>
            <a href="profile.php" class="btn btn-cancel">Back to Profile</a>
        </form>

        <div class="requirements">
            <strong>Upload Requirements:</strong>
            <ul>
                <li>Only JPG, JPEG, PNG, or GIF files</li>
                <li>Maximum file size: 2MB</li>
                <li>Recommended size: 150x150 pixels</li>
                <li>Square images work best</li>
            </ul>
        </div>
    </div>
</body>
</html>