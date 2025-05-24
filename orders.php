<?php
session_start();
include 'proses/koneksi.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit();
}
$sudahBayar = $_SESSION['sudah_bayar'] ?? false;
$id_user = $_SESSION['id_user'];

$alert = '';
if (isset($_SESSION['pesan_sukses'])) {
  $alert = '<div class="alert alert-success alert-center">'.$_SESSION['pesan_sukses'].'</div>';
  unset($_SESSION['pesan_sukses']);
} elseif (isset($_SESSION['pesan_error'])) {
  $alert = '<div class="alert alert-danger alert-center">'.$_SESSION['pesan_error'].'</div>';
  unset($_SESSION['pesan_error']);
}

$queryUser = mysqli_query($conn, "SELECT nama FROM users WHERE id_user = '$id_user'");
$dataUser = mysqli_fetch_assoc($queryUser);
$namaUser = $dataUser['nama'];

$query = "SELECT k.id_keranjang, m.name, m.image, m.harga, k.jumlah 
          FROM keranjang k 
          JOIN menu m ON k.id_menu = m.id_menu
          WHERE k.id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;

while ($row = $result->fetch_assoc()) {
    $harga = (int)preg_replace('/[^0-9]/', '', $row['harga']);
    $subtotal = $harga * $row['jumlah'];
    $total += $subtotal;
    $cartItems[] = [
        'id_keranjang' => $row['id_keranjang'],
        'name' => $row['name'],
        'image' => $row['image'],
        'harga' => $harga,
        'jumlah' => $row['jumlah'],
        'subtotal' => $subtotal,
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Keranjang | Cafe Kali</title>
  <link rel="stylesheet" href="css/menu.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { font-family: Arial, sans-serif; }
    .cart-container {
      max-width: 800px;
      margin: 30px auto;
      padding: 20px;
      border: 1px solid #eee;
      border-radius: 10px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #ccc;
    }
    img {
      width: 50px;
      height: auto;
    }
    .total {
      text-align: right;
      font-weight: bold;
      margin-bottom: 20px;
    }
    .alert-center {
      margin: 20px auto;
      width: fit-content;
      max-width: 90%;
      padding: 15px 25px;
      border-radius: 8px;
      font-weight: bold;
      text-align: center;
    }
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .alert-danger {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    .btn-checkout {
      background-color: #dc3545;
      color: white;
      padding: 10px 20px;
      border-radius: 5px;
      border: none;
      text-decoration: none;
      font-weight: bold;
      cursor: pointer;
      display: inline-block;
      width: 100%;
      max-width: 300px;
      text-align: center;
    }
    .btn-checkout:hover {
      background-color: #bb2d3b;
    }
  </style>
</head>
<body>
<header class="text-center p-3 bg-light shadow-sm sticky-top">
  <nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><i>Kafe Kali</i></a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ms-auto">
          <a class="nav-link" href="index.php#">Home</a>
          <a class="nav-link" href="index.php#about">About</a>
          <a class="nav-link" href="menu.php">Menu</a>
          <a class="nav-link" href="index.php#reservasi">Reservasi</a>
        </div>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-dark fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
              👤 <?= htmlspecialchars($_SESSION['nama']) ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="riwayat.php">Riwayat</a></li>
              <li><a class="dropdown-item" href="proses/proses-logout.php">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="cart-container">
  <h2>Keranjang Anda</h2>
  <?= $alert ?>

  <?php if (!empty($cartItems)): ?>
    <table>
      <thead>
        <tr>
          <th>Gambar</th>
          <th>Nama Menu</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cartItems as $item): ?>
          <tr>
            <td><img src="uploads/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
            <td><?= $item['jumlah'] ?></td>
            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="total">Total pembayaran: Rp <?= number_format($total, 0, ',', '.') ?></div>

    <!-- FORM PEMBAYARAN -->
    <form action="proses/proses-pembayaran.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id_user" value="<?= $id_user ?>">
      <input type="hidden" name="total" value="<?= $total ?>">

      <div class="mb-2">
        <label for="alamat" class="form-label">Alamat</label>
        <input type="text" name="alamat" id="alamat" class="form-control" required>
      </div>

      <div class="mb-2">
        <label for="metode" class="form-label">Metode Pembayaran</label>
        <select name="metode_pembayaran" id="metode" class="form-select" required>
          <option value="" selected>-- Pilih Metode --</option>
          <option value="cash">Cash</option>
          <option value="e-wallet">Qris</option>
          <option value="transfer_bank">BRI</option>
        </select>
      </div>

      <div class="mb-2">
        <label for="keterangan" class="form-label">Keterangan</label>
        <input type="text" name="keterangan" id="keterangan" class="form-control" placeholder="opsional">
      </div>

       <div class="mb-3" id="qr-image" style="display: none;">
        <label class="form-label">Scan QR Code:</label><br>
        <img src="img/qr.jpg" alt="QRIS" style="max-width: 300px; border: 1px solid #ccc; border-radius: 8px;">
      </div>

      <div class="mb-3" id="upload-bukti" style="display: none;">
        <label for="bukti" class="form-label">Upload Bukti Transfer</label>
        <input type="file" name="bukti_transfer" id="bukti" class="form-control" accept="image/*">
      </div>

     

      <button type="submit" class="btn btn-success">Kirim Pembayaran</button>
    </form>

    <?php if ($sudahBayar): ?>
      <div style="text-align: center; margin-top: 20px;">
        <form method="POST" action="proses/proses-orders.php">
          <input type="hidden" name="total" value="<?= $total ?>">
          <button type="submit" class="btn-checkout">Checkout</button>
        </form>
        <?php unset($_SESSION['sudah_bayar']); ?>
      </div>
    <?php endif; ?>

    <script>
      const metodeSelect = document.getElementById('metode');
      const upload = document.getElementById('upload-bukti');
      const qrImage = document.getElementById('qr-image');

      metodeSelect.addEventListener('change', function () {
        if (this.value === 'transfer_bank') {
          upload.style.display = 'block';
          qrImage.style.display = 'none';
        } else if (this.value === 'e-wallet') {
          upload.style.display = 'block';
          qrImage.style.display = 'block';
        } else {
          upload.style.display = 'none';
          qrImage.style.display = 'none';
        }
      });
    </script>

  <?php else: ?>
    <p>Keranjang Anda kosong.</p>
  <?php endif; ?>
</div>

<footer class="text-center p-4 mt-4 bg-light">
  <p>📍 Street name 1, City | ☎️ +569 2698 0256</p>
  <p>📧 email1@company.com | 📧 email2@company.com</p>
  <div><a href="#">Instagram</a> | <a href="#">Facebook</a> | <a href="#">Twitter</a></div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
