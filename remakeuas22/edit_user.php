<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

require 'db_conn.php';

// Edit User
if (isset($_POST['nama-admin']) && isset($_POST['pass-admin']) && isset($_POST['uname-admin'])) {
    $id = $_POST['admin-id'];
    $nama = $_POST["nama-admin"];
    $pass = $_POST["pass-admin"];
    $uname = $_POST["uname-admin"];
    echo "Received data: Nama=$nama, Pass=$pass, Uname=$uname";

    if (!empty($pass)) {
        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

        $sql = "UPDATE `users` SET `username`=?, `password`=?, `nama`=? WHERE admin_id =?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssss", $uname, $hashedPass, $nama, $id);
            mysqli_stmt_execute($stmt);
            echo "Rows affected: " . mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            exit();
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    } else {
        $sql = "UPDATE `users` SET `username`=?, `nama`=? WHERE admin_id =?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $uname, $nama, $id);
            mysqli_stmt_execute($stmt);
            echo "Rows affected: " . mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);
            exit();
        } else {
            echo "Error preparing statement: " . mysqli_error($conn);
        }
    }
} else {
    echo "Form data not received.";
}
?>
