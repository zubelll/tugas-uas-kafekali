<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id_keranjang'])) {
    $id_user = $_SESSION['id_user'];
    $id_keranjang = $_GET['id_keranjang'];

    // Pastikan data keranjang milik user yang login
    $query = "DELETE FROM keranjang WHERE id_keranjang = ? AND id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_keranjang, $id_user);
    $stmt->execute();
}

header("Location: ../keranjang.php");
exit();
?>
