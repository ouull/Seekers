<?php
$host = "localhost"; 
$user = "root"; 
$pass = ""; 
$dbname = "lost_and_found"; 

$conn = new mysqli($host, $user, $pass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Periksa apakah form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = isset($_POST["fullname"]) ? $_POST["fullname"] : "";
    $email = isset($_POST["email"]) ? $_POST["email"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $confirm_password = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";

    // Cek apakah password dan confirm password sama
    if ($password !== $confirm_password) {
        die("Password dan Confirm Password tidak sama!");
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query insert
    $query_sql = "INSERT INTO users (fullname, email, password) 
                  VALUES ('$fullname', '$email', '$hashed_password')";

    if (mysqli_query($conn, $query_sql)) {
        header("Location: login.html?message=success");
        exit();
    } else {
        echo "Pendaftaran gagal: " . mysqli_error($conn);
    }
}
?>
