<?php
session_start();
require '../db.php';

if (!isset($_SESSION['id_petugas']) || $_SESSION['id_level'] != '2') {
    header("Location: ../login.php");
    exit;
}

$id_nasabah = $_GET['id'];
$result = $conn->query("SELECT * FROM nasabah WHERE id_nasabah = $id_nasabah");
$nasabah = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $no_rekening = $_POST['no_rekening'];
    $saldo = $_POST['saldo'];
    $alamat = $_POST['alamat'];
    $no_telepon = $_POST['no_telepon'];
    $tanggal_lahir = $_POST['tanggal_lahir'];

    $sql = "UPDATE nasabah SET nama='$nama', no_rekening='$no_rekening', saldo='$saldo', alamat='$alamat', no_telepon='$no_telepon', tanggal_lahir='$tanggal_lahir' WHERE id_nasabah='$id_nasabah'";

    if ($conn->query($sql)) {
        echo "<script>
                alert('Nasabah berhasil diperbarui!');
                window.location.href = 'data_nasabah.php'; // Ganti dengan URL halaman data nasabah
              </script>";
    } else {
        $error = "Terjadi kesalahan saat memperbarui nasabah: " . $conn->error; // Menampilkan error dari query
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nasabah</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        .navbar {
            background-color: #0069d9 !important;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease-in-out;
        }

        .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }

        .navbar-nav .nav-item.active .nav-link {
            background-color: #0056b3;
            border-radius: 5px;
        }

        .navbar-nav .nav-item .nav-link.text-danger {
            font-size: 1.1rem;
            font-weight: bold;
            background-color: #ff4d4d;
            border-radius: 25px;
            padding: 8px 16px;
            transition: background-color 0.3s, transform 0.3s;
        }

        .navbar-nav .nav-item .nav-link.text-danger:hover {
            background-color: #e60000;
            transform: scale(1.05);
        }
        
        .navbar-nav .nav-item .nav-link.text-danger i {
            margin-right: 8px;
        }

        .navbar-toggler-icon {
            background-color: white;
        }

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Bank Mini</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_nasabah.php">Nasabah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="transaksi.php">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h3>Edit Nasabah</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?= $nasabah['nama'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="no_rekening" class="form-label">Nomor Rekening</label>
                <input type="text" class="form-control" id="no_rekening" name="no_rekening" value="<?= $nasabah['no_rekening'] ?>" required>
            </div>
            <div class="mb-3">
            <label for="saldo" class="form-label">Saldo</label>
            <input type="text" class="form-control" id="saldo" name="saldo" value="<?= $nasabah['saldo'] ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="alamat" class="form-label">Alamat</label>
                <textarea class="form-control" id="alamat" name="alamat" rows="2" required><?= $nasabah['alamat'] ?></textarea>
            </div>
            <div class="mb-3">
                <label for="no_telepon" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="<?= $nasabah['no_telepon'] ?>" required>
            </div>
            <div class="mb-3">
                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?= $nasabah['tanggal_lahir'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Edit Nasabah</button>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
