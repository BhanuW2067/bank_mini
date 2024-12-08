<?php
session_start();
require '../db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_petugas']) || $_SESSION['id_level'] != '1') {
    header("Location: ../login.php");
    exit;
}

// Ambil ID nasabah dari parameter GET
$id_nasabah = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($id_nasabah === null) {
    header("Location: data_nasabah.php"); // Redirect jika ID tidak valid
    exit;
}

// Query untuk mendapatkan data nasabah
$result_nasabah = $conn->query("SELECT * FROM nasabah WHERE id_nasabah = $id_nasabah");
if (!$result_nasabah || $result_nasabah->num_rows === 0) {
    die("Nasabah tidak ditemukan."); // Menangani jika nasabah tidak ditemukan
}
$nasabah = $result_nasabah->fetch_assoc();

// Query untuk mendapatkan transaksi
$result_transaksi = $conn->query("SELECT * FROM transaksi WHERE id_nasabah = $id_nasabah");

// Set header untuk download file Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Transaksi_" . $nasabah['nama'] . "_" . date('Y-m-d') . ".xls");
?>

<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }
    th, td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
</style>

<table border="1">
    <thead>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 16px;">LAPORAN TRANSAKSI NASABAH</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center;">Nama Nasabah: <?php echo $nasabah['nama']; ?></th>
        </tr>
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
            <td><?php echo $no++; ?></td>
            <td><?php echo date('d/m/Y', strtotime($transaksi['tanggal'])); ?></td>
            <td><?php echo $transaksi['jenis_transaksi']; ?></td>
            <td>Rp<?php echo number_format($transaksi['jumlah'], 0, ',', '.'); ?></td>
            <td><?php echo $nasabah_tujuan['nama'] ?? 'N/A'; ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="5" style="text-align: right; padding: 10px;">
                Jakarta, <?php echo date('d F Y'); ?><br><br>
                Petugas,<br><br><br>
                <?php echo $_SESSION['nama']; ?>
            </td>
        </tr>
    </tbody>
</table>