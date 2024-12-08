<?php
session_start();
require '../db.php';

if (!isset($_SESSION['id_petugas']) || $_SESSION['id_level'] != '2') {
    header("Location: ../login.php");
    exit;
}

$id_nasabah = $_GET['id'];
$result_nasabah = $conn->query("SELECT * FROM nasabah WHERE id_nasabah = $id_nasabah");
$nasabah = $result_nasabah->fetch_assoc();

// Filter tanggal
$start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
$end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';

// Query untuk mendapatkan transaksi
$query = "SELECT * FROM transaksi WHERE id_nasabah = $id_nasabah";
if ($start_date && $end_date) {
    $query .= " AND tanggal BETWEEN '$start_date' AND '$end_date'";
}
$result_transaksi = $conn->query($query);

// Hapus laporan
if (isset($_POST['delete'])) {
    $transaksi_id = $_POST['transaksi_id'];
    $conn->query("DELETE FROM transaksi WHERE id_transaksi = $transaksi_id");
    header("Location: laporan_transaksi.php?id=$id_nasabah"); // Redirect setelah menghapus
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Nasabah</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }
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
        h3 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:hover {
            background-color: #ddd;
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

<div class="container mt-4">
    <!-- Form Filter Tanggal -->
    <form method="POST" class="mb-4 d-flex justify-content-center">
        <div class="input-group me-2" style="max-width: 300px;">
            <span class="input-group-text">Dari Tanggal</span>
            <input type="date" name="start_date" value="<?= $start_date ?>" class="form-control">
        </div>
        <div class="input-group me-2" style="max-width: 300px;">
            <span class="input-group-text">Sampai Tanggal</span>
            <input type="date" name="end_date" value="<?= $end_date ?>" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <!-- Tombol Cetak dan Unduh -->
    <div class="d-flex justify-content-center mb-4">
        <a href="#" onclick="printReport(<?= $id_nasabah ?>)" class="btn btn-success me-2">Cetak Laporan</a>
        <a href="laporan_excel.php?id=<?= $id_nasabah ?>" class="btn btn-info">Unduh Laporan Excel</a>
    </div>

    <h3>Laporan Transaksi untuk Nasabah: <?= htmlspecialchars($nasabah['nama']) ?></h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Transaksi</th>
                <th>Jumlah</th>
                <th>Nasabah Tujuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($transaksi = $result_transaksi->fetch_assoc()): 
                $id_nasabah_tujuan = isset($transaksi['id_nasabah_tujuan']) ? $transaksi['id_nasabah_tujuan'] : null;
                $nasabah_tujuan = null;

                if ($id_nasabah_tujuan) {
                    $result_tujuan = $conn->query("SELECT nama FROM nasabah WHERE id_nasabah = $id_nasabah_tujuan");
                    if ($result_tujuan && $result_tujuan->num_rows > 0) {
                        $nasabah_tujuan = $result_tujuan->fetch_assoc();
                    }
                }
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d/m/Y', strtotime($transaksi['tanggal'])) ?></td>
                <td><?= htmlspecialchars($transaksi['jenis_transaksi']) ?></td>
                <td>Rp<?= number_format($transaksi['jumlah'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($nasabah_tujuan['nama'] ?? 'N/A') ?></td>
                <td>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="transaksi_id" value="<?= $transaksi['id_transaksi'] ?>">
                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
function printReport(id) {
    var printWindow = window.open('cetak_laporan.php?id=' + id, '_blank');
    printWindow.onload = function() {
        printWindow.print();
    };
}
</script>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>