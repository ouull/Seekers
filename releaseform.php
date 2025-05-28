<?php
session_start();
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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

// Ambil lost_item_id dari parameter URL
$lost_item_id = isset($_GET['lost_item_id']) ? (int)$_GET['lost_item_id'] : 0;

// Validasi ID
if ($lost_item_id === 0) {
    die("ID Barang tidak valid. Harap pastikan ID barang ada di URL.");
}

// Ambil data barang berdasarkan lost_item_id
$stmt = $conn->prepare("SELECT * FROM lost_items WHERE id = ?");
$stmt->bind_param("i", $lost_item_id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

// Pastikan item ditemukan
if (!$item) {
    die("Barang tidak ditemukan. Harap pastikan ID barang valid.");
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Release Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/releaseform.css"
</head>
<body>
    
        <div class="container">
            <div class="sidebar">
                <div>
                    <img alt="Logo" height="57" src="asset/Seekers.png" width="168"/>
                    <ul>
                    <li>
                        <a href="dashboard.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1 ;">
                            <img src="asset/dashcat.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                            Dashboard
                        </a>            
                    </li>        
                    <li>
                    <li>
                        <a href="catalog.php" style="display: flex; align-items: center; gap: 10px; color: #B1B1B1 ">
                            <img src="asset/catdash.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                            Catalog
                        </a>
                    </li>
                    <li>
                        <a class="active" href="claimapproval.php" style="display: flex; align-items: center; gap: 10px; ">
                            <img src="asset/approval.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                            Claim Approval
                        </a>
                    </li>
                    <li>
                        <a href="lostreport.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1">
                            <img src="asset/report2.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                            Lost Report
                        </a>
                    </li>          
                    <li>
                        <a href="chart.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1">
                            <img src="asset/chartdas.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                            Chart
                        </a>
                    </li>     
                    <li>
                        <a href="logout.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1">
                            <img src="asset/logdas.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                            Log Out
                        </a>
                    </li>     
                </ul>
                </div>
            </div>
    
        <div class="content">
            <div class="header">
            <h1>Release Form</h1>
            <div class="search">
                    <img alt="User Profile" height="35" width="35"
                    src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
                </div>
            </div>
            <div class="container">
  <div class="content">
    <div class="header">
      
                
    </div>
    <div class="form-container">
    <form action="update_rilis.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="item_id" value="<?= $row['id'] ?>">
            
    <div class="form-group">
    <label for="no_regist">No Regist:</label>
    <input type="text" id="no_regist" name="no_regist" value="<?= htmlspecialchars($item['no_regist']) ?>" readonly>
    </div>

    <div class="form-group">
    <label for="date">Tanggal Ditemukan:</label>
    <input type="text" id="date" name="date" value="<?= htmlspecialchars($item['date']) ?>" readonly >
    </div>

    <div class="form-group">
    <label for="nama_barang_text">Nama Barang:</label>
    <input type="text" name="nama_barang_text" value="<?= htmlspecialchars($item['nama_barang']) ?>" readonly>
    </div>
    
    <div class="form-group">
    <label for="categories">Kategori:</label>
    <input type="text" id="categories" name="categories" value="<?= htmlspecialchars($item['categories']) ?>" readonly >
    </div>

    <div class="form-group">
    <label for="location">Lokasi:</label>
    <input type="text" id="location" name="location" value="<?= htmlspecialchars($item['location']) ?>" readonly>
    </div>

    <div class="form-group">
    <label for="nama_pelapor">Nama Pelapor:</label>
    <input type="text" id="nama_pelapor" name="nama_pelapor" value="<?= htmlspecialchars($item['nama_pelapor']) ?>" readonly>
    </div>

    <div class="form-group">
    <label for="reporter">Posisi Petugas (Pelapor):</label>
    <input type="text" id="reporter" name="reporter" value="<?= htmlspecialchars($item['reporter']) ?>" readonly>
    </div>

    <div class="form-group">
    <label for="perilis">Nama Petugas yang Merilis:</label>
    <input id="perilis" name="perilis" type="text" required/>
    </div>

    <div class="form-group">
    <label for="tanggal_rilis">Tanggal Rilis:</label>
    <input id="tanggal_rilis" name="tanggal_rilis" type="date" required/>
    </div>

        
    <div class="form-group">
    <label for="foto_rilis">Upload Foto Rilis:</label>
    <input id="foto_rilis" name="foto_rilis" type="file" accept="image/*" required/>
    </div>

    <input type="hidden" name="lost_item_id" value="<?= $lost_item_id ?>">
    <button type="submit" class="btn">SUBMIT</button>
    <a href="claimapproval.php"><button type="button" class="btn" >KEMBALI</button></a>

    </form>
    </div>
    </div>
    </div>
    </div>



</body>

</html>
