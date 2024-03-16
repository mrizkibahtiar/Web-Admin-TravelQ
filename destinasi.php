<?php
require "config.php";
require "navbar.php";

$namadestinasi = "";
$lokasi = "";
$gambar = "";
$deskripsi = "";
$success = "";
$error = "";

$button = 'Tambah Destinasi';


if (isset ($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}





try {
    if ($op == 'delete') {
        $id = $_GET['id_destinasi'];

        // Fetch the image name before deleting the record
        $sqlFetchImage = "SELECT gambar FROM tb_destinasi WHERE id_destinasi = '$id'";
        $qFetchImage = mysqli_query($conn, $sqlFetchImage);
        $rFetchImage = mysqli_fetch_assoc($qFetchImage);
        $imageNameToDelete = $rFetchImage['gambar'];

        // Construct the file path
        $fileToDelete = 'upload/' . $imageNameToDelete;

        // Delete the local file
        if (file_exists($fileToDelete)) {
            unlink($fileToDelete);
        }

        // Delete the record
        $sqlDelete = "DELETE FROM tb_destinasi WHERE id_destinasi = '$id'";
        $qDelete = mysqli_query($conn, $sqlDelete);

        // Check if the query was successful
        if ($qDelete) {
            $success = "Berhasil Hapus Data";

            // Delete all unused images in the local file directory
            $sqlFetchAllImages = "SELECT gambar FROM tb_destinasi";
            $qFetchAllImages = mysqli_query($conn, $sqlFetchAllImages);
            $usedImages = mysqli_fetch_all($qFetchAllImages, MYSQLI_ASSOC);

            $allImages = glob('upload/*');
            foreach ($allImages as $imagePath) {
                $imageName = basename($imagePath);
                if (!in_array(['gambar' => $imageName], $usedImages)) {
                    // This image is not used in the database, delete it
                    unlink($imagePath);
                }
            }

            header("refresh:2;url=destinasi.php");
        } else {
            $error = "Gagal Melakukan Hapus Data";
            header("refresh:2;url=destinasi.php");
        }
    }
} catch (Exception $e) {
    // Handle other exceptions if needed
    // $error = "Terjadi kesalahan: " . $e->getMessage();
    $error = "Terjadi kesalahan: Data masih digunakan di halaman lain";
}





if ($op == 'edit') {
    $id = $_GET['id_destinasi'];
    $sql1 = "SELECT * FROM tb_destinasi WHERE id_destinasi = '$id'";
    $q1 = mysqli_query($conn, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $namadestinasi = $r1['nama_destinasi'];
    $lokasi = $r1['lokasi_destinasi'];
    $gambar = $r1['gambar'];
    $deskripsi = $r1['deskripsi'];
    $button = 'Edit Destinasi';

    if ($namadestinasi == '') {
        $error = 'Data tidak ditemukan';
        header("refresh:2;url=destinasi.php");
    }
}

if (isset ($_POST["simpan"])) { // untuk create
    $namadestinasi = $_POST['namadestinasi'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];

    // Check if a new image is uploaded
    if (!empty ($_FILES['gambar']['name'])) {
        // Generate a random name for the image
        $randomImageName = uniqid(rand(), true) . '_' . $_FILES['gambar']['name'];

        // Move the uploaded file to the 'upload' directory with the random name
        move_uploaded_file($_FILES['gambar']['tmp_name'], 'upload/' . $randomImageName);

        // Set the new random image name
        $gambar = $randomImageName;
    }

    if ($namadestinasi && $lokasi && $gambar && $deskripsi) {
        if ($op == 'edit') { // untuk update
            $button = 'Edit Destinasi';
            $sql1 = "UPDATE tb_destinasi SET nama_destinasi = '$namadestinasi', lokasi_destinasi = '$lokasi', gambar = '$gambar', deskripsi = '$deskripsi' WHERE id_destinasi = '$id'";
            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $success = "Data Berhasil diupdate";
                header("refresh:2;url=destinasi.php");
            } else {
                $error = "Data gagal diupdate";
            }
        } else { // untuk insert
            $sql1 = "INSERT INTO tb_destinasi VALUES('', '$namadestinasi', '$lokasi', '$gambar', '$deskripsi')";
            $q1 = mysqli_query($conn, $sql1);
            if ($q1) {
                $success = "Berhasil memasukkan data baru";
                header("refresh:2;url=destinasi.php");
            } else {
                $error = "Gagal memasukkan data";
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
                Create / Edit data Destinasi
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
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3 row">
                        <label for="namadestinasi" class="form-label">Nama Destinasi</label>
                        <input type="text" class="form-control" placeholder="Air Terjun Dolo" name="namadestinasi"
                            id="namadestinasi" value="<?= isset ($namadestinasi) ? $namadestinasi : '' ?>">
                    </div>
                    <div class="mb-3 row">
                        <label for="lokasi" class="form-label">Lokasi</label>
                        <input type="text" class="form-control" placeholder="Kediri" name="lokasi" id="lokasi"
                            value="<?= isset ($lokasi) ? $lokasi : '' ?>">
                    </div>
                    <div class="mb-3 row">
                        <label for="gambar" class="form-label">Gambar</label>

                        <?php if ($op == 'edit' && $gambar != ''): ?>
                            <!-- Display the current image when editing -->
                            <img src="upload/<?= $gambar ?>" alt="<?= $gambar ?>" id="old-image"
                                style="width: 100px; margin-bottom: 10px;">
                        <?php endif; ?>

                        <!-- when upload new image when editing -->
                        <img id="image-preview" src="#" alt="Preview"
                            style="display: none; width: 100px; margin-bottom: 10px;">

                        <input type="file" class="form-control" name="gambar" id="gambar" accept="image/*"
                            onchange="previewImage()">

                        <?php if ($op == 'edit'): ?>
                            <!-- Add a hidden input field to store the current image filename -->
                            <input type="hidden" name="current_image" value="<?= $gambar ?>">
                            <small class="form-text text-muted">Upload gambar baru untuk mengganti gambar ini
                                (opsional).</small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3 row">
                        <label for="deskripsi">Deskripsi</label>
                        <input type="text" class="form-control"
                            placeholder="Salah satu wisata ramai pengunjung di Kediri" name="deskripsi" id="deskripsi"
                            value="<?= isset ($deskripsi) ? $deskripsi : '' ?>">
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
                Destinasi
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Destinasi</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Gambar</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT * FROM tb_destinasi ORDER BY id_destinasi DESC";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id_destinasi'];
                            $namadestinasi = $r2['nama_destinasi'];
                            $lokasi = $r2['lokasi_destinasi'];
                            $gambar = $r2['gambar'];
                            $deskripsi = $r2['deskripsi'];

                            ?>
                            <tr>
                                <th scope="row">
                                    <?= $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?= $namadestinasi ?>
                                </td>
                                <td scope="row">
                                    <?= $lokasi ?>
                                </td>
                                <td scope="row">
                                    <img src="upload/<?= $gambar ?>" alt="<?= $gambar ?>" width="70px">
                                </td>
                                <td scope="row">
                                    <?= $deskripsi ?>
                                </td>
                                <td scope="row">
                                    <a style="display: block; margin-bottom: 5px;" href="destinasi.php?op=edit&id_destinasi=<?=
                                        $id; ?>"><button type="button" class="btn btn-warning"><svg
                                                xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);">
                                                <path
                                                    d="M8.707 19.707 18 10.414 13.586 6l-9.293 9.293a1.003 1.003 0 0 0-.263.464L3 21l5.242-1.03c.176-.044.337-.135.465-.263zM21 7.414a2 2 0 0 0 0-2.828L19.414 3a2 2 0 0 0-2.828 0L15 4.586 19.414 9 21 7.414z">
                                                </path>
                                            </svg></button></a>
                                    <a href="destinasi.php?op=delete&id_destinasi=<?= $id ?>"
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
    <script>
        function previewImage() {
            const input = document.getElementById('gambar');
            const preview = document.getElementById('image-preview');
            const oldImage = document.getElementById('old-image');

            // Remove the old image
            if (oldImage) {
                oldImage.parentNode.removeChild(oldImage);
            }

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
    </script>
</body>

</html>