<?php
session_start();
require 'db.php'; // Pastikan koneksi database sudah ada

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form login
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Pastikan username aman dari SQL injection dengan cara membatasi karakter
    $username = $conn->real_escape_string($username);

    // Hash password menggunakan MD5
    $hashed_password = md5($password);

    // Query langsung untuk mengecek petugas berdasarkan username
    $result = $conn->query("SELECT * FROM petugas WHERE username = '$username' AND password = '$hashed_password'");

    // Jika username ditemukan
    if ($result && $result->num_rows === 1) {
        $petugas = $result->fetch_assoc();

        // Simpan informasi session
        $_SESSION['id_petugas'] = $petugas['id_petugas'];
        $_SESSION['id_level'] = $petugas['id_level'];
        $_SESSION['nama'] = $petugas['nama']; // Menyimpan nama petugas

        // Arahkan berdasarkan level pengguna
        if ($_SESSION['id_level'] === '1') {
            header("Location: admin/dashboard.php");
            exit;
        } elseif ($_SESSION['id_level'] === '2') {
            header("Location: petugas/dashboard.php");
            exit;
        }
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <title>Bank Mini - Login</title>
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h3 class="card-title text-center">Login</h3>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>