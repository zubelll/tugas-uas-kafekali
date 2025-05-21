<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_menu'])) {
    $id_user = $_SESSION['id_user'];
    $id_menu = intval($_POST['id_menu']); // hindari SQL injection

    // Cek apa menu udah di keranjang
    $stmt = $conn->prepare("SELECT jumlah FROM keranjang WHERE id_user = ? AND id_menu = ?");
    $stmt->bind_param("ii", $id_user, $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        //  update jumlah
        $stmt = $conn->prepare("UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_user = ? AND id_menu = ?");
        $stmt->bind_param("ii", $id_user, $id_menu);
        $stmt->execute();
    } else {
        // Belum ada, insert baru
        $stmt = $conn->prepare("INSERT INTO keranjang (id_user, id_menu, jumlah) VALUES (?, ?, 1)");
        $stmt->bind_param("ii", $id_user, $id_menu);
        $stmt->execute();
    }

 
    header("Location: ../keranjang.php");
    exit();
} else {
  
    header("Location: ../menu.php");
    exit();
}
