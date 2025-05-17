<?php 
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "lost_and_found"; 
$conn = new mysqli($host, $user, $pass, $dbname); 
if ($conn->connect_error) { 
    die("Koneksi gagal: " . $conn->connect_error); 
    } // Ambil data dari form 
    
    $perilis = $_POST['perilis']; 
    $tanggal_rilis = $_POST['tanggal_rilis']; 

            
    // Proses file upload 
    $foto_rilis = ''; 
    if (isset($_FILES['foto_rilis']) && $_FILES['foto_rilis']['error'] === UPLOAD_ERR_OK) { 
        $upload_dir = 'rilis_uploads/'; 
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true); 
        $file_tmp = $_FILES['foto_rilis']['tmp_name']; 
        $file_name = basename($_FILES['foto_rilis']['name']); 
        $file_path = $upload_dir . time() . '_' . $file_name; 
        if (move_uploaded_file($file_tmp, $file_path)) { $foto_rilis = $file_path; 
        } } 
        
    // Validasi ID barang 
    $lost_item_id = isset($_POST['lost_item_id']) ? (int)$_POST['lost_item_id'] : null; 
    if ($lost_item_id === null) { die("Gagal: ID barang tidak ditemukan."); 
    } 
    // Update data ke database 
    $query = "UPDATE lost_items SET status='claimed', status_rilis='rilis', perilis=?, tanggal_rilis=?, foto_rilis=? WHERE id=?"; 
    $stmt = $conn->prepare($query); 
    $stmt->bind_param("sssi", $perilis, $tanggal_rilis, $foto_rilis, $lost_item_id);
    if ($stmt->execute()) { echo "<script>alert('Data berhasil diperbarui dan status diubah ke claimed.'); window.location.href='catalog.php';</script>"; } 
    else { echo "Error: " . $stmt->error; } $conn->close(); 
    ?>