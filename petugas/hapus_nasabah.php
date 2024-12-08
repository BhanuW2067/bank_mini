<?php
session_start();
require '../db.php';

$id_nasabah = $_GET['id'];
$conn->query("DELETE FROM nasabah WHERE id_nasabah = $id_nasabah");
header("Location: data_nasabah.php");
exit;
?>