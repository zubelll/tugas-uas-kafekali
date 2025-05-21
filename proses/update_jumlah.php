<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'koneksi.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_keranjang = $_POST['id_keranjang'] ?? null;
    $jumlah = $_POST['jumlah'] ?? null;

    var_dump($id_keranjang, $jumlah, $_SESSION['id_user']);

    if (!$id_keranjang || !$jumlah || !is_numeric($jumlah) || $jumlah < 1) {
        http_response_code(400);
        echo json_encode(['error' => 'Data tidak valid']);
        exit;
    }

    $id_user = $_SESSION['id_user'];

    $stmt = $conn->prepare("UPDATE keranjang SET jumlah = ? WHERE id_keranjang = ? AND id_user = ?");
    if (!$stmt) {
        echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
        exit;
    }
    $stmt->bind_param('iii', $jumlah, $id_keranjang, $id_user);
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Gagal update ke database']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Metode tidak diizinkan']);
}
