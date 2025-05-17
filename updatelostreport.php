<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE lost_report SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $report_id);

    if ($stmt->execute()) {
        header("Location: lostreport.php");
        exit();
    } else {
        echo "Gagal memperbarui status.";
    }
    $stmt->close();
}

$conn->close();
?>
