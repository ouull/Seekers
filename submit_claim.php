<?php
// submit_claim.php
session_start();
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama_barang_id = $_POST['lost_item_id'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$item_description = $_POST['item_description'];

// Validasi apakah lost_item_id ada di tabel lost_items
$check = $conn->prepare("SELECT id FROM lost_items WHERE id = ?");
$check->bind_param("i", $nama_barang_id);
$check->execute();
$check->store_result();

    $metode = $_POST['metode_pengambilan'] ?? ''; 
    $stasiun_ambil = $_POST['stasiun_ambil'] ?? null; 
    $stasiun_kirim = $_POST['stasiun_kirim'] ?? null; 
    
    // Normalisasi agar hanya satu stasiun yang diisi 
    if ($metode === 'ambil') { 
        $stasiun_kirim = null; 
        } elseif ($metode === 'dikirim') { 
            $stasiun_ambil = null; 
            } 

if ($check->num_rows === 0) {
    die("Error: Item yang diklaim tidak ditemukan di database.");
}
$check->close();


// Upload file dan simpan nama filenya
$uploadDir = 'claim/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

function uploadFile($fileInputName, $uploadDir) {
    $filename = basename($_FILES[$fileInputName]['name']);
    $targetPath = $uploadDir . time() . '_' . $filename;
    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $targetPath)) {
        return $targetPath;
    }
    return null;
}

$id_card_image = uploadFile('id_card', $uploadDir);
$train_ticket_image = uploadFile('ticket', $uploadDir);
$proof_of_ownership = uploadFile('ownership_proof', $uploadDir);

// Validasi upload berhasil
if (!$id_card_image || !$train_ticket_image || !$proof_of_ownership) {
    die("Gagal mengunggah salah satu file. Harap coba lagi.");
}

// Masukkan data ke tabel claim_form
$stmt = $conn->prepare("
INSERT INTO claims 
(passenger_name, phone_number, id_card_image, train_ticket_image, item_description, proof_of_ownership, lost_item_id, created_at, updated_at, metode_pengambilan, stasiun_ambil, stasiun_kirim)
VALUES (?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, ?, ?, ?)
");

$stmt->bind_param("ssssssisss", $name, $phone, $id_card_image, $train_ticket_image, $item_description, $proof_of_ownership, $nama_barang_id, $metode , $stasiun_ambil, $stasiun_kirim);


if ($stmt->execute()) {
echo "<script>alert('Klaim berhasil disimpan.'); window.location.href = 'claimapproval.php';</script>";
} else {
echo "Terjadi kesalahan saat menyimpan data klaim: " . $stmt->error;
}

$stmt->close();


// Redirect ke releaseform.php dengan item_id
// Redirect berdasarkan metode pengambilan
if ($metode === 'dikirim') {
    header("Location: catalog.php");
} else {
    header("Location: releaseform.php?lost_item_id=" . $nama_barang_id);
}
exit();

?>
