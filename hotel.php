<?php
require "config.php";
require "navbar.php";

$namahotel = "";
$lokasi = "";
$bintang = "";
$fasilitas = "";
$success = "";
$error = "";

$button = 'Tambah Hotel';


if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}



try {
    if ($op == 'delete') {
        $id = $_GET['id_hotel'];
        $sql1 = "DELETE FROM tb_hotel WHERE id_hotel = '$id'";

        // Attempt to execute the delete query
        $q1 = mysqli_query($conn, $sql1);

        // Check if the query was successful
        if ($q1) {
            $success = "Berhasil Hapus Data";
            header("refresh:2;url=hotel.php");
        } else {
            // Check for foreign key constraint violation
            if (mysqli_errno($conn) == 1451) {
                $error = "Gagal Melakukan Hapus Data. Data masih digunakan di halaman lain.";
            } else {
                $error = "Gagal Melakukan Hapus Data";
            }
            header("refresh:2;url=hotel.php");
        }
    }
} catch (Exception $e) {
    // Handle other exceptions if needed
    // $error = "Terjadi kesalahan: " . $e->getMessage();
    $error = "Terjadi kesalahan: Data masih digunakan di halaman lain";
}

// if ($op == 'delete') {
//     $id = $_GET['id_hotel'];
//     $sql1 = "DELETE FROM tb_hotel where id_hotel = '$id'";
//     $q1 = mysqli_query($conn, $sql1);
//     if ($q1) {
//         $success = "Berhasil Hapus Data";
//         header("refresh:1;url=hotel.php");
//     } else {
//         $error = "Gagal Melakukan Hapus Data";
//         header("refresh:1;url=hotel.php");
//     }
// }

if ($op == 'edit') {
    $id = $_GET['id_hotel'];
    $sql1 = "SELECT * FROM tb_hotel WHERE id_hotel = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $namahotel = $r1['nama_hotel'];
    $lokasi = $r1['lokasi_hotel'];
    $bintang = $r1['bintang'];
    $fasilitas = $r1['fasilitas'];

    $button = 'Edit Hotel';

    if ($namahotel == '') {
        $error = 'Data tidak ditemukan';
        header("refresh:1;url=hotel.php");
    }
}

if (isset($_POST["simpan"])) { // untuk create
    $namahotel = $_POST['namadestinasi'];
    $lokasi = $_POST['lokasi'];
    $bintang = $_POST['bintang'];
    $fasilitas = $_POST['fasilitas'];

    if ($namahotel && $lokasi && $bintang !== '' && $fasilitas !== '') {
        if ($bintang <= 5 && $bintang >= 1) {
            if ($op == 'edit') { // untuk update
                $button = 'Edit Hotel';
                $sql1 = "UPDATE tb_hotel SET nama_hotel = '$namahotel', lokasi_hotel = '$lokasi', bintang = '$bintang', fasilitas = '$fasilitas' WHERE id_hotel = '$id'";
                $q1 = mysqli_query($conn, $sql1);
                if ($q1) {
                    $success = "Data Berhasil diupdate";
                    header("refresh:1;url=hotel.php");
                } else {
                    $error = "Data gagal diupdate";
                }
            } else { // untuk insert
                $sql1 = "INSERT INTO tb_hotel VALUES('', '$namahotel', '$lokasi', '$bintang', '$fasilitas')";
                $q1 = mysqli_query($conn, $sql1);
                if ($q1) {
                    $success = "Berhasil memasukkan data baru";
                    header("refresh:1;url=hotel.php");
                } else {
                    $error = "Gagal memasukkan data";
                }
            }
        } else {
            // Invalid bintang value
            $error = "Bintang tidak valid. Harus di antara 1 dan 5.";
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
                Create / Edit data Hotel
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
                        <label for="namadestinasi" class="form-label">Nama Hotel</label>
                        <input type="text" class="form-control" name="namadestinasi" id="namadestinasi"
                            placeholder="Hotel Merdeka" value="<?= $namahotel ?>">
                    </div>
                    <div class="mb-3 row">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" name="lokasi" id="lokasi" placeholder="Kediri"
                            value="<?= $lokasi ?>">
                    </div>
                    <div class="mb-3 row">
                        <label for="bintang">Bintang</label>
                        <input class="form-control" placeholder="1-5" name="bintang" id="bintang"
                            value="<?= $bintang ?>"></input>
                    </div>
                    <div class="mb-3 row">
                        <label for="fasilitas">Fasilitas</label>
                        <input class="form-control" placeholder="AC, Double Bed" name="fasilitas" id="fasilitas"
                            value="<?= $fasilitas ?>"></input>
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
                Hotel
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Hotel</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Bintang</th>
                            <th scope="col">Fasilitas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM tb_hotel ORDER BY id_hotel DESC";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id_hotel'];
                            $namahotel = $r2['nama_hotel'];
                            $lokasi = $r2['lokasi_hotel'];
                            $bintang = $r2['bintang'];
                            $fasilitas = $r2['fasilitas'];
                            ?>
                            <tr>
                                <th scope="row">
                                    <?= $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?= $namahotel ?>
                                </td>
                                <td scope="row">
                                    <?= $lokasi ?>
                                </td>
                                <td scope="row">
                                    <?= $bintang ?>
                                </td>
                                <td scope="row">
                                    <?= $fasilitas ?>
                                </td>
                                <td scope="col">
                                    <a style="display: block; margin-bottom: 5px;" href="hotel.php?op=edit&id_hotel=<?=
                                        $id; ?>"><button type="button" class="btn btn-warning"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                                <path
                                                    d="M8.707 19.707 18 10.414 13.586 6l-9.293 9.293a1.003 1.003 0 0 0-.263.464L3 21l5.242-1.03c.176-.044.337-.135.465-.263zM21 7.414a2 2 0 0 0 0-2.828L19.414 3a2 2 0 0 0-2.828 0L15 4.586 19.414 9 21 7.414z">
                                                </path>
                                            </svg></button></a>
                                    <a href="hotel.php?op=delete&id_hotel=<?= $id ?>"
                                        onclick="return confirm('Yakin ingin menghapus data?')"><button type="button"
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