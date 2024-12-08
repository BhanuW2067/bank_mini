<?php
session_start();
require '../db.php';

// Cek apakah pengguna sudah login dan memiliki akses yang tepat
if (!isset($_SESSION['id_petugas']) || $_SESSION['id_level'] != '1') {
    header("Location: ../login.php");
    exit;
}

// Proses penambahan petugas
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_petugas = $_POST['nama_petugas'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Hash password menggunakan MD5
    $id_level = $_POST['id_level'];

    // Insert data petugas
    $conn->query("INSERT INTO petugas (nama, username, password, id_level) VALUES ('$nama_petugas', '$username', '$password', '$id_level')");
    header("Location: data_petugas.php"); // Redirect setelah menambah
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>Tambah Petugas</title>
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
                        <a class="nav-link" href="data_nasabah.php">Nasabah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="data_petugas.php">Petugas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1>Tambah Petugas</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nama_petugas" class="form-label">Nama Petugas</label>
                <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required>
            </div>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="id_level" class="form-label">Level</label>
                <select class="form-select" id="id_level" name="id_level" required>
                    <option value="1">Admin</option>
                    <option value="2">Petugas</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Petugas</button>
            <a href="data_petugas.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>

