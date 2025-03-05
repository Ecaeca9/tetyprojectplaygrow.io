<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-body p-5">
                    <h3 class="text-center text-primary fw-bold mb-3">Daftar Akun</h3>
                    <form action="register.php" method="POST">
                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                    </form>
                    <p class="text-center mt-3">Sudah punya akun? <a href="login.php">Login di sini</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
