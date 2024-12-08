<?php
session_start();
require '../db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_petugas']) || $_SESSION['id_level'] != '2') {
    header("Location: ../login.php");
    exit;
}

// Ambil daftar nasabah untuk dropdown
$result_nasabah = $conn->query("SELECT id_nasabah, nama, no_rekening, saldo FROM nasabah");
$nasabah_list = [];
if ($result_nasabah) {
    while ($row = $result_nasabah->fetch_assoc()) {
        $nasabah_list[] = $row;
    }
}

// Proses transaksi jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_nasabah = $_POST['id_nasabah'];
    $id_nasabah_tujuan = $_POST['id_nasabah_tujuan'] ?: null; // Jika tidak ada, set null
    $jenis_transaksi = $_POST['jenis_transaksi'];
    $jumlah = $_POST['jumlah'];

    // Simpan transaksi ke database
    $query = "INSERT INTO transaksi (id_nasabah, id_nasabah_tujuan, jenis_transaksi, jumlah) 
              VALUES ($id_nasabah, " . ($id_nasabah_tujuan ? $id_nasabah_tujuan : 'NULL') . ", '$jenis_transaksi', $jumlah)";
    $conn->query($query);

    // Update saldo nasabah jika diperlukan
    if ($jenis_transaksi == 'setor') {
        $conn->query("UPDATE nasabah SET saldo = saldo + $jumlah WHERE id_nasabah = $id_nasabah");
    } elseif ($jenis_transaksi == 'tarik') {
        $conn->query("UPDATE nasabah SET saldo = saldo - $jumlah WHERE id_nasabah = $id_nasabah");
    } elseif ($jenis_transaksi == 'transfer' && $id_nasabah_tujuan) {
        // Transfer ke nasabah tujuan
        $conn->query("UPDATE nasabah SET saldo = saldo - $jumlah WHERE id_nasabah = $id_nasabah");
        $conn->query("UPDATE nasabah SET saldo = saldo + $jumlah WHERE id_nasabah = $id_nasabah_tujuan");
    }

    header("Location: data_nasabah.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>Transaksi</title>
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
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="data_nasabah.php">Nasabah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="transaksi.php">Transaksi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Proses Transaksi</h1>
        <form method="POST" action="transaksi.php">
            <div class="mb-3">
                <label for="id_nasabah" class="form-label">Pilih Nasabah</label>
                <select class="form-select" id="id_nasabah" name="id_nasabah" required>
                    <option value="">-- Pilih Nasabah --</option>
                    <?php foreach ($nasabah_list as $nasabah): ?>
                        <option value="<?= $nasabah['id_nasabah'] ?>">
                            <?= $nasabah['nama'] ?> - No Rekening: <?= $nasabah['no_rekening'] ?> - Saldo: <?= number_format($nasabah['saldo'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="jenis_transaksi" class="form-label">Jenis Transaksi</label>
                <select class="form-select" id="jenis_transaksi" name="jenis_transaksi" required>
                    <option value="setor">Setoran</option>
                    <option value="tarik">Penarikan</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="jumlah" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="jumlah" name="jumlah" required>
            </div>
            <div class="mb-3" id="transfer-section" style="display: none;">
                <label for="id_nasabah_tujuan" class="form-label">Pilih Nasabah Tujuan</label>
                <select class="form-select" id="id_nasabah_tujuan" name="id_nasabah_tujuan">
                    <option value="">-- Pilih Nasabah Tujuan --</option>
                    <?php foreach ($nasabah_list as $nasabah): ?>
                        <option value="<?= $nasabah['id_nasabah'] ?>">
                            <?= $nasabah['nama'] ?> - No Rekening: <?= $nasabah['no_rekening'] ?> - Saldo: <?= number_format($nasabah['saldo'], 2) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Proses Transaksi</button>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Menampilkan input ID Nasabah Tujuan hanya untuk jenis transaksi transfer
        document.getElementById('jenis_transaksi').addEventListener('change', function() {
            const transferSection = document.getElementById('transfer-section');
            if (this.value === 'transfer') {
                transferSection.style.display = 'block';
            } else {
                transferSection.style.display = 'none';
            }
        });
    </script>
</body>
</html>