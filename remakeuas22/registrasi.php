<?php 
require "db_conn.php";


if(isset($_POST["register"])){
    if(register($_POST) > 0){
        echo "<script>alert('Register Success');</script>";
    }else{
        echo mysqli_error($conn);
    }
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>REGISTRATION</h1>

    <form action="" method="post" style="list-style-type: none;">
        <ul>
            <li>
                <label for="username">Username</label>
                <input type="text"name="username" id="username">
            </li>
            <li>
                <label for="password">Password</label>
                <input type="password" name="password" id="password">
            </li>
            <li>
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama">
            </li>
            <li>
                <label for="Status">Status</label>
                <input type="number" name="status" id="status">
            </li>
            <li><button type="submit" name="register">Register</button></li>
        </ul>
    </form>
</body>
</html>