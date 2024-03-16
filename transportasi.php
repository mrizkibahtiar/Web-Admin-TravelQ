<?php
require "config.php";
require "navbar.php";


$jenis = "";
$perusahaan = "";
$success = "";
$error = "";
$button = 'Tambah Transportasi';


if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}


try {
    if ($op == 'delete') {
        $id = $_GET['id_transportasi'];
        $sql1 = "DELETE FROM tb_transportasi WHERE id_transportasi = '$id'";

        // Attempt to execute the delete query
        $q1 = mysqli_query($conn, $sql1);

        // Check if the query was successful
        if ($q1) {
            $success = "Berhasil Hapus Data";
            header("refresh:2;url=transportasi.php");
        } else {
            // Check for foreign key constraint violation
            if (mysqli_errno($conn) == 1451) {
                $error = "Gagal Melakukan Hapus Data. Data masih digunakan di halaman lain.";
            } else {
                $error = "Gagal Melakukan Hapus Data";
            }
            header("refresh:2;url=transportasi.php");
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
//         header("refresh:1;url=transportasi.php");
//     } else {
//         $error = "Gagal Melakukan Hapus Data";
//         header("refresh:1;url=transportasi.php");
//     }
// }

if ($op == 'edit') {
    $id = $_GET['id_transportasi'];
    $sql1 = "SELECT * FROM tb_transportasi WHERE id_transportasi = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $jenis = $r1['jenis'];
    $perusahaan = $r1['perusahaan'];
    $button = 'Edit Transportasi';

    if ($perusahaan == '') {
        $error = 'Data tidak ditemukan';
        header("refresh:1;url=transportasi.php");
    }
}

if (isset($_POST["simpan"])) {
    $perusahaan = $_POST['perusahaan'];

    if (isset($_POST['jenis'])) {
        $jenis = $_POST['jenis'];

        if ($jenis && $perusahaan) {
            // Validate $jenis against allowed values ('Bus' or 'Travel')
            $allowedJenis = ['Bus', 'Elf'];

            if (in_array($jenis, $allowedJenis)) {
                if ($op == 'edit') {
                    // Update record
                    $button = 'Edit Transportasi';
                    $sql1 = "UPDATE tb_transportasi SET jenis = '$jenis', perusahaan = '$perusahaan' WHERE id_transportasi = '$id'";
                    $q1 = mysqli_query($conn, $sql1);

                    if ($q1) {
                        $success = "Data Berhasil diupdate";
                        header("refresh:1;url=transportasi.php");
                    } else {
                        $error = "Data gagal diupdate";
                    }
                } else {
                    // Insert new record
                    $sql1 = "INSERT INTO tb_transportasi VALUES('', '$jenis', '$perusahaan')";
                    $q1 = mysqli_query($conn, $sql1);

                    if ($q1) {
                        $success = "Berhasil memasukkan data baru";
                        header("refresh:1;url=transportasi.php");
                    } else {
                        $error = "Gagal memasukkan data";
                    }
                }
            } else {
                $error = "Jenis transportasi tidak valid.";
            }
        } else {
            $error = "Silahkan masukkan semua data";
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
    <title>Transportasi</title>
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
                Create / Edit data Transportasi
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
                        <select name="jenis" class="form-select" aria-label="Default select example">
                            <option disabled selected>Pilih Jenis</option>
                            <option value="Bus" <?= $jenis == 'Bus' ? 'selected' : '' ?>>Bus</option>
                            <option value="Elf" <?= $jenis == 'Elf' ? 'selected' : '' ?>>Elf</option>
                        </select>
                    </div>
                    <div class="mb-3 row">
                        <label for="perusahaan" class="form-label">Perusahaan</label>
                        <input type="text" class="form-control" name="perusahaan" id="perusahaan"
                            placeholder="PT Harapan Jaya" value="<?= $perusahaan ?>">
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
                Transportasi
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Jenis</th>
                            <th scope="col">Perusahaan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM tb_transportasi ORDER BY id_transportasi DESC";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id_transportasi'];
                            $jenis = $r2['jenis'];
                            $perusahaan = $r2['perusahaan'];

                            ?>
                        <tr>
                            <th scope="row">
                                <?= $urut++ ?>
                            </th>
                            <td scope="row">
                                <?= $jenis ?>
                            </td>
                            <td scope="row">
                                <?= $perusahaan ?>
                            </td>
                            <td scope="row">
                                <a style="display: block; margin-bottom: 5px;" href="transportasi.php?op=edit&id_transportasi=<?=
                                        $id; ?>"><button type="button" class="btn btn-warning"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                            <path
                                                d="M8.707 19.707 18 10.414 13.586 6l-9.293 9.293a1.003 1.003 0 0 0-.263.464L3 21l5.242-1.03c.176-.044.337-.135.465-.263zM21 7.414a2 2 0 0 0 0-2.828L19.414 3a2 2 0 0 0-2.828 0L15 4.586 19.414 9 21 7.414z">
                                            </path>
                                        </svg></button></a>
                                <a href="transportasi.php?op=delete&id_transportasi=<?= $id ?>"
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