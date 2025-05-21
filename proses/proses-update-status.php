<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_pembayaran'])) {
    $id_pembayaran = intval($_POST['id_pembayaran']);

    // Update status pembayaran
    $query = "UPDATE pembayaran SET status_pembayaran = 'dibayar' WHERE id_pembayaran = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_pembayaran);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['success'] = "Status pembayaran berhasil diubah.";
        } else {
            $_SESSION['error'] = "Gagal mengubah status pembayaran.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Query tidak valid.";
    }
} else {
    $_SESSION['error'] = "Permintaan tidak valid.";
}

header("Location: ../admin/index.php?tab=pembayaran");
exit;
