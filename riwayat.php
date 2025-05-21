<?php
session_start();
include 'proses/koneksi.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data pesanan user
$query = "SELECT * FROM pesanan WHERE id_user = ? ORDER BY tanggal_pesan DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Pembelian | Cafe Kali</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .card {
      margin-bottom: 20px;
    }
    .card-title {
      color: #dc3545;
    }
    .badge-status {
      font-size: 0.9em;
    }
    .no-history {
      text-align: center;
      margin-top: 100px;
      color: #666;
    }
    .content-wrapper {
  padding-top: 100px;
}

  </style>
</head>
<body>
    <!-- nav -->
     <nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top" style="background-color: #f3f3f3;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i>Kafe Kali</i></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link active" href="index.php#">Home</a>
        <a class="nav-link" href="index.php#about">About Us</a>
        <a class="nav-link" href="index.php#menu">Menu</a>
        <a class="nav-link" href="index.php#reservasi">Reservasi</a>
      </div>

      <!-- Tampilkan Nama User -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-dark fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
            👤 <?= htmlspecialchars($_SESSION['nama']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="proses/proses-logout.php">Logout</a></li>
            <li><a class="dropdown-item" href="riwayat.php">riwayat</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
     <!-- akhir nav -->
<div class="container content-wrapper py-5">
  <h2 class="text-center mt-5 pt-4 mb-4">Riwayat Pembelian Anda</h2>


  <?php if ($result->num_rows > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="card shadow-sm">
        <div class="card-body">
          <!-- ID pesanan disimpan sebagai hidden input (opsional untuk keperluan JS backend) -->
          <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">

          <p class="mb-1">Tanggal: <?= date('d M Y, H:i', strtotime($row['tanggal_pesan'])) ?></p>
          <p class="mb-1">Total: <strong>Rp <?= number_format($row['total'], 0, ',', '.') ?></strong></p>
          <p class="mb-2">Status: 
            <?php if ($row['status'] == 'dibayar'): ?>
              <span class="badge bg-success badge-status">Dibayar</span>
            <?php elseif ($row['status'] == 'sukses'): ?>
              <span class="badge bg-primary badge-status">Sukses</span>
            <?php else: ?>
              <span class="badge bg-secondary badge-status"><?= htmlspecialchars($row['status']) ?></span>
            <?php endif; ?>
          </p>

          <!-- Detail pesanan -->
          <div class="mt-3">
            <h6>Rincian Pesanan:</h6>
            <ul class="list-group list-group-flush">
              <?php
              $detail = $conn->prepare("SELECT m.name, dp.jumlah, dp.subtotal 
                                        FROM detail_pesanan dp 
                                        JOIN menu m ON dp.id_menu = m.id_menu 
                                        WHERE dp.id_pesanan = ?");
              $detail->bind_param("i", $row['id_pesanan']);
              $detail->execute();
              $detailResult = $detail->get_result();
              while ($item = $detailResult->fetch_assoc()):
              ?>
                <li class="list-group-item d-flex justify-content-between">
                  <?= htmlspecialchars($item['name']) ?> (x<?= $item['jumlah'] ?>)
                  <span>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                </li>
              <?php endwhile; ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <div class="no-history">
      <h4>Belum ada riwayat pembelian 😕</h4>
      <p>Ayo pesan makanan favoritmu sekarang!</p>
      <a href="menu.php" class="btn btn-danger mt-3">Lihat Menu</a>
    </div>
  <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
