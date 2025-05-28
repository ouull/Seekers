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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $position = $_POST['position'];
    $departement = $_POST['departement'];
    $biro = $_POST['biro'];
    $placement = $_POST['placement'];

    $query = "UPDATE users SET fullname=?, username=?, position=?, departement=?, biro=?, placement=? WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssssssi", $fullname, $username, $position, $departement, $biro, $placement, $user_id);

    if ($stmt->execute()) {
        header("Location: profil.php?success=1");
        exit();
    } else {
        echo "Gagal memperbarui data.";
    }
}
?>
