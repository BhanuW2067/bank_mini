<?php
session_start();
require '../db.php';
// Ambil ID petugas dari parameter GET
$petugas_id = $_GET['id'];

// Hapus petugas
$conn->query("DELETE FROM petugas WHERE id_petugas = $petugas_id");
header("Location: data_petugas.php");
exit;
?>