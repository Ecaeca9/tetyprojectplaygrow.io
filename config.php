<?php
$servername = "localhost";
$username = "root";   
$password = "";      
$dbname = "playgrow"; 

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (!defined("YOUTUBE_API_KEY")) {
    define("YOUTUBE_API_KEY", "AIzaSyDj3boPJB-d8cVg3BUPn03GVk9dN6RqWUs");
}
?>


