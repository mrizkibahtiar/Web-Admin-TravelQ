<?php
require 'config.php';
require 'navbar.php';

if (!empty ($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $result = mysqli_query($conn, "SELECT * FROM tb_user WHERE id = '$id'");
    $row = mysqli_fetch_assoc($result);
} else {
    header("Location : signin.php");
}


?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Destinasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: "Poppins", sans-serif !important;
        }

        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 10px;
        }

        input.form-control::placeholder {
            /* Chrome, Firefox, Opera, Safari 10.1+ */
            opacity: .2;
            /* Firefox */
        }
    </style>
</head>

<body>
    <div style="width: 100%; display: flex; justify-content: center;">
        <p style="font-size: 2rem;">
            Selamat Datang Admin
            <span style="font-weight: bold;">
                <?= $row["name"]; ?>
            </span>
        </p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>