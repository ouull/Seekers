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


    // Query untuk menghitung jumlah laporan kehilangan di lost_items
    $queryLostReport = "SELECT COUNT(*) AS total FROM lost_report";
    $resultLostReport = $conn->query($queryLostReport);
    $rowLostReport = $resultLostReport->fetch_assoc();
    $totalLostReport = $rowLostReport['total'];


    $queryLostReportData = "SELECT id, nama_pelapor, kontak, nama_barang, ciri_ciri, lokasi_kehilangan, kronologi, created_at, status FROM lost_report ORDER BY created_at DESC";
    $resultLostReportData = $conn->query($queryLostReportData);

    $conn->query("
    UPDATE lost_report
    SET status = 'Expired'
    WHERE status = 'Belum ditemukan'
    AND created_at <= NOW() - INTERVAL 7 DAY
    "); 

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost Report - Lost and Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/lostreport.css" />
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
            <h1>Lost Report</h1>
                <div class="search">
                    <img alt="User Profile" height="35" width="35"
                    src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
                </div>
            </div>
            <section class="overview-container">
                <div class="overview-box">
                    <h2>Total Loss Report</h2>
                    <p class="value"><?= $totalLostReport ?></p>
                    <p class="date">All Station | Date: <?= date('d/m') ?></p>
                </div>
            </section>

            
    <div class="daftarlaporan">
    <h1>Daftar Laporan Kehilangan</h1>         
    <button onclick="location.href='formlostreport.php'" class="report-btn">REPORT LOST ITEM</button>
    <div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelapor</th>
                <th>Kontak</th>
                <th>Nama Barang</th>
                <th>Ciri-ciri</th>
                <th>Lokasi Kehilangan</th>
                <th>Kronologi</th>
                <th>Waktu Pelaporan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            while ($row = $resultLostReportData->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_pelapor']) . "</td>";
                echo "<td>" . htmlspecialchars($row['kontak']) . "</td>";
                echo "<td>" . htmlspecialchars($row['nama_barang']) . "</td>";
                echo "<td>" . htmlspecialchars($row['ciri_ciri']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lokasi_kehilangan']) . "</td>";
                echo "<td>" . htmlspecialchars($row['kronologi']) . "</td>";
                echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                echo "<td> <form method='post' action='updatelostreport.php'>
                                <input type='hidden' name='report_id' value='" . $row['id'] . "'>
                                <select name='status' class='status-select' onchange='this.form.submit()'>
                                    <option value='Belum ditemukan'" . ($row['status'] == 'Belum ditemukan' ? ' selected' : '') . ">BELUM DITEMUKAN</option>
                                    <option value='Sudah ditemukan'" . ($row['status'] == 'Sudah ditemukan' ? ' selected' : '') . ">SUDAH DITEMUKAN</option>
                                    <option value='Expired'" . ($row['status'] == 'Expired' ? ' selected' : '') . ">EXPIRED</option>
                                </select>
                            </form>
                        </td>";

                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</div>
<script>
      function updateSelectColor(selectElement) {
        // Hapus class warna sebelumnya
        selectElement.classList.remove('status-red', 'status-gray', 'status-green');

        // Tambahkan class sesuai nilai
        if (selectElement.value === 'Expired') {
            selectElement.classList.add('status-red');
        } else if (selectElement.value === 'Belum ditemukan') {
            selectElement.classList.add('status-gray');
        } else if (selectElement.value === 'Sudah ditemukan') {
            selectElement.classList.add('status-green');
        }
    }

    // Jalankan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function () {
        const selectElements = document.querySelectorAll('.status-select');
        selectElements.forEach(function (select) {
            updateSelectColor(select); // Set warna awal
            select.addEventListener('change', function () {
                updateSelectColor(this); // Set warna saat berubah
            });
        });
    });
</script>
    </body>
</html>