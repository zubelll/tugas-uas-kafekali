<?php
require_once '../proses/koneksi.php'; 

$query = "SELECT 
            k.id_keranjang, 
            u.nama AS nama, 
            m.name, 
            k.jumlah 
          FROM keranjang k
          JOIN users u ON k.id_user = u.id_user
          JOIN menu m ON k.id_menu = m.id_menu";

$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>

<h2>Daftar Pesanan</h2>
<table border="1" cellpadding="8" cellspacing="0">
  <tr>
    <th>ID pesanan</th>
    <th>Nama User</th>
    <th>Nama Menu</th>
    <th>Jumlah</th>
  </tr>
  <?php while($row = mysqli_fetch_assoc($result)) { ?>
  <tr>
    <td><?= $row['id_keranjang'] ?></td>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['jumlah'] ?></td>
  </tr>
  <?php } ?>
</table>