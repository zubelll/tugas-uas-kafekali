<?php
$host     = "localhost";
$username = "root";
$password = "";
$database = "kafekali";

$conn = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
