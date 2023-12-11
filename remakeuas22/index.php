<?php 
require "db_conn.php";
if (isset($_POST['filter'])) {
    $nr = $_POST['filter'];

    $sql = "SELECT * FROM detail WHERE no_resi = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $nr);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $row = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // var_dump($row); die;
        if ($row) {
            foreach ($row as $r) {
                echo "<tr><td class='tgl'>" . $r["tanggal"] . "</td>";
                echo "<td class='kota'>" . $r["kota"] . "</td>";
                echo "<td class='keterangan'>" . $r["keterangan"] . "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='3' class='text-center text-danger'><strong>Tidak ada Data detail pengiriman dengan No Resi " . $nr . "</strong></td></tr>";
        }
        
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <title>Document</title>

    <script>
        $(document).ready(function () {
            $('#search').on('click', function () {
                // var noResi = $('#inNp').val();
                $.ajax({
                    type: "post",
                    data: {
                        filter: $('#inNp').val()
                    },
                    success: function (response) {
                        $('#view').html(response);
                    }
                });
            });
        });
    </script>
</head>
<body>
<nav class="navbar navbar-dark navbar-expand-lg bg-dark">  
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Halo,Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="login.php">Login Admin</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-4">
        <h1>Cek Pengiriman</h1>
        <div class="row my-4">
            <div class="mb-3 col-3">
                <input type="text" class="form-control" id="inNp" name ="inNp" placeholder="Nomor Pengiriman">
            </div>
            <div class="col-2"><button class="btn btn-dark" id="search">Lihat</button></div>
            <p>Ex Input. "RS-100"</p>
        </div>
        <div class="row p-2">
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kota</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody class="table-light" id= "view">
                    <tr></tr>
                </tbody>
        </div>
    </div>




<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>