<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['id_user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $total = isset($_POST['total']) ? (int)$_POST['total'] : 0;

   
    $conn->begin_transaction();

    try {
        
        $stmt = $conn->prepare("INSERT INTO pesanan (id_user, tanggal_pesan, total, status) VALUES (?, NOW(), ?, 'pending')");
        $stmt->bind_param("ii", $id_user, $total);
        $stmt->execute();

      
        $id_pesanan = $stmt->insert_id;

        //  data keranjang milik user saat ini
        $query = "SELECT k.id_menu, m.harga, k.jumlah 
                  FROM keranjang k 
                  JOIN menu m ON k.id_menu = m.id_menu 
                  WHERE k.id_user = ?";
        $stmt2 = $conn->prepare($query);
        $stmt2->bind_param("i", $id_user);
        $stmt2->execute();
        $result = $stmt2->get_result();

      
        $stmt3 = $conn->prepare("INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal) VALUES (?, ?, ?, ?)");

       
        while ($row = $result->fetch_assoc()) {
          
            $harga = (int)preg_replace('/[^0-9]/', '', $row['harga']);
            $jumlah = (int)$row['jumlah'];
            $subtotal = $harga * $jumlah;

            $stmt3->bind_param("iiii", $id_pesanan, $row['id_menu'], $jumlah, $subtotal);
            $stmt3->execute();
        }

        //  Bersihkan keranjang setelah pesanan 
        $stmt4 = $conn->prepare("DELETE FROM keranjang WHERE id_user = ?");
        $stmt4->bind_param("i", $id_user);
        $stmt4->execute();

        // 
        $conn->commit();

       
        header("Location: order_success.php?id_pesanan=" . $id_pesanan);
        exit();

    } catch (Exception $e) {
       
        $conn->rollback();
        echo "Terjadi kesalahan: " . $e->getMessage();
    }
} else {
   
    header("Location: ../orders.php");
    exit();
}
