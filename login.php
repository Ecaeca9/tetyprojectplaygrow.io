<?php
session_start();
include 'controller.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi input (Cek apakah ada email dan password yang dikirim)
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email dan password harus diisi!";
        header("Location: login.php");
        exit();
    }

    // Panggil fungsi login
    if (login($email, $password)) {
        exit(); // Pastikan script berhenti setelah redirect
    } else {
        $_SESSION['error'] = "Login gagal! Periksa email dan password.";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - PlayGrow</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-5">
                    <h3 class="text-center text-primary fw-bold mb-3">Masuk ke PlayGrow</h3>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <p class="text-center mt-3">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
