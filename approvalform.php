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
    <link rel="stylesheet" href="style/approvalform.css"
    
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
            <h1>Claim Form</h1>
                <div class="search">
                    <img alt="User Profile" height="35" width="35"
                    src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                    onclick="window.location.href='profil.php'"/>
                </div>
            </div>
            <div class="form-container">
                <div class="content">
                    <!-- <h2>Claim Form</h2> -->
                    <div class="claim-form">
                        <div class="form-section">
                        <?php
                            $lost_item_id = isset($_GET['lost_item_id']) ? intval($_GET['lost_item_id']) : 0;

                            if ($lost_item_id === 0) {
                                die("Data tidak valid.");
                            }

                            $conn = new mysqli("localhost", "root", "", "lost_and_found");

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $query = "SELECT passenger_name, phone_number, id_card_image, train_ticket_image, item_description, proof_of_ownership, stasiun_ambil, stasiun_kirim FROM claims WHERE lost_item_id = ?";
                            $stmt = $conn->prepare($query);
                            $stmt->bind_param("i", $lost_item_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            // Tambahkan setelah query ke claims
                            $queryLost = "SELECT perilis, tanggal_rilis, foto_rilis FROM lost_items WHERE id = ?";
                            $stmtLost = $conn->prepare($queryLost);
                            $stmtLost->bind_param("i", $lost_item_id);
                            $stmtLost->execute();
                            $resultLost = $stmtLost->get_result();
                            $rowLost = $resultLost->fetch_assoc();

                            $lokasi_rilis = !empty($row['stasiun_ambil']) ? $row['stasiun_ambil'] :
                                            (!empty($row['stasiun_kirim']) ? $row['stasiun_kirim'] : '');

                            // Inisialisasi nilai default jika tidak ditemukan
                            $perilis = $rowLost['perilis'] ?? '';
                            $tanggal_rilis = $rowLost['tanggal_rilis'] ?? '';
                            $foto_rilis = $rowLost['foto_rilis'] ?? '';





                            if ($row = $result->fetch_assoc()) {

                            $lokasi_rilis = !empty($row['stasiun_ambil']) ? $row['stasiun_ambil'] :
                                            (!empty($row['stasiun_kirim']) ? $row['stasiun_kirim'] : '');
                            ?>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" id="name" readonly value="<?= htmlspecialchars($row['passenger_name']); ?>"/>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" id="phone" readonly value="<?= htmlspecialchars($row['phone_number']); ?>"/>
                                </div>

                                <div class="form-group">
                                    <label for="id-card">ID CARD</label>
                                    <div class="file-info">
                                        <img src="<?= htmlspecialchars($row['id_card_image']); ?>" alt="ID Card image" width="50" height="50" style="cursor: pointer;" onclick="showImage('<?= htmlspecialchars($row['id_card_image']); ?>')"/>
                                        <span><?= basename($row['id_card_image']); ?></span>
                                    
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="train-ticket">Train Ticket Proof</label>
                                    <div class="file-info">
                                        <img src="<?= htmlspecialchars($row['train_ticket_image']); ?>" alt="Train Ticket image" width="50" height="50" style="cursor: pointer;" onclick="showImage('<?= htmlspecialchars($row['train_ticket_image']); ?>')"/>
                                        <span><?= basename($row['train_ticket_image']); ?></span>
                                    
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="item-description">Item Description</label>
                                    <textarea id="item-description" rows="4" readonly><?= htmlspecialchars($row['item_description']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="proof-ownership">Proof Of Ownership</label>
                                    <div class="file-info">
                                        <img src="<?= htmlspecialchars($row['proof_of_ownership']); ?>" alt="Proof of Ownership image" width="50" height="50" style="cursor: pointer;" onclick="showImage('<?= htmlspecialchars($row['proof_of_ownership']); ?>')"/>
                                        <span><?= basename($row['proof_of_ownership']); ?></span>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label for="perilis">Perilis</label>
                                    <input type="text" id="perilis" readonly value="<?= htmlspecialchars($perilis); ?>"/>
                                </div>

                                <div class="form-group">
                                    <label for="tanggal_rilis">Tanggal Rilis</label>
                                    <input type="text" id="tanggal_rilis" readonly value="<?= htmlspecialchars($tanggal_rilis); ?>"/>
                                </div>

                                <div class="form-group">
                                    <label for="lokasi_rilis">Lokasi Rilis</label>
                                    <input type="text" id="lokasi_rilis" readonly value="<?= htmlspecialchars($lokasi_rilis); ?>" />
                                </div>

                                <div class="form-group">
                                <label for="foto_rilis">Foto Rilis</label>
                                <div class="file-info">
                                    <img src="<?= $foto_rilis; ?>" alt="Foto Rilis" width="50" height="50" style="cursor: pointer;" onclick="showImage('<?= $foto_rilis; ?>')"/>
                                    <span><?= basename($foto_rilis); ?></span>
                                    </div>
                                </div>

                                    <a href="claimapproval.php">
                                    <button type="button" class="btn" >KEMBALI</button>
                                    </a>
                               

                            <?php
                            } else {
                                echo "<p>Data klaim tidak ditemukan.</p>";
                            }

                            $stmt->close();
                            $conn->close();
                        ?>
 
                        </div>
                <?php
                    $host = "localhost";
                    $user = "root";
                    $pass = "";
                    $db = "lost_and_found";

                    $conn = new mysqli($host, $user, $pass, $db);

                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Ambil data dari database
                    $lost_item_id = isset($_GET['lost_item_id']) ? intval($_GET['lost_item_id']) : 0;

                    if ($lost_item_id === 0) {
                        die("Data tidak valid.");
                    }
                    $query = "SELECT id, nama_barang, no_regist, image, status FROM lost_items WHERE id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $lost_item_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($row = $result->fetch_assoc()) {
                        $imageSrc = htmlspecialchars($row['image']);
                        $namaBarang = htmlspecialchars($row['nama_barang']);
                        $noRegist = htmlspecialchars($row['no_regist']);
                        $status = htmlspecialchars($row['status']);

                ?>

            <div class="item-section">
                <img src="<?= $imageSrc; ?>" alt="<?= $namaBarang; ?> image"  />
                <div class="item-details">
                    <h3><?= $namaBarang; ?></h3>
                    <p><?= $noRegist; ?></p>
                </div>


        <div id="imgModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage">
        </div>
            </div>

        


                <?php
                } else {
                    echo "<p>Item tidak ditemukan.</p>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>
                    


        </div>
        
    </div>
    </div>
    <!-- Modal Structure -->

<script>
function showImage(src) {
  var modal = document.getElementById("imgModal");
  var modalImg = document.getElementById("modalImage");
  modal.style.display = "flex";
  modalImg.src = src;
}

function closeModal() {
    document.getElementById("imgModal").style.display = "none";
}


document.getElementById("imgModal").addEventListener("click", function () {
  this.style.display = "none";
});

</script>

</body>
</html>