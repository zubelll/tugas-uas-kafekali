<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "User belum login";
    exit();
}

include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_user = $_SESSION['id_user'];
    $metode = $_POST['metode_pembayaran'];
    $keterangan = $_POST['keterangan'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $status = ($metode === 'cash') ? 'dibayar' : 'pending';
    $buktiTransfer = ''; 

    // Upload bukti jika metode e-wallet atau transfer_bank
    if ($metode === 'transfer_bank' || $metode === 'e-wallet') {
        if (isset($_FILES['bukti_transfer']) && $_FILES['bukti_transfer']['error'] === UPLOAD_ERR_OK) {
            $targetDir = "../uploads/bukti/";
            $fileName = basename($_FILES["bukti_transfer"]["name"]);
            $targetFilePath = $targetDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
            if (in_array(strtolower($fileType), $allowTypes)) {
                if (move_uploaded_file($_FILES["bukti_transfer"]["tmp_name"], $targetFilePath)) {
                    $buktiTransfer = $fileName;
                    $status = 'dibayar'; // Ubah status jadi dibayar setelah berhasil upload
                } else {
                    $_SESSION['pesan_error'] = "Gagal upload bukti transfer.";
                    header("Location: ../keranjang.php");
                    exit();
                }
            } else {
                $_SESSION['pesan_error'] = "Format file bukti tidak valid.";
                header("Location: ../keranjang.php");
                exit();
            }
        } else {
            $_SESSION['pesan_error'] = "Bukti transfer wajib diunggah untuk metode ini.";
            header("Location: ../keranjang.php");
            exit();
        }
    }

    // Ambil id pesanan terakhir user
    $queryLastOrder = mysqli_query($conn, "SELECT id_pesanan FROM pesanan WHERE id_user = '$id_user' ORDER BY id_pesanan DESC LIMIT 1");
    $row = mysqli_fetch_assoc($queryLastOrder);
    $id_pesanan = $row['id_pesanan'] ?? null;

    if (!$id_pesanan) {
        $_SESSION['pesan_error'] = "Tidak ada pesanan ditemukan untuk user ini.";
        header("Location: ../keranjang.php");
        exit();
    }

    // Simpan data pembayaran ke database
    $stmt = $conn->prepare("INSERT INTO pembayaran (id_user, id_pesanan, metode_pembayaran, status_pembayaran, tanggal_pembayaran, alamat, keterangan, bukti_transfer) VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)");
    $stmt->bind_param("iisssss", $id_user, $id_pesanan, $metode, $status, $alamat, $keterangan, $buktiTransfer);

    if ($stmt->execute()) {
        $_SESSION['sudah_bayar'] = true;
        $_SESSION['pesan_sukses'] = "Pembayaran berhasil dikirim!";
        header("Location: ../orders.php");
        exit();
    } else {
        $_SESSION['pesan_error'] = "Gagal menyimpan pembayaran: " . $stmt->error;
        header("Location: ../keranjang.php");
        exit();
    }
} else {
    $_SESSION['pesan_error'] = "Permintaan tidak valid.";
    header("Location: ../keranjang.php");
    exit();
}
?>
