<?php
require 'koneksi.php'; 

// Ambil data dari form
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$konfirmasi = $_POST['konfirmasi'];

// Validasi konfirmasi password
if ($password !== $konfirmasi) {
    die("Password dan konfirmasi tidak cocok.");
}


$password = ($password);


$sql = "INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')";
if ($conn->query($sql) === TRUE) {
   header("Location: ../daftar.php?status=success");

   
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
