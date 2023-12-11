<?php
if(isset($_SESSION["username"])){
    header("Location: admin.php");
}
session_start(); 

require "db_conn.php";

if(isset($_POST["username"]) && isset($_POST["password"])){
    
    $username = $_POST["username"];
    $password = $_POST["password"];
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username' and status = 1");

    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_assoc($result);
        if(password_verify($password, $row["password"])){
            
            $_SESSION["username"] = $username;
            $_SESSION["data"] = $row;
            echo "1";
            exit;
        }
    }
}




?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <style>
        /* *{
            border: 1px solid red;
        } */

        body{
            margin: 0;
            padding: 0;
        }

        .box{
            padding: 45px;
            border-radius: 10%;
            border: 1px solid black;
        }
    </style>

    <script>
        $(document).ready(function () {
            $('#logIn').on('click', function () {
                $.ajax({
                type: "post",
                data: {
                    username: $('#username').val(),
                    password: $('#password').val()
                },
                success: function (response) {
                    console.log("Username: " + $('#username').val()); // Add this line
                    console.log("Password: " + $('#password').val()); // This line is already there

                    console.log("Server Response: ", response);
                    if(response != 1){
                        alert("Login Failed!")
                    }else{
                        window.location.href="admin.php";
                    }
                }
            });
            });
        });
    </script>

</head>
  <body>
    
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Login Admin</a>
            <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link" href="index.php">Back</a>
            </li>
        </ul>
        </div>
            </div>
        </div>
    </nav>
    <p>username = admin, password = admin</p>
    
  <div class="container-fluid">
    <div class="row" style="width: 90%; margin: 100pt">
        <div class="col-lg-4 col-md-3 col-sm-2 col-1"></div>
        <div class="col-lg-4 col-md-6 col-sm-8 col-10 box">
            <div class="row bg-dark mb-3">
                <h1 class="text-light text-center">Welcome</h1>
            </div>

            <form action="login.php" method="post">
                <div class="mb-3">
                    <div class="container-fluid position-relative p-0">
                        <input type="text" name="username" id="username"class="form-control" placeholder="Username" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="container-fluid position-relative p-0">
                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="off">
                    </div>
                </div>
                <div class="mb-3">
                    <div class="container-fluid position-relative p-0">
                        <button type="button" id="logIn" class="btn btn-dark" style="width: 100%">Login</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>