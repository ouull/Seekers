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

// Query untuk menghitung jumlah barang dalam tabel lost_items
$queryCatalog = "SELECT COUNT(*) AS total FROM lost_items";
$resultCatalog = $conn->query($queryCatalog);
$rowCatalog = $resultCatalog->fetch_assoc();
$totalCatalog = $rowCatalog['total'];

// Query untuk menghitung jumlah laporan kehilangan di lost_items
$queryLostReport = "SELECT COUNT(*) AS total FROM lost_report";
$resultLostReport = $conn->query($queryLostReport);
$rowLostReport = $resultLostReport->fetch_assoc();
$totalLostReport = $rowLostReport['total'];

// Query untuk menghitung jumlah klaim yang disetujui di claims
$queryApprovedClaims = "SELECT COUNT(*) AS total FROM lost_items WHERE status = 'Claimed'";
$resultApprovedClaims = $conn->query($queryApprovedClaims);
$rowApprovedClaims = $resultApprovedClaims->fetch_assoc();
$totalApprovedClaims = $rowApprovedClaims['total'];

// Query untuk menghitung jumlah klaim yang ditolak di claims
$queryRejectedClaims = "SELECT COUNT(*) AS total FROM lost_items WHERE status = 'Unclaimed'";
$resultRejectedClaims = $conn->query($queryRejectedClaims);
$rowRejectedClaims = $resultRejectedClaims->fetch_assoc();
$totalRejectedClaims = $rowRejectedClaims['total'];

    // Fungsi untuk mengambil data jumlah barang dalam seminggu terakhir berdasarkan hari
    function getWeeklyData($conn, $table, $dateColumn) {
        $weeklyData = array_fill(0, 7, 0); // 0 = Minggu, 6 = Sabtu
    
        $query = "SELECT DATE_FORMAT($dateColumn, '%w') AS day, COUNT(*) AS total 
                  FROM $table 
                  WHERE $dateColumn >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                  AND $dateColumn < DATE_ADD(DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY), INTERVAL 7 DAY)
                  GROUP BY day";
    
        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            $index = (int) $row['day']; // 0 = Minggu, 6 = Sabtu
            $weeklyData[$index] = $row['total'];
        }
    
        return $weeklyData;
    }
    

    // Ambil data jumlah Found Item (lost_items)
    $foundItems = getWeeklyData($conn, "lost_items", "created_at");

    // Ambil data jumlah Lost Report (lost_report)
    $lostReports = getWeeklyData($conn, "lost_report", "created_at");

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
    <title>Chart - Lost and Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="style/chartt.css"
</head>
<body>
<div class="container">
    <div class="sidebar">
        <img alt="Logo" height="57" src="asset/Seekers.png" width="168"/>
        <ul>
            <li>
                <a href="dashboard.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1">
                    <img src="asset/dashcat.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                    Dashboard
                </a>            
            </li>        
            <li>
            <li>
                <a href="catalog.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1 ;">
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
                <a href="lostreport.php" style="display: flex; align-items: center; gap: 10px; color:#B1B1B1">
                    <img src="asset/report2.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
                    Lost Report
                </a>
            </li>          
            <li>
                <a class="active" href="chart.php" style="display: flex; align-items: center; gap: 10px; ">
                    <img src="asset/chart.png" alt="dashboard" style="width: 20px; height: 20px; border-radius: 10%; margin-bottom: 5px;"> <!-- Gambar -->
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
            <h1>Chart</h1>
            <div class="search">
            <img alt="User Profile" height="35" width="35"
                src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                onclick="window.location.href='profil.php'"/>
            </div>
        </div>

        <section class="chart-section">
            <div class="chart-container">
            <h2>Weekly Activity</h2>
            <canvas id="weeklyActivityChart"></canvas>
            </div>

            <div class="chart-container">
            <h2>Statistics All Stations</h2>
            <canvas id="statisticsChart"></canvas>
            </div>

            <div class="chart-container">
                <h2>Pie Chart</h2>
                <canvas id="claimsChart"></canvas>
            </div>
            
        </section>
        
    </div>
</div>


<script>
    // Data aktivitas mingguan dari PHP
    const foundItemsData = <?php echo json_encode($foundItems); ?>;
    const lostReportsData = <?php echo json_encode($lostReports); ?>;

    const ctx1 = document.getElementById('weeklyActivityChart').getContext('2d');
    const weeklyActivityChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [
                {
                    label: 'Found Item',
                    data: foundItemsData,
                    backgroundColor: '#b71c1c',
                },
                {
                    label: 'Lost Report',
                    data: lostReportsData,
                    backgroundColor: '#888',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Data statistik dari PHP
    const catalogCount = <?php echo $totalCatalog; ?>;
    const lostReportCount = <?php echo $totalLostReport; ?>;
    const approvedClaimsCount = <?php echo $totalApprovedClaims; ?>;
    const rejectedClaimsCount = <?php echo $totalRejectedClaims; ?>;

    const ctx2 = document.getElementById('statisticsChart').getContext('2d');
    const statisticsChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Catalog', 'Loss Report', 'Claimed', 'Unclaimed'],
            datasets: [{
                data: [catalogCount, lostReportCount, approvedClaimsCount, rejectedClaimsCount],
                backgroundColor: ['#ff9800', '#444444', '#7F0408', '#A3A3A3'],
            }]
        },
        options: {
            responsive: true
        }
    });

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
