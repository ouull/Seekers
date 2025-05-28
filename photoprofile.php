<?php
session_start();
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["profile_pic"])) {
    $user_id = $_SESSION['user_id'];
    $uploadDir = "profil/";
    $fileName = basename($_FILES["profile_pic"]["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Validasi file
    $allowedTypes = ['jpg', 'jpeg', 'png'];
    if (in_array(strtolower($fileType), $allowedTypes)) {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $targetFilePath)) {
            // Update database
            $query = "UPDATE users SET profile_pic = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $targetFilePath, $user_id);
            $stmt->execute();

            // Perbarui session agar langsung terlihat
            $_SESSION['profile_pic'] = $targetFilePath;

            header("Location: profil.php");
            exit();
        } else {
            echo "Gagal mengunggah gambar.";
        }
    } else {
        echo "Format file tidak didukung.";
    }
}
?>
