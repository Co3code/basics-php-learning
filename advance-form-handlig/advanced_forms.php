<?php
    /*
 * FILE: advanced_forms.php
 * PURPOSE: Learn advanced form handling with security
 * AUTHOR: Co3code with Seek
 */

    // 1. Include database configuration
    require_once 'db_config.php';

    // 2. Check if security_functions.php exists before including
    //    If file doesn't exist, we'll define functions here
    if (file_exists('security_functions.php')) {
        require_once 'security_functions.php';
    }

    echo "<h2>Advanced Form Handling with Security</h2>";

    // ====================
    // SECURITY FUNCTIONS (Define only if not already included)
    // ====================

    // Check if functions don't exist, then define them
    if (! function_exists('sanitizeInput')) {
        // Function to sanitize input data
        function sanitizeInput($data)
        {
            $data = trim($data);             // Remove whitespace
            $data = stripslashes($data);     // Remove backslashes
            $data = htmlspecialchars($data); // Convert special characters
            return $data;
        }
    }

    if (! function_exists('validateEmail')) {
        // Function to validate email format
        function validateEmail($email)
        {
            return filter_var($email, FILTER_VALIDATE_EMAIL);
        }
    }

    if (! function_exists('validatePassword')) {
        // Function to validate password strength
        function validatePassword($password)
        {
            // At least 8 characters, 1 uppercase, 1 lowercase, 1 number
            $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/";
            return preg_match($pattern, $password);
        }
    }

    // ====================
    // FORM PROCESSING LOGIC
    // ====================

    $formErrors     = [];
    $successMessage = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize all inputs
        $name     = sanitizeInput($_POST["name"]);
        $email    = sanitizeInput($_POST["email"]);
        $password = $_POST["password"]; // Don't sanitize password before hashing
        $age      = sanitizeInput($_POST["age"]);

        // Validation checks
        if (empty($name)) {
            $formErrors[] = "Name is required";
        } elseif (strlen($name) < 2) {
            $formErrors[] = "Name must be at least 2 characters";
        }

        if (empty($email)) {
            $formErrors[] = "Email is required";
        } elseif (! validateEmail($email)) {
            $formErrors[] = "Invalid email format";
        }

        if (empty($password)) {
            $formErrors[] = "Password is required";
        } elseif (! validatePassword($password)) {
            $formErrors[] = "Password must be at least 8 characters with uppercase, lowercase, and number";
        }

        if (empty($age)) {
            $formErrors[] = "Age is required";
        } elseif (! is_numeric($age) || $age < 1 || $age > 120) {
            $formErrors[] = "Age must be a valid number between 1 and 120";
        }

        // If no errors, process the form
        if (empty($formErrors)) {
            // Hash password using CORRECT function name
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement (prevents SQL injection)
            $sql  = "INSERT INTO users (name, email, password, age) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // Bind parameters (s = string, i = integer)
                $stmt->bind_param("sssi", $name, $email, $hashedPassword, $age);

                if ($stmt->execute()) {
                    $successMessage = "Registration successful! Data saved to database.";
                    $lastId         = $stmt->insert_id;
                } else {
                    $formErrors[] = "Database error: " . $stmt->error;
                }

                $stmt->close();
            } else {
                $formErrors[] = "Database preparation error: " . $conn->error;
            }
        }
    }

    // ====================
    // DISPLAY FORM OR RESULTS
    // ====================

    if (! empty($successMessage)) {
        echo "<div style='background: #d4edda; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h3>Success!</h3>";
        echo $successMessage;

        if (isset($lastId)) {
            echo "<br>Your user ID is: " . $lastId;
        }

        echo "<br><br>Form Data Received (Sanitized):<br>";
        echo "Name: " . (isset($name) ? $name : '') . "<br>";
        echo "Email: " . (isset($email) ? $email : '') . "<br>";
        echo "Age: " . (isset($age) ? $age : '') . "<br>";
        echo "</div>";

        echo "<a href='" . $_SERVER['PHP_SELF'] . "'>Submit Another Form</a>";
    } else {
        // Display errors if any
        if (! empty($formErrors)) {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
            echo "<h3>Validation Errors:</h3>";
            echo "<ul>";
            foreach ($formErrors as $error) {
                echo "<li>" . $error . "</li>";
            }
            echo "</ul>";
            echo "</div>";
        }
    ?>
<!-- Advanced Form with Validation -->
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" style="max-width: 500px; margin: 0 auto;">
    <h3>Advanced Registration Form</h3>

    <div style="margin-bottom: 15px;">
        <label>Full Name:</label><br>
        <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>"
               style="width: 100%; padding: 8px; margin-top: 5px;">
        <small>Minimum 2 characters</small>
    </div>

    <div style="margin-bottom: 15px;">
        <label>Email Address:</label><br>
        <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>"
               style="width: 100%; padding: 8px; margin-top: 5px;">
        <small>Must be valid email format</small>
    </div>

    <div style="margin-bottom: 15px;">
        <label>Password:</label><br>
        <input type="password" name="password"
               style="width: 100%; padding: 8px; margin-top: 5px;">
        <small>8+ characters with uppercase, lowercase, and number</small>
    </div>

    <div style="margin-bottom: 20px;">
        <label>Age:</label><br>
        <input type="number" name="age" value="<?php echo isset($age) ? $age : ''; ?>"
               min="1" max="120" style="width: 100%; padding: 8px; margin-top: 5px;">
        <small>Between 1 and 120</small>
    </div>

    <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
        Submit Registration
    </button>
</form>
<?php
}
?>