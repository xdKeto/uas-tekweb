<?php 
session_start();

if (!isset($_SESSION["username"])) {
    header("Location:login.php");
    exit;
}

require 'db_conn.php';

// Insert
if (isset($_POST['inputResi'])) {
    $inNo = $_POST['inNo'];
    $inTgl = $_POST['inTgl'];

    $sql = "INSERT INTO transaksi (no_resi, tanggal, jenis) VALUES (?, ?, 'Default')";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $inNo, $inTgl);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($result) {
            echo 'success'; // Signal success to AJAX
            exit();
        } else {
            echo 'Error inserting data into transaksi table';
            exit();
        }
    } else {
        // Handle error if preparing statement fails
        echo "Error: " . mysqli_error($conn);
        exit();
    }
}
?>