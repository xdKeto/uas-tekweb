<?php 
session_start();

if (!isset($_SESSION["username"])) {
  header("Location:login.php");
  exit;
}


if(isset($_POST['no_res'])){
    $_SESSION['resi'] = $_POST['no_res'];
    exit();
}
require 'db_conn.php';


// Insert
if (isset($_POST['inTgl']) && isset($_POST['inNo'])) {
    $inNo = $_POST['inNo'];
    $inTgl = $_POST['inTgl'];

    $sql = "INSERT INTO transaksi (no_resi, tanggal, jenis) VALUES (?, ?, 'Default')";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $inNo, $inTgl);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        // Handle error if preparing statement fails
        echo "Error: " . mysqli_error($conn);
    }
}

// Delete
if (isset($_POST['del_res'])) {
    $res = $_POST['del_res'];

    // Delete from 'detail' table
    $sqlDeleteDetail = "DELETE FROM detail WHERE no_resi = ?";
    $stmtDeleteDetail = mysqli_prepare($conn, $sqlDeleteDetail);

    if ($stmtDeleteDetail) {
        mysqli_stmt_bind_param($stmtDeleteDetail, "s", $res);
        mysqli_stmt_execute($stmtDeleteDetail);
        mysqli_stmt_close($stmtDeleteDetail);
    } else {
        // Handle error if preparing statement fails
        echo "Error deleting from 'detail' table: " . mysqli_error($conn);
    }

    // DELETE from 'transaksi' table
    $sql = "DELETE FROM transaksi WHERE no_resi = ?";
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
                var noResi = $(this).closest('tr').find('.no-resi').text();
                console.log(noResi)
                $.ajax({
                    type: "post",
                    data: {
                        del_res: noResi,
                    },
                    success: function (response) {
                        console.log(noResi)
                        alert('Nomor Resi Telah Dihapus.');
                        location.reload();
                    }
                });
            });

            $('#view').on('click', '#btn-edit', function () {
                var noResi = $(this).data('no_resi');
                $('#nomorResi').val(noResi);
                $('#entryLogModal').modal('show');
            });

            $('#addEntryLogForm').on('submit', function (event) {
                event.preventDefault();
                console.log("AKSJDHLAKSJDH")
                $.ajax({
                    type: 'post',
                    url: 'entry_log.php',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#entryLogModal').modal('hide');
                        alert('Data berhasil ditambahkan.');
                        header("Location: admin.php")
                    },
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
            <a class="nav-link" href="#">Data Resi Pengiriman</a>
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
                            <label for="inTgl" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="inTgl" name ="inTgl" required>
                        </div>
                        <div class="mb-3">
                            <label for="inNo" class="form-label">No Resi</label>
                            <input type="text" class="form-control" id="inNo" name ="inNo" required>
                        </div>
                        <div class="mb-3" id="stat">
                        </div>
                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary" id="submit">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <table class="table table-dark table-striped">
                    <thead>
                        <tr>
                            <th>Tanggal Resi</th>
                            <th>Nomor Resi</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-light" id= "view">
                        <?php
                            $sql = "SELECT * FROM transaksi";
                            $result = mysqli_query($conn, $sql);
                            while($r = mysqli_fetch_assoc($result)){
                                echo "<tr><td class='tgl-resi'>".$r['tanggal']."</td>";
                                echo "<td class='no-resi'>".$r['no_resi']."</td>";
                                echo "<td><button class='btn btn-warning me-lg-3' id='btn-edit' data-no_resi='".$r['no_resi']."'>Entry Log </button><button class='btn btn-danger' id='btn-del'>Delete </button></td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Entry Log -->
     <div class="modal fade" id="entryLogModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labelNomorResi">Entry Log Resi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form untuk mengedit informasi pengguna -->
                    <form id="addEntryLogForm" action="">
                        <div class="mb-3">
                            <label for="editStock" class="form-label">Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                required>
                          
                        </div>
                        <div class="mb-3">
                            <label for="editGambarResource" class="form-label">Kota</label>
                            <input type="text" class="form-control" id="kota" name="kota"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="editNominalResource" class="form-label">Keterangan</label>
                            <input type="textfield" class="form-control" id="keterangan" name="keterangan"
                                required>
                        </div>
                        <input type="hidden" name="nomorResi" id="nomorResi"> 
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>

