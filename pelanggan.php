<?php
require "config.php";
require "navbar.php";


$namapelanggan = "";
$alamat = "";
$email = "";
$success = "";
$error = "";
$button = 'Tambah Pelanggan';


if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}


try {
    if ($op == 'delete') {
        $id = $_GET['id_pelanggan'];
        $sql1 = "DELETE FROM tb_pelanggan WHERE id_pelanggan = '$id'";

        // Attempt to execute the delete query
        $q1 = mysqli_query($conn, $sql1);

        // Check if the query was successful
        if ($q1) {
            $success = "Berhasil Hapus Data";
            header("refresh:2;url=pelanggan.php");
        } else {
            // Check for foreign key constraint violation
            if (mysqli_errno($conn) == 1451) {
                $error = "Gagal Melakukan Hapus Data. Data masih digunakan di halaman lain.";
            } else {
                $error = "Gagal Melakukan Hapus Data";
            }
            header("refresh:2;url=pelanggan.php");
        }
    }
} catch (Exception $e) {
    // Handle other exceptions if needed
    // $error = "Terjadi kesalahan: " . $e->getMessage();
    $error = "Terjadi kesalahan: Data masih digunakan di halaman lain";
}

// if ($op == 'delete') {
//     $id = $_GET['id_pelanggan'];
//     $sql1 = "DELETE FROM tb_pelanggan where id_pelanggan = '$id'";
//     $q1 = mysqli_query($conn, $sql1);
//     if ($q1) {
//         $success = "Berhasil Hapus Data";
//         header("refresh:1;url=pelanggan.php");
//     } else {
//         $error = "Gagal Melakukan Hapus Data";
//         header("refresh:1;url=pelanggan.php");
//     }
// }

if ($op == 'edit') {
    $id = $_GET['id_pelanggan'];
    $sql1 = "SELECT * FROM tb_pelanggan WHERE id_pelanggan = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $namapelanggan = $r1['nama_pelanggan'];
    $alamat = $r1['alamat'];
    $email = $r1['email'];
    $button = 'Edit Pelanggan';

    if ($namapelanggan == '') {
        $error = 'Data tidak ditemukan';
        header("refresh:1;url=pelanggan.php");
    }
}

if (isset($_POST["simpan"])) {
    $namapelanggan = $_POST['namapelanggan'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];

    if ($namapelanggan && $alamat && $email) {
        // Check if the email already exists
        $emailExistsQuery = "SELECT COUNT(*) as count FROM tb_pelanggan WHERE email = '$email'";
        $emailExistsResult = mysqli_query($conn, $emailExistsQuery);
        $emailExistsData = mysqli_fetch_assoc($emailExistsResult);

        if ($op == 'edit') {
            // Fetch existing email for the current record
            $existingEmailQuery = "SELECT email FROM tb_pelanggan WHERE id_pelanggan = '$id'";
            $existingEmailResult = mysqli_query($conn, $existingEmailQuery);
            $existingEmailData = mysqli_fetch_assoc($existingEmailResult);
            $existingEmail = $existingEmailData['email'];

            if ($emailExistsData['count'] > 0 && $email != $existingEmail) {
                $error = "Email sudah digunakan. Silahkan gunakan email lain.";
            } else {
                // Update record
                $button = 'Edit Pelanggan';
                $sql1 = "UPDATE tb_pelanggan SET nama_pelanggan = '$namapelanggan', alamat = '$alamat', email = '$email' WHERE id_pelanggan = '$id'";
                $q1 = mysqli_query($conn, $sql1);

                if ($q1) {
                    $success = "Data Berhasil diupdate";
                    header("refresh:1;url=pelanggan.php");
                } else {
                    $error = "Data gagal diupdate";
                }
            }
        } else {
            if ($emailExistsData['count'] > 0) {
                $error = "Email sudah digunakan. Silahkan gunakan email lain.";
            } else {
                // Insert new record
                $sql1 = "INSERT INTO tb_pelanggan VALUES('', '$namapelanggan', '$alamat', '$email')";
                $q1 = mysqli_query($conn, $sql1);

                if ($q1) {
                    $success = "Berhasil memasukkan data baru";
                    header("refresh:1;url=pelanggan.php");
                } else {
                    $error = "Gagal memasukkan data";
                }
            }
        }
    } else {
        $error = "Silahkan masukkan semua data";
    }
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
    <div class="mx-auto">
        <div class="card">
            <!-- untuk memasukkan data -->
            <div class="card-header text-white bg-secondary">
                Create / Edit data Pelanggan
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                    ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error; ?>
                    </div>
                <?php // 1 detik
                }
                ?>
                <?php
                if ($success) {
                    ?>
                    <div class="alert alert-success" role="alert">
                        <?= $success; ?>
                    </div>

                <?php // 1 detik
                }
                ?>
                <form action="" method="post">
                    <div class="mb-3 row">
                        <label for="namapelanggan" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" name="namapelanggan" id="namapelanggan"
                            placeholder="Bahtiar" value="<?= $namapelanggan ?>">
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" name="alamat" id="alamat" placeholder="Jl. Cempaka No 9"
                            value="<?= $alamat ?>">
                    </div>
                    <div class="mb-3 row">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" placeholder="bahtiar@gmail.com" name="email" id="email"
                            value="<?= $email ?>"></input>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="<?= $button ?>" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>

        <!-- untuk menampilkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Pelanggan
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Pelanggan</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Email</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM tb_pelanggan ORDER BY id_pelanggan DESC";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id_pelanggan'];
                            $namapelanggan = $r2['nama_pelanggan'];
                            $alamat = $r2['alamat'];
                            $email = $r2['email'];

                            ?>
                            <tr>
                                <th scope="row">
                                    <?= $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?= $namapelanggan ?>
                                </td>
                                <td scope="row">
                                    <?= $alamat ?>
                                </td>
                                <td scope="row">
                                    <?= $email ?>
                                </td>
                                <td scope="row">
                                    <a style="display: block; margin-bottom: 5px;" href="pelanggan.php?op=edit&id_pelanggan=<?=
                                        $id; ?>"><button type="button" class="btn btn-warning"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                                <path
                                                    d="M8.707 19.707 18 10.414 13.586 6l-9.293 9.293a1.003 1.003 0 0 0-.263.464L3 21l5.242-1.03c.176-.044.337-.135.465-.263zM21 7.414a2 2 0 0 0 0-2.828L19.414 3a2 2 0 0 0-2.828 0L15 4.586 19.414 9 21 7.414z">
                                                </path>
                                            </svg></button></a>
                                    <a href="pelanggan.php?op=delete&id_pelanggan=<?= $id ?>"
                                        onclick=" return confirm('Yakin ingin menghapus data?')"><button type="button"
                                            class="btn btn-danger"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);">
                                                <path
                                                    d="M6 7H5v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7H6zm10.618-3L15 2H9L7.382 4H3v2h18V4z">
                                                </path>
                                            </svg></button></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>