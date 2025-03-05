<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['email'] = $email;
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == "orangtua") {
            header("Location: dashboard_parent.php");
        } elseif ($user['role'] == "pengajar") {
            header("Location: dashboard_pengajar.php");
        }
        exit();
    } else {
        echo "Email atau password salah!";
    }
}
?>
