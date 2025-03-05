<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] != 'pengajar') {
    header("Location: login.php");
    exit();
}
?>
