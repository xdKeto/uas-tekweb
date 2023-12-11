<?php 
session_start();

if (!isset($_SESSION["username"])) {
  header("Location:login.php");
  exit;
}

require 'db_conn.php';

// Insert
if (isset($_POST['addUser'])) {
    if(register($_POST) > 0){
        echo "<script>alert('Register Success');</script>";
    }else{
        echo mysqli_error($conn);
    }
}

// Switch Status
if (isset($_POST['switch_user'])) {
    $res = $_POST['switch_user'];

    $check = mysqli_query($conn, "SELECT status FROM users WHERE username = '$res'");
    $currentStatus = mysqli_fetch_assoc($check)["status"];

    if ($currentStatus == 1) {
        $newStatus = 0;
    } else {
        $newStatus = 1;
    }

    $sql = "UPDATE `users` SET `status`='$newStatus' WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $res);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        exit();
    } else {
        // Handle error if preparing statement fails
        echo "Error: " . mysqli_error($conn);
    }
}




?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>

    <style>
            body {
                margin: 0;
                padding: 0;
            }
    </style>

    <script>
        $(document).ready(function () {
            $('#view').on('click', '#btn-del', function () {
                var username = $(this).data('username')
                $.ajax({
                    type: "post",
                    url: "userControl.php",
                    data: {
                        switch_user: username,
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            });

            $('#view').on('click', '#btn-edit', function () {
                var username = $(this).data('username');

            // Fetch existing data and populate the modal fields
                $.ajax({
                    type: "post",
                    url: "get_user_data.php",
                    data: {
                        get_user_data: username,
                    },
                    success: function (response) {
                        var userData = JSON.parse(response);
                        $('#admin-id').val(userData.admin_id);
                        $('#nama-admin').val(userData.nama);
                        $('#uname-admin').val(userData.username);
                        $('#editUserModal').modal('show');
                    }
                });
            });

            $('#editUserForm').on('submit', function (event) {
                event.preventDefault();
                $.ajax({
                    type: 'post',
                    url: 'edit_user.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        console.log("AJAX success:", response); 
                        $('#editUserModal').modal('hide');
                        alert('Data Updated.');
                        location.reload();
                    },
                    error: function (xhr, status, error) {
                        console.log("AJAX error:", error); 
                    }
                });
            });
        });
    </script>

</head>
<body>



    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link" href="admin.php">Data Resi Pengiriman</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="userControl.php">Users</a>
            </li>
            <li class="nav-item">
            <a class="nav-link" href="logout.php">Log Out</a>
            </li>
        </ul>
        </div>
    </div>
    </nav>

    <div class="container-fluid my-2 p-3">
            <div class="row mb-2">
                <div class="col-4">
                    <form action="" method="post">
                        <div class="mb-3">
                            <label for="admin-Username" class="form-label">Username</label>
                            <input type="username" class="form-control" id="admin-Username" name ="admin-Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="admin-pass" class="form-label">Password</label>
                            <input type="password" class="form-control" id="admin-pass" name ="admin-pass">
                        </div>
                        <div class="mb-3">
                            <label for="admin-name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="admin-name" name ="admin-name" required>
                        </div>
                        <div class="mb-3">
                            <input type="hidden" id="admin-status" name="admin-status" value="1">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="submit" name="addUser">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-light" id= "view">
                        <?php
                            $sql = "SELECT * FROM users";
                            $result = mysqli_query($conn, $sql);
                            while($r = mysqli_fetch_assoc($result)){
                                $status = $r['status'];
                                if($status == 1){
                                    $status = "Aktif";
                                }else{
                                    $status = "Tidak Aktif";
                                }

                                echo "<tr><td class='uname'>".$r['username']."</td>";
                                echo "<td class='adminName'>".$r['nama']."</td>";
                                echo "<td class='adminStatus'>".$status."</td>";
                                echo "<td><button class='btn btn-warning me-lg-3' id='btn-edit' data-username='" . $r['username'] . "'>Edit </button><button class='btn btn-danger' id='btn-del' data-username='" . $r['username'] . "'>Switch </button></td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Entry Log -->
     <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelNomorResi">Entry Log Resi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk mengedit informasi pengguna -->
                    <form id="editUserForm">
                        <div class="mb-3">
                            <label for="editStock" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="nama-admin" name="nama-admin"
                                required>
                          
                        </div>
                        <div class="mb-3">
                            <label for="editGambarResource" class="form-label">Password</label>
                            <input type="text" class="form-control" id="pass-admin" name="pass-admin"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editNominalResource" class="form-label">Username</label>
                            <input type="textfield" class="form-control" id="uname-admin" name="uname-admin"
                                required>
                        </div>
                        <input type="hidden" id="admin-id" name="admin-id" value="">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

