<?php
    session_start();
    if(isset($_SESSION['name'])){
        header('Location: dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">

</head>
<body style="background: url(./assets/images/bg-1.jpg);">
    <div class="container reg-container">
        <?php

            if(isset($_POST['submit'])){
                $name = $_POST['name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $passwordRepeat = $_POST['repeat_password'];
                $passwordHash = password_hash($password, PASSWORD_DEFAULT); // Password Encryption
                $error = array();


                if(empty($name) || empty($email || empty($password) || empty($passwordRepeat))){
                    array_push($error, 'All fields are required !');
                }

                if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    array_push($error, 'Email not valid');
                }

                if(strlen($password)<5){
                    array_push($error, 'Password not valid');
                }

                if($password !== $passwordRepeat){
                    array_push($error, 'Passwords do not match');
                }
                
                require_once "database.php";

                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $rowCount = mysqli_num_rows($result);

                if($rowCount > 0){
                    array_push($error, "Email already exist !");
                }

                if(count($error)>0){
                    foreach($error as $elmt){
                        echo "<div class='alert alert-danger'>$elmt</div>";
                    }
                }
                else{
                    $stmt = mysqli_stmt_init($conn);
                    $sql = "INSERT INTO users (name, email, password) VALUES ( ?, ?, ?)";
                    $prepareStmt = mysqli_stmt_prepare($stmt, $sql);

                    if($prepareStmt){
                        mysqli_stmt_bind_param($stmt, "sss", $name, $email, $passwordHash);
                        mysqli_stmt_execute($stmt);
                        echo "<div class='alert alert-success'>You are registered successfully.</div>";
                    }
                    else{
                        die("Something went wrong !");
                    }
                }
            }

        ?>

        <h2 class="pb-5 text-uppercase text-center">Registration</h2>

        <form action="registration.php" method="post">
            <div class="form-group">
                <input class="form-control" type="text" name="name" placeholder="Your Name">
            </div>
            <div class="form-group">
                <input class="form-control" type="email" name="email" placeholder="Your Email">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="password" placeholder="Enter Password">
            </div>
            <div class="form-group">
                <input class="form-control" type="password" name="repeat_password" placeholder="Re-enter Password">
            </div>
            <div class="form-btn text-center mb-3 mt-5">
                <input class="btn w-50 submit-btn" type="submit" value="Submit" name="submit">
            </div>
        </form>

        <p class="pt-3 text-center">Already registered? <a href="login.php">Click here to login</a></p>
    </div>
</body>
</html>