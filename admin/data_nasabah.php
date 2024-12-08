<?php
session_start();
require '../db.php';

if (!isset($_SESSION['id_petugas']) || $_SESSION['id_level'] != '1') {
    header("Location: ../login.php");
    exit;
}

// Proses pencarian
$search_query = '';
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
    $result = $conn->query("SELECT * FROM nasabah WHERE nama LIKE '%$search_query%'");
} else {
    // Ambil semua nasabah jika tidak ada pencarian
    $result = $conn->query("SELECT * FROM nasabah");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>Daftar Nasabah</title>
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
    <!-- Navbar -->
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
                        <a class="nav-link active" href="data_nasabah.php">Nasabah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_petugas.php">Petugas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Daftar Nasabah -->
    <div class="container mt-5">
        <h3>Daftar Nasabah</h3>

        <!-- Baris untuk Pencarian dan Tombol Tambah -->
        <div class="row mb-3">
            <!-- Kolom untuk Pencarian -->
            <div class="col-md-8">
                <form method="POST">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Cari berdasarkan nama" value="<?= $search_query ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Nasabah -->
        <div class="mt-4"> <!-- Menambahkan margin bawah pada tabel -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No Rekening</th>
                        <th>Saldo</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>Tanggal Lahir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['no_rekening'] ?></td>
                        <td>Rp<?= number_format($row['saldo'], 0, ',', '.') ?></td>
                        <td><?= $row['alamat'] ?></td>
                        <td><?= $row['no_telepon'] ?></td>
                        <td><?= $row['tanggal_lahir'] ?></td>
                        <td>
                            <a href="laporan_transaksi.php?id=<?= $row['id_nasabah'] ?>" class="btn btn-info btn-sm">Laporan</a> 
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
