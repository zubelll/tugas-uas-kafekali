<?php
include 'proses/koneksi.php';
session_start();

if (!isset($_SESSION['id_user'])) {
  die("Anda belum login. <a href='login.php'>Login dulu</a>");
}

$id_user = $_SESSION['id_user'];

// Ambil pesanan terakhir user
$queryPesanan = "SELECT id_pesanan, tanggal_pesan, total FROM pesanan WHERE id_user = ? ORDER BY id_pesanan DESC LIMIT 1";
$stmtPesanan = $conn->prepare($queryPesanan);
$stmtPesanan->bind_param("i", $id_user);
$stmtPesanan->execute();
$resultPesanan = $stmtPesanan->get_result();

if ($resultPesanan->num_rows === 0) {
  die("Belum ada pesanan.");
}

$pesanan = $resultPesanan->fetch_assoc();
$id_pesanan = $pesanan['id_pesanan'];
$tanggal = $pesanan['tanggal_pesan'];
$total = $pesanan['total'];

// Ambil detail pesanan
$queryDetail = "
  SELECT menu.name, menu.harga, detail_pesanan.jumlah, detail_pesanan.subtotal
  FROM detail_pesanan
  JOIN menu ON detail_pesanan.id_menu = menu.id_menu
  WHERE detail_pesanan.id_pesanan = ?
";
$stmtDetail = $conn->prepare($queryDetail);
$stmtDetail->bind_param("i", $id_pesanan);
$stmtDetail->execute();
$resultDetail = $stmtDetail->get_result();

$items = [];
while ($row = $resultDetail->fetch_assoc()) {
  $harga = preg_replace('/[^0-9]/', '', $row['harga']);
  $items[] = [
    'name' => $row['name'],
    'harga' => $harga,
    'jumlah' => $row['jumlah'],
    'subtotal' => $row['subtotal']
  ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Nota Pembelian</title>
  <style>
    body { font-family: Arial; max-width: 600px; margin: auto; padding: 20px; }
    h2 { text-align: center; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #aaa; padding: 8px; text-align: center; }
    .total { text-align: right; margin-top: 20px; font-size: 18px; }
    .print-btn { margin-top: 20px; display: block; text-align: center; }
  </style>
</head>
<body>

  <h2>Nota Pembelian<br>Cafe Kali</h2>
  <p>Tanggal Pesanan: <?= date('d-m-Y H:i', strtotime($tanggal)) ?></p>

  <table>
    <thead>
      <tr>
        <th>Menu</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['name']) ?></td>
          <td>Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
          <td><?= $item['jumlah'] ?></td>
          <td>Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="total"><strong>Total:</strong> Rp <?= number_format($total, 0, ',', '.') ?></div>

  <div class="print-btn">
    <button onclick="window.print()">🖨️ Cetak Nota</button>
  </div>

</body>
</html>
