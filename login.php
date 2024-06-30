<?php
    session_start();
    if(isset($_SESSION['name'])){
        header('Location: pages/view-blogs.php');
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body style="background: url(./assets/images/bg-2.png);">
    <div class="container login-container">

        <?php
        
            if(isset($_POST['submit'])){
                $email = $_POST['email'];
                $password = $_POST['password'];
                require_once "database.php";
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($conn, $sql);
                $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

                if($user){
                    if(password_verify($password, $user["password"])){
                        session_start();
                        $_SESSION["name"] = $user["name"];
                        header("Location: pages/view-blogs.php");
                        die();
                    }else{
                        echo '<div class="alert alert-danger">Not Found</div>';
                    }
                }
            }
        
        ?>

        <h2 class="pb-5 text-center">LOGIN FORM</h2>
        <form action="login.php" method="post">
            <div class="form-group">
                <input name="email" type="email" class="form-control" placeholder="Enter your email">
            </div>

            <div class="form-group">
                <input name="password" type="password" class="form-control" placeholder="Enter password">
            </div>

            <div class="form-group w-100 text-center">
                <input name="submit" type="submit" value="Submit" class="btn w-50 submit-btn">
            </div>
        </form>
        
        <p class="pt-3 text-center">New here? <a href="registration.php">Click here to register</a></p>
    </div>
</body>
</html>