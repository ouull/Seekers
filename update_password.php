<?php
session_start();
$host = "localhost"; // Sesuaikan dengan host database
$user = "root"; // Sesuaikan dengan user database
$pass = ""; // Sesuaikan dengan password database
$dbname = "lost_and_found"; // Nama database

$conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id']; // Pastikan user sudah login
    $currentPassword = $_POST['currentpassword'];
    $newPassword = $_POST['newpassword'];

    // Ambil password lama dari database
    $query = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $query->bind_param("i", $user_id);
    $query->execute();
    $result = $query->get_result();
    $user = $result->fetch_assoc();

    // Verifikasi password lama
    if (!password_verify($currentPassword, $user['password'])) {
        echo "Password lama salah!";
        exit;
    }

    // Hash password baru
    $newPasswordHashed = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password di database
    $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $update->bind_param("si", $newPasswordHashed, $user_id);

    if ($update->execute()) {
        echo "Password berhasil diubah!";
    } else {
        echo "Gagal mengubah password.";
    }
}
?>
