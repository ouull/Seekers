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
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item - Lost and Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/uploadd.css" />
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
                    <a class="active" href="catalog.php" style="display: flex; align-items: center; gap: 10px;  ">
                        <img src="asset/catalog.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                        Catalog
                    </a>
                </li>
                <li>
                    <a href="claimapproval.php" style="display: flex; align-items: center; gap: 10px; color: #B1B1B1 ">
                        <img src="asset/claimappdas.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
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
    <div class="main-content">
    <div class="header1">
        <h1>Upload Item</h1>
        <div class="search">
            <img alt="User Profile" height="35" width="35"
                 src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
        </div>
        </div>
    </div>
    
    <form action="upload.php" method="POST" enctype="multipart/form-data">
    <div class="form-container">
        <div class="form-group">
            <label for="no-regist">No Regist</label>
            <input id="no-regist" name="no_regist" type="text" required/>
        </div>
    
        <div class="form-group">
            <label for="categories">Categories</label>
            <select id="categories" name="categories">
                <option value="Bag">Bag</option>
                <option value="Phone">Phone</option>
                <option value="Wallet">Wallet</option>
                <option value="Automotive">Automotive</option>
                <option value="Cosmetic">Cosmetic</option>
                <option value="Kids Stuff">Kids Stuff</option>
                <option value="FnB">FnB</option>
                <option value="footwear">Footwear</option>
                <option value="Electronic">Electronic</option>
                <option value="Accessories">Accessories</option>
            </select>
        </div>
    
        <div class="form-group">
            <label for="date">Date</label>
            <input id="date" name="date" type="date" required/>
        </div>
    
        <div class="form-group">
            <label for="nama-barang">Nama Barang</label>
            <input id="nama-barang" name="nama_barang" type="text" required/>
        </div>
    
        <div class="form-group">
            <label for="upload-picture">Upload Picture</label>
            <input id="upload-picture" name="upload_picture" type="file" accept="image/*" required/>
        </div>

        <div class="form-group">
        <div class="row">
            <div class="col">
            <label for="ticket-details">Nomor Kereta</label>
            <input id="ticket-details" name="ticket-details" placeholder="G 1238" type="text" class="form-control" />
            </div>
            <div class="col">
            <label for="ticket-details-2">Gerbong</label>
            <input id="ticket-details-2" name="ticket-details-2" placeholder="2" type="text" class="form-control" />
            </div>
            <div class="col">
            <label for="ticket-details-3">Nomor Kursi</label>
            <input id="ticket-details-3" name="ticket-details-3" placeholder="12 A" type="text" class="form-control" />
            </div>
        </div>
        </div>

        <div class="form-group">
            <label for="nama_pelapor">Nama Pelapor</label>
            <input id="nama_pelapor" name="nama_pelapor" type="text" class="form-control" />
        </div>
    
        <div class="form-group">
            <label for="reporter">Posisi Petugas</label>
            <select id="reporter" name="reporter">
                <option value="PSAP">PSAP</option>
                <option value="PSAC">PSAC</option>
                <option value="PSOT">PSOT</option>
                <option value="Security">Security</option>
            </select>
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <select id="location" name="location">
                <option value="Halim Station">Halim Station</option>
                <option value="Karawang Station">Karawang Station</option>
                <option value="Padalarang Station">Padalarang Station</option>
                <option value="Tegalluar Summarecon">Tegalluar Summarecon</option>
            </select>
        </div>
    
        <div class="form-group">
            <label for="chronology">Kronologi</label>
            <textarea id="chronology" name="chronology" placeholder="(Jika ditemukan di area Stasiun)"></textarea>
        </div>
    
        <button type="submit" class="btn">UPLOAD</button>
        <a href="catalog.php">
            <button type="button" class="btnback" style="margin-left: 10px;">KEMBALI</button>
        </a>
    </div>  
    </form>

    
    
        </div>
    </div>  
    </div>
</body>
</html>