<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "lost_and_found";
$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi gagal."]));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("DELETE FROM lost_items WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Gagal menghapus item."]);
    }

    $stmt->close();
    $conn->close();
}
?>
