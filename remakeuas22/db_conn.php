<?php 
$conn = mysqli_connect("localhost", "root", "", "remakeuas22");

if(!$conn){
    echo "Connection Failed!";
}


function register($data){
    global $conn;

    $username = strtolower(stripslashes($data["admin-Username"]));
    $password = mysqli_real_escape_string($conn, $data["admin-pass"]);
    $nama = mysqli_real_escape_string($conn, $data["admin-name"]);
    $status = $data["admin-status"];


    // username double
    $result = mysqli_query($conn, "SELECT * FROM `users` WHERE username = '$username'");

    // var_dump($result); die;
    if(mysqli_fetch_assoc($result) == true){
        echo "<script>alert('username sudah diambil')</script>";
        return false;
    }

    // encryption
    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO users VALUES('$username', '$password', '$nama', '$status', '')");

    return mysqli_affected_rows($conn);
}

?>