<?php
include 'config.php';

    $nama = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $role = "parent"; // Default role untuk orang tua

    // Cek apakah email sudah terdaftar
    $cek_email = $conn->prepare("SELECT * FROM users WHERE email=?");
    if (!$cek_email) {
        die("Error dalam query: " . $conn->error);
    }
    $cek_email->bind_param("s", $email);
    $cek_email->execute();
    $result = $cek_email->get_result();

    if ($result->num_rows > 0) {
        echo "Email sudah digunakan!";
        exit();
    }

    // Simpan ke database dengan role default "orangtua"
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Error dalam query: " . $conn->error);
    }

    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
        header("Location: login.php?success=Registrasi berhasil! Silakan login.");
        exit();
    } else {
        echo "Terjadi kesalahan, coba lagi! Error: " . $stmt->error;
    }
?>
