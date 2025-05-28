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
// Ambil data user yang login
$user_id = $_SESSION['user_id'];
$query = "SELECT fullname, email, password, username, position, departement, biro, placement, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$profile_picture = !empty($user['profile_pic']) ? $user['profile_pic'] : 'profil/noprofil.png';

// Ambil data dari form
$no_regist = $_POST['no_regist'];
$categories = $_POST['categories'];
$date = $_POST['date'];
$nama_barang = $_POST['nama_barang'];
$location = $_POST['location'];
$reporter = $_POST['reporter'];
$chronology = $_POST['chronology'];
$nomor_kereta = $_POST['ticket-details'];
$gerbong = $_POST['ticket-details-2'];
$kursi = $_POST['ticket-details-3'];
$nama_pelapor = $_POST['nama_pelapor'];

// Upload gambar
$target_dir = "uploads/"; // Pastikan folder ini ada
$target_file = $target_dir . basename($_FILES["upload_picture"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Validasi file gambar
$allowed_types = ["jpg", "jpeg", "png", "gif"];
if (!in_array($imageFileType, $allowed_types)) {
    echo "<script>alert('Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.'); window.history.back();</script>";
    exit;
}

// Simpan gambar ke folder
if (move_uploaded_file($_FILES["upload_picture"]["tmp_name"], $target_file)) {
    $image_path = $target_file;

    // Simpan data ke database
    $sql = "INSERT INTO lost_items (no_regist, categories, date, nama_barang, location, reporter, chronology, image, nomor_kereta, gerbong, kursi, nama_pelapor)
            VALUES ('$no_regist', '$categories', '$date', '$nama_barang', '$location', '$reporter', '$chronology', '$image_path', '$nomor_kereta', '$gerbong', '$kursi', '$nama_pelapor')";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diunggah!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Maaf, terjadi kesalahan saat mengunggah file.";
}

$conn->close();


// Proses upload dan penyimpanan data ke database
// (Pastikan semua proses upload sudah berjalan dengan benar)

echo "Data berhasil diunggah!"; // Bisa dihapus jika tidak diperlukan

// Redirect ke catalog.html setelah upload berhasil
header("Location: catalog.php");
exit();
?>