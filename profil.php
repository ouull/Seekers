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
    <title>Profile - Lost and Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/profile1.css" />
    </head>
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
        <h1>Profile</h1>
            <div class="search">
            <img alt="User Profile" height="35" width="35"
                src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>"
                onclick="window.location.href='profil.php'"/>
            </div>
    </div>
    <div class="profile-card">
        <div class="tabs" style="margin-top: -30px;">
            <div class="active">Edit Profile</div>

            <div onclick="window.location.href='security.php'">Security</div>
        </div>

        <form id="uploadForm" enctype="multipart/form-data">
            <div class="profile-pic">
                <img id="profileImage" src="<?= htmlspecialchars($user['profile_pic'] ?: 'profil/noprofil.png') ?>?t=<?= time() ?>" alt="Foto Profil">
                <label for="fileInput" class="upload-icon">
                    <img src="profil/camera.png" alt="Upload">
                </label>
                <input type="file" id="fileInput" name="profile_pic" accept="image/*">
            </div>
        </form>

        <h2></h2>

        <form action="update_profile.php" method="POST">
    <div class="form-group">
        <label for="fullname">Your Name</label>
        <input id="fullname" name="fullname" type="text" value="<?= htmlspecialchars($user['fullname']) ?>"/>

        <label for="username">Username</label>
        <input id="username" name="username" type="text" value="<?= htmlspecialchars($user['username']) ?>"/>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled/>

        <label for="password">Password</label>
        <input disabled id="password" type="password" value="**********"/>
    </div>

    <div class="form-group">
        <label for="position">Position</label>
        <input id="position" name="position" type="text" value="<?= htmlspecialchars($user['position']) ?>"/>

        <label for="departement">Department</label>
        <input id="departement" name="departement" type="text" value="<?= htmlspecialchars($user['departement']) ?>"/>
    </div>

    <div class="form-group">
        <label for="biro">Biro</label>
        <input id="biro" name="biro" type="text" value="<?= htmlspecialchars($user['biro']) ?>"/>

        <label for="placement">Placement</label>
        <input id="placement" name="placement" type="text" value="<?= htmlspecialchars($user['placement']) ?>"/>
    </div>

    <button type="submit" class="save-btn">Save Changes</button>
</form>
    </div>
</div>
</div>

<script>
document.getElementById("fileInput").addEventListener("change", function(event) {
    var file = event.target.files[0]; 
    if (!file) return;

    var formData = new FormData();
    formData.append("profile_pic", file);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "photoprofile.php", true);

    xhr.onload = function() {
    if (xhr.status === 200) {
        // Reload halaman setelah upload berhasil
        location.reload();
        } else {
            alert("Gagal mengupload gambar.");
        }
    };


    xhr.send(formData);
});

</script>
</body>
</html>