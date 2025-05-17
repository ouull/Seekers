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

$queryClaims = "SELECT 
    c.claim_id, 
    c.item_description, 
    u.status, 
    c.created_at, 
    c.passenger_name, 
    u.nama_barang, 
    u.id AS lost_item_id,
    u.status_rilis
FROM claims c
JOIN lost_items u ON c.lost_item_id = u.id
ORDER BY c.created_at DESC;
";

$resultClaims = $conn->query($queryClaims);

$queryTotalApproved = "SELECT COUNT(*) AS total_approved FROM lost_items WHERE status = 'claimed'";
$resultTotalApproved = $conn->query($queryTotalApproved);
$totalApproved = $resultTotalApproved->fetch_assoc()['total_approved'];

$queryTotalRejected = "SELECT COUNT(*) AS total_rejected FROM lost_items WHERE status = 'unclaimed'";
$resultTotalRejected = $conn->query($queryTotalRejected);
$totalRejected = $resultTotalRejected->fetch_assoc()['total_rejected'];

$querylocation = "SELECT location, COUNT(*) AS total_approved 
    FROM lost_items 
    WHERE status = 'claimed' 
    GROUP BY location";
$resultlocation = $conn->query($querylocation);


// Buat array untuk menyimpan hasil query
$locationData = [];
while ($row = $resultlocation->fetch_assoc()) {
    $locationData[$row['location']] = $row['total_approved'];
}

$queryChart = "
    SELECT 
        SUM(CASE WHEN status = 'claimed' THEN 1 ELSE 0 END) AS claimed_count,
        SUM(CASE WHEN status = 'unclaimed' THEN 1 ELSE 0 END) AS unclaimed_count
    FROM lost_items
";

$resultChart = $conn->query($queryChart);
$rowChart = $resultChart->fetch_assoc();

$approved = $rowChart['claimed_count'] ?? 0;
$rejected = $rowChart['unclaimed_count'] ?? 0;


?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claim Approval - Lost and Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style/claimapprovalll.css" />
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

        <div class="main-content">
            <div class="header">
            <h1>Claim Approval</h1>
                <div class="search">
                    <img alt="User Profile" height="35" width="35"
                    src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
                </div>
            </div>

            <div class="content">
                <div class="left-panel">
                <h2>All Station</h2>
                <div class="cardTotal">
                <div class="claim-box">
                    <h2><?= htmlspecialchars($totalApproved) ?></h2>
                    <p>Total Claim</p>
                </div>
                <div class="claim-box">
                    <h2><?= htmlspecialchars($totalRejected) ?></h2>
                    <p>Total Unclaimed</p>
                </div>
                </div>


                    <div class="card">
                        <div class="stations" style="display: flex; justify-content: space-around;">
                    <?php
                    // Daftar stasiun yang ingin ditampilkan
                    $locations = ['Halim Station', 'Padalarang Station', 'Tegalluar Summarecon', 'Karawang Station'];

                    foreach ($locations as $location) {
                        $approvedCount = isset($locationData[$location]) ? $locationData[$location] : 0;
                        echo '<div style="text-align: center;">';
                        echo '<img src="asset/Train.png" alt="Train Icon" style="width: 40px; height: 40px; margin-bottom: 5px;">';
                        echo '<p>' . htmlspecialchars($location) . '</p>';
                        echo '<p>' . htmlspecialchars($approvedCount) . '</p>';
                        echo '</div>';
                    }
                    ?>
                        </div>
                    </div>
                </div>

                <div class="right-panel">
                    <h2>Pie Chart</h2>
                    <div class="card">
                        <canvas id="claimsChart" style="max-width: 200px; max-height: 200px;"></canvas>
                    </div>
                </div>


                <div class="list">
                <h3>Claim List</h3>
                <?php
                if ($resultClaims->num_rows > 0) {
                    while ($row = $resultClaims->fetch_assoc()) {
                        echo '<div class="list-item">';
                        echo '<p>' . htmlspecialchars($row['passenger_name'] ?? 'Unknown') . ' - ' . htmlspecialchars($row['nama_barang'] ?? 'Unknown') . '</p>';
                        echo '<div class="button-group">';
                        // Tampilkan tombol Release hanya jika status == "belum rilis"
                        if (strtolower($row['status_rilis']) === 'belum rilis') {
                            echo '<button class="release" onclick="window.location.href=\'releaseform.php?lost_item_id=' . urlencode($row['lost_item_id']) . '\'">Release</button>';
                        }
                        echo '<button class="view-details-btn" onclick="window.location.href=\'approvalform.php?lost_item_id=' . urlencode($row['lost_item_id']) . '\'">View Details</button>';                      
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No claims found.</p>';
                }
                ?>
                </div>

            </div>
        </div> 
    </div>
    
    <script>
    const ctx = document.getElementById('claimsChart').getContext('2d');
    const claimsChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Claimed', 'Unclaimed'],
            datasets: [{
                data: [<?= $approved ?>, <?= $rejected ?>],
                backgroundColor: ['#7F0408', '#A3A3A3'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 12,    // Ukuran lebar kotak
                        boxHeight: 12,   // Ukuran tinggi kotak (Chart.js v4+)
                        borderRadius: 2, // Membuat bentuk lebih seperti kotak
                        usePointStyle: false // Biarkan default bentuk kotak
                    }
                }
                
            }
        }
    });
    </script>

    
</body>
</html>