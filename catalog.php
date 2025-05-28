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

$dateOptions = [];
$dateQuery = "SELECT DISTINCT date FROM lost_items ORDER BY date DESC";
$dateResult = $conn->query($dateQuery);
while ($dateRow = $dateResult->fetch_assoc()) {
    $dateOptions[] = $dateRow['date'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalog - Lost and Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/cataloggg.css" />
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
                <a class="active" href="catalog.php" style="display: flex; align-items: center; gap: 10px; ">
                    <img src="asset/catalog.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
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
        <div class="main-content">
            <div class="header">
            <h1>Catalog</h1>
                <div class="search">
                    <img alt="User Profile" height="35" width="35"
                    src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
                </div>
            </div>
            <div class="filter-bar">
            <div class="filter-options">
                    <label>
                        <select id="filter-location">
                            <option value="">All Locations</option>
                            <option value="Halim Station">Halim Station</option>
                            <option value="Karawang Station">Karawang Station</option>
                            <option value="Padalarang Station">Padalarang Station</option>
                            <option value="Tegalluar Summarecon">Tegalluar Summarecon</option>
                        </select>
                    </label>
                    <label>
                        <select id="filter-category">
                            <option value="">All Categories</option>
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
                    </label>
                    <label>
                    <select id="filter-date">
                        <option value="">All Dates</option>
                        <?php foreach ($dateOptions as $date): ?>
                            <option value="<?= htmlspecialchars($date) ?>"><?= date('d M Y', strtotime($date)) ?></option>
                        <?php endforeach; ?>
                    </select>
                     </label>

                    <label>
                        <select id="filter-status">
                            <option value="">All Status</option>
                            <option value="claimed">Claimed</option>
                            <option value="unclaimed">Unclaimed</option>
                        </select>
                    </label>
            </div>


            <button class="add-catalog" a >
            Register <i class="fas fa-plus"></i>
            </button>
            </div>
            
        <div class="catalog-list">
                    <?php

                    $sql = "SELECT * FROM lost_items ORDER BY date DESC";
                    $result = $conn->query($sql);
                    
                    while ($row = $result->fetch_assoc()) {
                        $status = trim($row["status"]); // Menghilangkan spasi sebelum/akhir
                        $statusClass = ($status === "claimed") ? "claimed" : "unclaimed";
                        $statusColor = ($status === "claimed") ? "#228B22" : "#d9534f";

                        echo '<div class="catalog-item"
                            data-id="'.$row["id"].'" 
                            data-location="'.htmlspecialchars($row["location"]).'" 
                            data-category="'.htmlspecialchars($row["categories"]).'" 
                            data-status="'.htmlspecialchars($row["status"]).'"
                            data-date="'.htmlspecialchars($row["date"]).'">
                            <div class="item-info">
                                <img alt="Item Image" height="50" src="'.$row["image"].'" width="50"/>
                                <div><span>No Regist</span><strong>'.$row["no_regist"].'</strong></div>
                                <div><span>Date</span><strong>'.$row["date"].'</strong></div>
                                <div><span>Name</span><strong>'.$row["nama_barang"].'</strong></div>
                                <div><span>Categories</span><strong>'.$row["categories"].'</strong></div>
                                <div><span>Location</span><strong>'.$row["location"].'</strong></div>
                                <div><span>Nama Pelapor</span><strong>'.$row["nama_pelapor"].'</strong></div>
                                <div><span>Posisi Petugas</span><strong>'.$row["reporter"].'</strong></div>
                            </div>
                           <div class="item-actions">';
                           
     // Tambahkan tombol CLAIM jika UNCLAIMED
    if (strtolower($status) === "unclaimed") {
        echo '<form action="claimform.php" method="GET" style="margin-top: 5px;">
                <input type="hidden" name="id" value="'.htmlspecialchars($row["id"]).'">
                <button type="submit" class="claim-form">CLAIM</button>
              </form>';
    }

    // Tombol status (claimed/unclaimed)
    echo '<button class="'.$statusClass.'" style="background-color: '.$statusColor.';">'.htmlspecialchars($row["status"]).'</button>';

    echo '<i class="fas fa-trash"></i>
        </div> 
    </div>';
                    }

                    $conn->close();
                    ?>     
                    
                    
            </div>
        </div>
    </div>
    <script>
  
    document.querySelector('.claim-form').addEventListener('click', function() {
    window.location.href = 'claimform.php';
    });

    document.querySelector('.add-catalog').addEventListener('click', function() {
    window.location.href = 'upload1.php';
    });
    
    function filterCatalog() {
        const selectedLocation = document.getElementById('filter-location').value.toLowerCase();
        const selectedCategory = document.getElementById('filter-category').value.toLowerCase();
        const selectedStatus = document.getElementById('filter-status').value.toLowerCase();
        const selectedDate = document.getElementById('filter-date').value;

        const items = document.querySelectorAll('.catalog-item');

        items.forEach(item => {
            const itemLocation = item.dataset.location.toLowerCase();
            const itemCategory = item.dataset.category.toLowerCase();
            const itemStatus = item.dataset.status.toLowerCase();
            const itemDate = item.dataset.date;

            const matchLocation = !selectedLocation || itemLocation === selectedLocation;
            const matchCategory = !selectedCategory || itemCategory === selectedCategory;
            const matchStatus = !selectedStatus || itemStatus === selectedStatus;
            const matchDate = !selectedDate || itemDate === selectedDate;

            if (matchLocation && matchCategory && matchStatus && matchDate) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    document.getElementById('filter-location').addEventListener('change', filterCatalog);
    document.getElementById('filter-category').addEventListener('change', filterCatalog);
    document.getElementById('filter-status').addEventListener('change', filterCatalog);
    document.getElementById('filter-date').addEventListener('change', filterCatalog);

    document.querySelectorAll('.catalog-item .fa-trash').forEach(icon => {
    icon.addEventListener('click', function () {
        const item = this.closest('.catalog-item');
        const itemId = item.dataset.id;

        if (confirm("Yakin ingin menghapus item ini?")) {
            fetch('delete_item.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(itemId)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    item.remove(); // Hapus dari tampilan
                    alert("Item berhasil dihapus.");
                } else {
                    alert("Gagal menghapus item: " + (data.message || "Unknown error"));
                }
            })
            .catch(err => {
                console.error("Error:", err);
                alert("Terjadi kesalahan.");
                        });
                    }
                });
            });

    </script>

    </script>       
    </body>
</html>