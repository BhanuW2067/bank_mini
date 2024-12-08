<?php
session_start();
require '../db.php';

// Hapus transaksi
if (isset($_POST['transaksi_id'])) {
    $transaksi_id = $_POST['transaksi_id'];
    $conn->query("DELETE FROM transaksi WHERE id_transaksi = $transaksi_id");
    
    // Redirect kembali ke halaman laporan transaksi
    $id_nasabah = $_POST['id_nasabah']; // Ambil id_nasabah dari POST
    header("Location: laporan_transaksi.php?id=$id_nasabah");
    exit();
}
?> 