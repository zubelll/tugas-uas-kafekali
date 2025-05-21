<?php
session_start();
include 'proses/koneksi.php';

if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit();
}

$id_user = $_SESSION['id_user'];

// Ambil data keranjang user
$query = "SELECT keranjang.id_keranjang, menu.name, menu.image, menu.harga, keranjang.jumlah 
          FROM keranjang 
          JOIN menu ON keranjang.id_menu = menu.id_menu 
          WHERE keranjang.id_user = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$cartItems = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
  $harga = preg_replace('/[^0-9]/', '', $row['harga']);
  $price = (int)$harga;
  $quantity = (int)$row['jumlah'];
  $subtotal = $price * $quantity;
  $total += $subtotal;

  $cartItems[] = [
    'name' => $row['name'],
    'image' => 'uploads/' . $row['image'],
    'price' => $price,
    'quantity' => $quantity,
    'subtotal' => $subtotal,
  ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Proses simpan order ke database
  $nama_pelanggan = $_POST['nama_pelanggan'];
  $alamat = $_POST['alamat'];
  $telepon = $_POST['telepon'];
  $metode_pembayaran = $_POST['metode_pembayaran'];

  // Insert ke tabel orders
  $stmt = $conn->prepare("INSERT INTO orders (id_user, nama_pelanggan, alamat, telepon, metode_pembayaran, total, tanggal) VALUES (?, ?, ?, ?, ?, ?,?' NOW())");
  $stmt->bind_param("issssi", $id_user, $nama_pelanggan, $alamat, $telepon, $metode_pembayaran, $total);
  $stmt->execute();
  $id_order = $stmt->insert_id;

  // Insert detail pesanan
  $stmtDetail = $conn->prepare("INSERT INTO order_details (id_order, product_name, quantity, price) VALUES (?, ?, ?, ?)");
  foreach ($cartItems as $item) {
    $stmtDetail->bind_param("isii", $id_order, $item['name'], $item['quantity'], $item['price']);
    $stmtDetail->execute();
  }

  // Kosongkan keranjang user setelah order
  $stmt = $conn->prepare("DELETE FROM keranjang WHERE id_user = ?");
  $stmt->bind_param("i", $id_user);
  $stmt->execute();

  // Redirect ke halaman sukses / terima kasih
  header("Location: order_success.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout | Cafe Kali</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 800px;
      margin: 20px auto;
      padding: 0 15px;
      color: #333;
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    th, td {
      padding: 10px;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }
    .product-img {
      width: 50px;
      height: 50px;
      object-fit: cover;
    }
    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
      max-width: 500px;
      margin: 0 auto;
    }
    label {
      font-weight: bold;
    }
    input[type="text"], textarea, select {
      padding: 8px;
      font-size: 1rem;
      width: 100%;
      box-sizing: border-box;
    }
    button {
      background-color: #ff5e5e;
      border: none;
      color: white;
      padding: 12px;
      font-size: 1rem;
      cursor: pointer;
    }
    .total {
      text-align: right;
      font-weight: bold;
      font-size: 1.2rem;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <h2>Checkout - Place Your Order</h2>

  <?php if (empty($cartItems)) : ?>
    <p>Keranjang Anda kosong.</p>
  <?php else : ?>
    <table>
      <thead>
        <tr>
          <th></th>
          <th>Produk</th>
          <th>Harga</th>
          <th>Jumlah</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($cartItems as $item) : ?>
          <tr>
            <td><img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-img"></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td>Rp <?= number_format($item['price'], 0, ',', '.') ?></td>
            <td><?= $item['quantity'] ?></td>
            <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="total">
      Total Pembayaran: Rp <?= number_format($total, 0, ',', '.') ?>
    </div>

    <form method="POST" action="">
      <label for="nama_pelanggan">Nama Lengkap</label>
      <input type="text" id="nama_pelanggan" name="nama_pelanggan" required />

      <label for="alamat">Alamat Pengiriman</label>
      <textarea id="alamat" name="alamat" rows="3" required></textarea>

      <label for="telepon">Nomor Telepon</label>
      <input type="text" id="telepon" name="telepon" required />

      <label for="metode_pembayaran">Metode Pembayaran</label>
      <select id="metode_pembayaran" name="metode_pembayaran" required>
        <option value="">-- Pilih metode pembayaran --</option>
        <option value="Cash">Cash</option>
        <option value="Transfer Bank">Transfer Bank</option>
        <option value="E-Wallet">E-Wallet</option>
      </select>

      <button type="submit">Place Order</button>
    </form>
  <?php endif; ?>

</body>
</html>
