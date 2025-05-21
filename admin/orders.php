<?php
include '../proses/koneksi.php';

// Cek  tombol "Sukses" ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sukses_id'])) {
    $id_pesanan = $_POST['sukses_id'];
    $update = $conn->prepare("UPDATE pesanan SET status = 'sukses' WHERE id_pesanan = ?");
    $update->bind_param("i", $id_pesanan);
    $update->execute();
}

// Ambil semua pesanan
$query = "SELECT p.id_pesanan, u.nama, p.tanggal_pesan, p.total, p.status 
          FROM pesanan p 
          JOIN users u ON p.id_user = u.id_user
          ORDER BY p.tanggal_pesan DESC";
$result = $conn->query($query);
?>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
  <h2 class="mb-3">Daftar Pesanan</h2>

  <table class="table table-bordered table-striped">
    <thead class="table-dark">
      <tr>
        <th>ID Pesanan</th>
        <th>User</th>
        <th>Tanggal Pesan</th>
        <th>Total</th>
        <th>Status</th>
        <th>Detail Pesanan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['id_pesanan']) ?></td>
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td><?= htmlspecialchars($row['tanggal_pesan']) ?></td>
          <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
          <td>
            <?php
              $status = $row['status'];
              if ($status == 'sukses') {
                echo "<span class='badge bg-success'>Selesai</span>";
              } else {
                echo "<span class='badge bg-warning text-dark'>" . htmlspecialchars($status) . "</span>";
              }
            ?>
          </td>
          <td>
            <?php
            $id_pesanan = $row['id_pesanan'];
            $detailQuery = "SELECT dp.jumlah, dp.subtotal, m.name 
                            FROM detail_pesanan dp 
                            JOIN menu m ON dp.id_menu = m.id_menu 
                            WHERE dp.id_pesanan = ?";
            $stmt = $conn->prepare($detailQuery);
            $stmt->bind_param("i", $id_pesanan);
            $stmt->execute();
            $detailResult = $stmt->get_result();

            echo "<table class='table table-sm'>";
            echo "<tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr>";
            while ($detail = $detailResult->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($detail['name']) . "</td>";
                echo "<td>" . $detail['jumlah'] . "</td>";
                echo "<td>Rp " . number_format($detail['subtotal'], 0, ',', '.') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            ?>
          </td>
          <td>
            <?php if ($status !== 'sukses'): ?>
              <form method="post" onsubmit="return confirm('Yakin ingin ubah status menjadi sukses?')">
                <input type="hidden" name="sukses_id" value="<?= $row['id_pesanan'] ?>">
                <button type="submit" class="btn btn-success btn-sm">Sukses</button>
              </form>
            <?php else: ?>
              <span class="text-success fw-bold">✓ Selesai</span>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
