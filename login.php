<?php
session_start(); // Tambahkan session_start()

$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Gunakan Prepared Statement untuk menghindari SQL Injection
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) { 
        // Simpan session
        $_SESSION['user_id'] = $row['id'];

        // Redirect ke dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Email atau Password Anda Salah'); window.location.href='login.html';</script>";
        exit();
    }
} else {
    echo "<script>alert('Akun tidak terdaftar, Silahkan Register terlebih dahulu'); window.location.href='login.html';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>
