<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body{
            font-family:  Arial, sans-serif;
            margin: 40px;
          
        }
        .form-group{
            margin: 15px 0;
        }
        input[type="text"], input[type="email"], input[type="password"]{
            width: 300px;
            padding: 8px;
            margin: 5px 0;
        }


    </style>
</head>
<body>
    <form action="process_registration.php" method="POST">
    <div class="form-group">
        <label>username</label><br>
        <input type="text" name="username" required>
    </div>


    <div class="form-group"><br>
    <label >email</label><br>
    <input type="email" name="email" required>
    <div>
    
    
    <div class="form-group">
        <label >password:</label> <br>
        <input type="password" name="password" required>
    </div>

    <div class="form-group">
        <label >confirm password:</label> <br>
        <input type="password" name="confirm_password" required>
    </div>

   <input type="submit" value="register">

   <p> already have an account? <a href="login.php">login here</a></p>
   </form>
    
</body>
</html>