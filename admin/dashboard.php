<?php
session_start();
require '../db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['id_petugas']) || $_SESSION['id_level'] != '1') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link href="../assets/css/bootstrap-icons.css" rel="stylesheet">
    <title>Dashboard Admin</title>
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

        .container {
            max-width: 1200px;
        }

        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            transition: transform 0.3s ease;
            background-color: #ffffff;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .card-body {
            padding: 2rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .card-text {
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
        }

        .btn-primary,
        .btn-secondary {
            font-size: 1rem;
            border-radius: 30px;
            padding: 10px 20px;
            transition: background-color 0.3s ease-in-out;
        }

        .btn-primary:hover,
        .btn-secondary:hover {
            background-color: #0056b3;
        }

        .lead {
            font-size: 1.2rem;
            color: #555;
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
                        <a class="nav-link" href="data_petugas.php">Petugas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center">Selamat Datang, Admin!</h1>
        <p class="lead text-center">Gunakan menu ini untuk mengelola data nasabah dan menambahkan petugas.</p>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Lihat Nasabah</h5>
                        <p class="card-text">Lihat dan kelola data nasabah yang sudah terdaftar.</p>
                        <a href="data_nasabah.php" class="btn btn-secondary">Lihat Nasabah</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tambah Petugas</h5>
                        <p class="card-text">Tambahkan petugas baru untuk mengelola transaksi.</p>
                        <a href="tambah_petugas.php" class="btn btn-primary">Tambah Petugas</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
