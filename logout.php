<?php
session_start();
include 'controller.php';

if (isset($_SESSION['id'])) {
    $user_id = $_SESSION['id'];
    endVisit($user_id);  // Simpan waktu logout ke database
}

session_destroy();
header("Location: login.php");
exit();
?>
