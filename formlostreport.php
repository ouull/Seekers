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

// Ambil data user
$user_id = $_SESSION['user_id'];
$query = "SELECT fullname, email, password, username, position, departement, biro, placement, profile_pic FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$profile_picture = !empty($user['profile_pic']) ? $user['profile_pic'] : 'profil/noprofil.png';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pelapor = $_POST['nama_pelapor'] ?? '';
    $kontak = $_POST['kontak'] ?? '';
    $nama_barang = $_POST['nama_barang'] ?? '';
    $ciri_ciri = $_POST['ciri_ciri'] ?? '';
    $lokasi_kehilangan = $_POST['lokasi_kehilangan'] ?? '';
    $kronologi = $_POST['kronologi'] ?? '';
    $date = date('Y-m-d');
// Query SQL
$sql = "INSERT INTO lost_report (nama_pelapor, kontak, nama_barang, ciri_ciri, lokasi_kehilangan, kronologi, tanggal)
        VALUES (?, ?, ?, ?, ?, ?, ?)";

// Prepare
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);  // debug di sini
}

// Bind dan eksekusi
$stmt->bind_param("sssssss", $nama_pelapor, $kontak, $nama_barang, $ciri_ciri, $lokasi_kehilangan, $kronologi, $date);

if ($stmt->execute()) {
    echo "<script>alert('Data berhasil diunggah!'); window.location.href='lostreport.php';</script>";
} else {
    echo "Gagal mengirim laporan: " . $stmt->error;
}
}
$stmt->close();
$conn->close();



?>


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Lost Report - Lost and Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/formlostreport11.css" />
</head>
<body>
    <div class="container">
        <div class="sidebar">
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
                <a href="catalog.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1">
                    <img src="asset/catdash.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                    Catalog
                </a>
            </li>
            <li>
                <a href="claimapproval.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1">
                    <img src="asset/claimappdas.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                    Claim Approval
                </a>
            </li>
            <li>
                <a class="active" href="lostreport.php" style="display: flex; align-items: center; gap: 10px; ">
                    <img src="asset/report1.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
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
        <div class="main-content">
            <div class="header">
            <h1>Form Lost Report</h1>
                <div class="search">
                    <img alt="User Profile" height="35" width="35"
                    src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
                </div>
            </div>

    <form action="" method="POST" >
    <div class="form-container">
        <div class="form-group">
            <label for="nama_pelapor">Nama Pelapor</label>
            <input id="nama_pelapor" name="nama_pelapor" type="text" required/>
        </div>

        <div class="form-group">
            <label for="kontak">Kontak</label>
            <input id="kontak" name="kontak" type="text" required/>
        </div>
    
        <div class="form-group">
            <label for="nama_barang">Nama Barang</label>
            <input id="nama_barang" name="nama_barang" type="text" required/>
        </div>

        <div class="form-group">
            <label for="ciri_ciri">Ciri-ciri</label>
            <input id="ciri_ciri" name="ciri_ciri" type="text" required/>
        </div>        
    
        <div class="form-group">
            <label for="lokasi_kehilangan">Lokasi Kehilangan</label>
            <select id="lokasi_kehilangan" name="lokasi_kehilangan">
                <option value="Halim Station">Halim Station</option>
                <option value="Karawang Station">Karawang Station</option>
                <option value="Padalarang Station">Padalarang Station</option>
                <option value="Tegalluar Summarecon">Tegalluar Summarecon</option>
            </select>
        </div>
    
        <div class="form-group">
            <label for="kronologi">Kronologi</label>
            <textarea id="kronologi" name="kronologi" required placeholder="Barang hilang di waiting hall"></textarea>
        </div>

        <div class="form-group">
            <label for="date">Date</label>
            <input id="date" name="date" type="date" required/>
        </div>
    
        <button type="submit" class="btn">UPLOAD</button>
        <a href="lostreport.php">
            <button type="button" class="btnback" style="margin-left: 10px;">KEMBALI</button>
        </a>
    </div>
        
    </form>
    
    
        </div>
    </div>  
    </div>
    </body>
</html>