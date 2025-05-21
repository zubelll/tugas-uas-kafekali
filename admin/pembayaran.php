<?php
session_start();
include '../proses/koneksi.php';



// Ambil pembayaran
$query = "SELECT p.id_pembayaran, u.nama, p.metode_pembayaran, p.status_pembayaran, p.tanggal_pembayaran, p.bukti_transfer, p.keterangan
          FROM pembayaran p
          JOIN users u ON p.id_user = u.id_user
          ORDER BY p.tanggal_pembayaran DESC";

$result = mysqli_query($conn, $query);
?>



<div class="container">
    <h2 class="mb-4">Data Pembayaran</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nama User</th>
                <th>Metode</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Bukti</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?= $row['id_pembayaran'] ?></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= $row['metode_pembayaran'] ?></td>
                <td><?= $row['status_pembayaran'] ?></td>
                <td><?= $row['tanggal_pembayaran'] ?></td>
                <td><?= htmlspecialchars($row['keterangan']) ?></td>
                
                        <td>
  <?php if (!empty($row['bukti_transfer'])): ?>
    <img src="../uploads/bukti/<?= htmlspecialchars($row['bukti_transfer']) ?>" alt="Bukti Transfer" style="width: 80px; height: auto; border: 1px solid #ccc; border-radius: 4px;">
  <?php else: ?>
    <span>Tidak ada</span>
  <?php endif; ?>
</td>

                 
                <td>
                    <?php if ($row['status_pembayaran'] !== 'dibayar'): ?>
                        <form action="../proses/proses-update-status.php" method="POST" class="d-inline">
                            <input type="hidden" name="id_pembayaran" value="<?= $row['id_pembayaran'] ?>">
                            <button type="submit" name="update" class="btn btn-success btn-sm">Tandai Dibayar</button>
                        </form>
                    <?php else: ?>
                        <span class="badge bg-success">Sudah Dibayar</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

