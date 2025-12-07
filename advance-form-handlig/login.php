<?php 

session_start();

if(!isset($_SESSION['user_ud'])){
    header("location:dashboard.php");
    exit();
}


require_once 'db_config.php';

// initialize error/sucess messages
$error = '';
$success = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

 //basic validation
 if(empty($email)|| empty($password)){
    $error = "please enter both email and password.";


 }else{
    //finde user by email
    $sql = " SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

            // to be continue 
 }














}




?>