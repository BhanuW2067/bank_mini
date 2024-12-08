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

$result_transaksi = $conn->query("SELECT * FROM transaksi WHERE id_nasabah = $id_nasabah");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Nasabah</title>
    <style>
        /* Gaya untuk cetak */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        h3 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h3>Laporan Transaksi untuk Nasabah: <?= $nasabah['nama'] ?></h3>
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jenis Transaksi</th>
                <th>Jumlah</th>
                <th>Nasabah Tujuan</th>
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
                <td><?= $transaksi['tanggal'] ?></td>
                <td><?= $transaksi['jenis_transaksi'] ?></td>
                <td>Rp<?= number_format($transaksi['jumlah'], 0, ',', '.') ?></td>
                <td><?= $nasabah_tujuan['nama'] ?? 'N/A' ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>