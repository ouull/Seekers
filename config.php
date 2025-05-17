<?php
$host = "localhost";
$user = "root";  // Ganti dengan username MySQL-mu
$pass = "";      // Ganti dengan password MySQL-mu
$dbname = "lost_and_found";

// Koneksi ke database
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
