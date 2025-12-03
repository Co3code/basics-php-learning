<?php 
echo "<h2>Advance Form Handling with security</h2>";


//securitu function

//function santize input data

function sanitizeInput($data){
    $data =trim($data); // remove whitespace
    $data = stripcslashes($data);  // remove backslashe
    $data =htmlspecialchars($data); // conver special characters
    return $data;
}

//fucntionm to validate email format
function validateEmail($email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }else{
        return false;
    }
}

//function to validate password strenght
function validatePassword($password){
    //at least 8 characters, 1 uppercaseeee, 1 lowercase, 1 numer
    $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/";
    return preg_match($pattern, $password);
}

//fomr  processing logic
$formErrors = array();
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    // I SANITIZE DAYN NATO TANANA INPUTS
    $name = sanitizeInput($_POST["name"]);
    $email = sanitizeInput($_POST["email"]);
    $password = $_POST["password"]; // take note dont sanitize password before hashing
    $age = sanitizeInput($_POST["age"]);


    //validate checks
    if(empty($name)){
        $formErrors[] = "name is required";
    }elseif(strlen($name) < 2){
        $formErrors[]  = "name must be at least 2 characters";
    }

    if (empty($email)){
        $formErrors[] = "email is required";
    }elseif(!validateEmail($email)){
        $formErrors[]= "invalid email format";
    }

    if(empty($password)){
        $formErrors[]= "password is required";

    }elseif(!validatePassword($password)){
        $formErrors[] = "password must be at least 8 characters with uppercase,lowercase, and number";
    }

    if(empty($age)){
        $formErrors[] = "age is required";

    }elseif(!is_numeric($age) || $age < 1 || $age >120){
        $formErrors[] = "age must be a valid number between 1 and 120 wow";
    }

    //if no error, process the form 
    if (empty($formErrors)){
        $successMessage = "form submitted successfully";
        //$hasedpassword = password_hash($password , PASSWORD_DEFAULT);
    }
}   

//Display form result

if(!empty($successMessage)){
    // FIXED: missing "<" + incorrect background spelling + incorrect padding syntax
    echo "<div style='background:#d4edda; color:#155724; padding:15px; margin:20px 0; border-radius:5px;'>";
    
    echo "<h3>Sucess!</h3>";
    echo "$successMessage<br>";
    echo "Name:" . (isset($name) ? $name : '') . "<br>";
    echo "Email:" . (isset($email) ? $email : '') . "<br>";
    echo "Age:" . (isset($age) ? $age : '') . "<br>";
    echo "</div>";

    echo "<a href='".$_SERVER['PHP_SELF']."'>Submit another form</a>";

}else{
    //display error if any
    if(!empty($formErrors)){
        
        echo "<div style='background:#f8d7da; color:#721c25; padding:15px; margin:20px 0; border-radius:5px;'>";
        echo "<h3>validation errors:</h3>";
        echo "<ul>" ;
        foreach ($formErrors as $error){
            echo "<li>" . $error .  "</li>";
        }
        echo "</ul>";
        echo "</div>";

    }
    
}
?>
