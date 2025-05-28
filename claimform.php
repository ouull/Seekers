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

// Ambil data barang dari tabel lost_items
$items_query = "SELECT id, nama_barang FROM lost_items";
$items_result = $conn->query($items_query);

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$item_query = $conn->prepare("SELECT * FROM lost_items WHERE id = ?");
$item_query->bind_param("i", $id);
$item_query->execute();
$item_result = $item_query->get_result();
$item = $item_result->fetch_assoc();

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style/claimformm.css"
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
            
            </div>
            <div class="container">
  <div class="content">
    <div class="header">
      <h1>Claim Form</h1>
                <div class="search">
                    <img alt="User Profile" height="35" width="35"
                    src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
                </div>
    </div>
    <div class="form-container">
      <form action="submit_claim.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="lost_item_id" value="<?= htmlspecialchars($id) ?>">
        <div class="form-section">

          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required/>
          </div>

          <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required/>
          </div>

          <div class="form-group">
            <label for="id_card">Upload ID Card</label>
            <input type="file" id="id_card" name="id_card" accept="image/*" required/>
          </div>

          <div class="form-group">
            <label for="ticket">Upload Train Ticket</label>
            <input type="file" id="ticket" name="ticket" accept="image/*" required/>
          </div>

          <div class="form-group">
            <label for="item_description">Item Description</label>
            <textarea id="item_description" name="item_description" rows="4" required></textarea>
          </div>

          <div class="form-group">
            <label for="ownership_proof">Upload Proof of Ownership</label>
            <input type="file" id="ownership_proof" name="ownership_proof" accept="image/*" required/>
          </div>

          <div class="form-group"> 
            <label for="metode_pengambilan">Metode Pengambilan:</label> 
            <select id="metode_pengambilan" name="metode_pengambilan"  class='status-select' onchange="togglePengambilan()" required> 
            <option value="">-- Pilih Metode --</option> 
            <option value="ambil">Diambil di Stasiun</option> 
            <option value="dikirim">Dikirim</option> 
            </select> 
          </div>

          <div class="form-group" id="stasiun_ambil_group" style="display:none;"> 
            <label for="stasiun_ambil">Pilih Stasiun Pengambilan:</label> 
            <select id="stasiun_ambil" name="stasiun_ambil" class='status-select' > 
            <option value="">-- Pilih Stasiun --</option> 
            <option value="Halim Station">Halim Station</option>
            <option value="Karawang Station">Karawang Station</option>
            <option value="Padalarang Station">Padalarang Station</option>
            <option value="Tegalluar Summarecon">Tegalluar Summarecon</option>
            </select> 
          </div> 
          
          <div class="form-group" id="stasiun_kirim_group" style="display:none;"> 
            <label for="stasiun_kirim">Pilih Stasiun Tujuan Pengiriman:</label> 
            <select id="stasiun_kirim" name="stasiun_kirim" class='status-select'> 
            <option value="">-- Pilih Stasiun --</option> 
            <option value="Halim Station">Halim Station</option>
            <option value="Karawang Station">Karawang Station</option>
            <option value="Padalarang Station">Padalarang Station</option>
            <option value="Tegalluar Summarecon">Tegalluar Summarecon</option>
            </select> 
          </div>


          <div class="form-group">
            <button type="submit" class="btn">Submit Claim</button>
            <a href="catalog.php"><button type="button" class="btn">Kembali</button></a>
          </div>
        </div>
      </form>
            </div>
                    


        </div>
    </div>
    </div>
    <script> 
   function togglePengambilan() { 
    const metode = document.getElementById('metode_pengambilan').value; 
    document.getElementById('stasiun_ambil_group').style.display = (metode === 'ambil') ? 'block' : 'none';
    document.getElementById('stasiun_kirim_group').style.display = (metode === 'dikirim') ? 'block' : 'none'; } 
    </script>

</body>
</html>