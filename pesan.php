<?php
require "config.php";
require "navbar.php";

$pelanggan = "";
$destinasi = "";
$hotel = "";
$transportasi = "";
$jenis = "";
$perusahaan = "";
$success = "";
$error = "";
$button = "Tambah Pesanan";

// Set $op to an empty string if it is not set
$op = isset($_GET['op']) ? $_GET['op'] : '';

// Delete operation
if ($op == 'delete' && isset($_GET['id_pesan'])) {
    $id = $_GET['id_pesan'];
    $sqlDelete = "DELETE FROM tb_pesan WHERE id_pesan = '$id'";
    $resultDelete = mysqli_query($conn, $sqlDelete);

    if ($resultDelete) {
        $success = "Berhasil Hapus Data";
        header("refresh:1;url=pesan.php");
    } else {
        $error = "Gagal Melakukan Hapus Data";
    }
}

// Edit operation
if ($op == 'edit' && isset($_GET['id_pesan'])) {
    $id = $_GET['id_pesan'];
    $button = "Edit Pesanan";
    $sqlSelect = "SELECT A.nama_pelanggan, B.nama_destinasi, C.nama_hotel, D.jenis, D.perusahaan
                  FROM tb_pelanggan A
                  JOIN tb_pesan E ON A.id_pelanggan = E.id_pelanggan
                  JOIN tb_destinasi B ON B.id_destinasi = E.id_destinasi
                  JOIN tb_hotel C ON C.id_hotel = E.id_hotel
                  JOIN tb_transportasi D ON D.id_transportasi = E.id_transportasi
                  WHERE E.id_pesan = '$id'";

    $resultSelect = mysqli_query($conn, $sqlSelect);

    if ($resultSelect) {
        $row = mysqli_fetch_assoc($resultSelect);
        if ($row) {
            $pelanggan = $row['nama_pelanggan'];
            $destinasi = $row['nama_destinasi'];
            $hotel = $row['nama_hotel'];
            $jenis = $row['jenis'];
            $perusahaan = $row['perusahaan'];
        } else {
            $error = 'Data tidak ditemukan';
        }
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}


// Function to remove specified keywords from a location name
function removeKeywords($location)
{
    $keywords = array("Kota", "Kabupaten");
    return str_replace($keywords, "", $location);
}

// Insert or Update operation
if (isset($_POST["simpan"])) {
    $idpelanggan = $_POST['id_pelanggan'];
    $iddestinasi = $_POST['id_destinasi'];
    $idhotel = $_POST['id_hotel'];
    $idtransportasi = $_POST['id_transportasi'];

    // Check if all required fields are filled
    if ($idpelanggan && $iddestinasi && $idhotel && $idtransportasi) {
        // Additional check to ensure that the selected options are not empty
        if ($idpelanggan != '' && $iddestinasi != '' && $idhotel != '' && $idtransportasi != '') {
            // Check if the hotel and destination are in the same location
            $sqlLocationCheck = "SELECT lokasi_destinasi, lokasi_hotel
                                 FROM tb_destinasi, tb_hotel
                                 WHERE id_destinasi = '$iddestinasi'
                                 AND id_hotel = '$idhotel'";
            $resultLocationCheck = mysqli_query($conn, $sqlLocationCheck);

            if ($resultLocationCheck) {
                $locationData = mysqli_fetch_assoc($resultLocationCheck);
                $lokasiDestinasi = $locationData['lokasi_destinasi'];
                $lokasiHotel = $locationData['lokasi_hotel'];

                // Remove specified keywords for comparison
                $lokasiDestinasiCleaned = removeKeywords($lokasiDestinasi);
                $lokasiHotelCleaned = removeKeywords($lokasiHotel);

                // Check if the cleaned names are the same
                $locationsMatch = strcasecmp($lokasiDestinasiCleaned, $lokasiHotelCleaned) == 0;

                if (!$locationsMatch) {
                    $error = "Lokasi Destinasi dan Hotel tidak sama.";
                } else {
                    // Continue with your insert or update logic here
                    if ($op == 'edit') {
                        $button = "Edit Pesanan";
                        $sqlUpdate = "UPDATE tb_pesan
                              SET id_pelanggan = '$idpelanggan', id_destinasi = '$iddestinasi', id_hotel = '$idhotel', id_transportasi = '$idtransportasi'
                              WHERE id_pesan = '$id'";
                        $resultUpdate = mysqli_query($conn, $sqlUpdate);

                        if ($resultUpdate) {
                            $success = "Data Berhasil diupdate";
                            header("refresh:1;url=pesan.php");
                        } else {
                            $error = "Data gagal diupdate";
                        }
                    } else {

                        $sqlInsert = "INSERT INTO tb_pesan (id_pelanggan, id_destinasi, id_hotel, id_transportasi)
                VALUES ('$idpelanggan', '$iddestinasi', '$idhotel', '$idtransportasi')";
                        $resultInsert = mysqli_query($conn, $sqlInsert);

                        if ($resultInsert) {
                            $success = "Berhasil memasukkan data baru";
                            header("refresh:1;url=pesan.php");
                        } else {
                            $customError = "Gagal memasukkan data. ";

                            // Check if the error is due to a foreign key constraint failure
                            if (strpos(mysqli_error($conn), 'foreign key constraint fails') !== false) {
                                $customError .= "Invalid destination selected.";
                            } else {
                                $customError .= "Error: " . mysqli_error($conn);
                            }

                            $error = $customError;
                        }
                    }
                }
            } else {
                $error = "Gagal melakukan pengecekan lokasi.";
            }
        } else {
            $error = "Silahkan pilih semua opsi";
        }
    } else {
        $error = "Silahkan masukkan semua data";
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
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
        opacity: .2;
    }
    </style>
</head>

<body>
    <div class="mx-auto">
        <div class="card">
            <!-- untuk memasukkan data -->
            <div class="card-header text-white bg-secondary">
                Create / Edit data Pesanan
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                    ?>
                <div class="alert alert-danger" role="alert">
                    <?= $error; ?>
                </div>
                <?php
                    // 1 detik
                }
                ?>
                <?php
                if ($success) {
                    ?>
                <div class="alert alert-success" role="alert">
                    <?= $success; ?>
                </div>

                <?php
                    // 1 detik
                }
                ?>
                <form action="" method="post">
                    <div class="mb-3 row">
                        <select name="id_pelanggan" class="form-select" aria-label="Default select example" required
                            oninvalid="this.setCustomValidity('Pilih salah satu nama!')"
                            oninput="this.setCustomValidity('')">
                            <option value="" selected>Nama Pelanggan</option>
                            <?php
                            $queryPelanggan = mysqli_query($conn, "SELECT * FROM tb_pelanggan");
                            while ($dataPelanggan = mysqli_fetch_array($queryPelanggan)) {
                                $selected = ($dataPelanggan['id_pelanggan'] == $id_pelanggan) ? 'selected' : '';
                                echo "<option value='{$dataPelanggan['id_pelanggan']}' $selected> {$dataPelanggan['nama_pelanggan']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3 row">
                        <select name="id_destinasi" class="form-select" aria-label="Default select example" required
                            oninvalid="this.setCustomValidity('Pilih salah satu Destinasi!')"
                            oninput="this.setCustomValidity('')">
                            <option value="" selected>Destinasi</option>
                            <?php
                            $queryDestinasi = mysqli_query($conn, "SELECT * FROM tb_destinasi");
                            while ($dataDestinasi = mysqli_fetch_array($queryDestinasi)) {
                                $selected = ($dataDestinasi['id_destinasi'] == $id_destinasi) ? 'selected' : '';
                                echo "<option value='{$dataDestinasi['id_destinasi']}' $selected> {$dataDestinasi['nama_destinasi']} ({$dataDestinasi['lokasi_destinasi']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3 row">
                        <select name="id_hotel" class="form-select" aria-label="Default select example" required
                            oninvalid="this.setCustomValidity('Pilih salah satu Hotel!')"
                            oninput="this.setCustomValidity('')">
                            <option value="" selected>Hotel</option>
                            <?php
                            $queryHotel = mysqli_query($conn, "SELECT * FROM tb_hotel");
                            while ($dataHotel = mysqli_fetch_array($queryHotel)) {
                                $selected = ($dataHotel['id_hotel'] == $id_hotel) ? 'selected' : '';
                                echo "<option value='{$dataHotel['id_hotel']}' $selected> {$dataHotel['nama_hotel']} ({$dataHotel['lokasi_hotel']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3 row">
                        <select name="id_transportasi" class="form-select" aria-label="Default select example" required
                            oninvalid="this.setCustomValidity('Pilih salah satu Transportasi!')"
                            oninput="this.setCustomValidity('')">
                            <option value="" selected>Transportasi</option>
                            <?php
                            $queryTransportasi = mysqli_query($conn, "SELECT * FROM tb_transportasi");
                            while ($dataTransportasi = mysqli_fetch_array($queryTransportasi)) {
                                $selected = ($dataTransportasi['id_transportasi'] == $id_transportasi) ? 'selected' : '';
                                echo "<option value='{$dataTransportasi['id_transportasi']}' $selected> {$dataTransportasi['perusahaan']} ({$dataTransportasi['jenis']})</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-12">
                        <input type="submit" name="simpan" value="<?= $button ?>" onclick="return confirm(" yakin ingin
                            menambah pesanan?)" class="btn btn-primary">
                    </div>
                </form>

            </div>
        </div>

        <!-- untuk menampilkan data -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Pesanan
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Pelanggan</th>
                            <th scope="col">Destinasi</th>
                            <th scope="col">Hotel</th>
                            <th scope="col">Transportasi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT A.nama_pelanggan, B.nama_destinasi, B.lokasi_destinasi, C.nama_hotel, C.lokasi_hotel, D.jenis, D.perusahaan, E.id_pesan
                        FROM tb_pelanggan A
                        JOIN tb_pesan E ON A.id_pelanggan = E.id_pelanggan
                        JOIN tb_destinasi B ON B.id_destinasi = E.id_destinasi
                        JOIN tb_hotel C ON C.id_hotel = E.id_hotel
                        JOIN tb_transportasi D ON D.id_transportasi = E.id_transportasi";
                        $q2 = mysqli_query($conn, $sql2);
                        $urut = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $id = $r2['id_pesan'];
                            $pelanggan = $r2['nama_pelanggan'];
                            $destinasi = $r2['nama_destinasi'];
                            $lokasiDestinasi = $r2['lokasi_destinasi']; // Add this line to retrieve the location of the destination
                            $hotel = $r2['nama_hotel'];
                            $lokasiHotel = $r2['lokasi_hotel']; // Add this line to retrieve the location of the hotel
                            $jenis = $r2['jenis'];
                            $perusahaan = $r2['perusahaan'];
                            // Rest of your code...
                            ?>
                        <tr>
                            <th scope="row">
                                <?= $urut++ ?>
                            </th>
                            <td scope="row">
                                <?= $pelanggan ?>
                            </td>
                            <td scope="row">
                                <?= "$destinasi ($lokasiDestinasi)" ?>
                            </td>
                            <td scope="row">
                                <?= "$hotel ($lokasiHotel)" ?>
                            </td>
                            <td scope="row">
                                <?= "$perusahaan($jenis)" ?>
                            </td>
                            <td scope="row">
                                <!-- <a style="display: block; margin-bottom: 5px; text-decoration: none;" href="pesan.php?op=edit&id_pesan=<?=
                                        $id; ?>"><button type="button" class="btn btn-warning">Edit</button></a> -->
                                <a href="pesan.php?op=delete&id_pesan=<?= $id ?>"
                                    onclick=" return confirm('Apakah trip benar sudah seleasi?')"><button type="button"
                                        class="btn btn-danger">Selesai</button></a>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>