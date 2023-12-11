<?php
require 'db_conn.php';

if (isset($_POST['get_user_data'])) {
    $username = $_POST['get_user_data'];
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $userData = mysqli_fetch_assoc($result);
        echo json_encode($userData);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
