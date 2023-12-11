<?php 
session_start();

if (!isset($_SESSION["username"])) {
  header("Location:login.php");
  exit;
}

require 'db_conn.php';

// Insert Entry Log
if (isset($_POST['nomorResi']) && isset($_POST['tanggal']) && isset($_POST['kota']) && isset($_POST['keterangan'])) {
    $nomorResi = $_POST['nomorResi'];
    $tanggal = $_POST['tanggal'];
    $kota = $_POST['kota'];
    $keterangan = $_POST['keterangan'];

    $sql = "INSERT INTO detail (no_resi, tanggal, kota, keterangan) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $nomorResi, $tanggal, $kota, $keterangan);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
