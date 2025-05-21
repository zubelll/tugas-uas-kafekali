<?php
include '../proses/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Validasi form
  if (!empty($_POST['name']) && !empty($_POST['harga']) && !empty($_POST['kategori']) && isset($_FILES['image'])) {
    $name = $_POST['name'];
    $harga = $_POST['harga'];
    $kategori = $_POST['kategori'];

    $image_name = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_dir = '../uploads/';

    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0777, true);
    }

    $path = $upload_dir . basename($image_name);

    // Upload gambar berhasil → baru simpan ke DB
    if (move_uploaded_file($tmp_name, $path)) {
      $stmt = $conn->prepare("INSERT INTO menu (name, harga, image, kategori) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $name, $harga, $image_name, $kategori);
      $stmt->execute();
      $stmt->close();
    }
  }

  // Redirect (PRG pattern) supaya refresh tidak kirim ulang data
  header("Location: " . $_SERVER['PHP_SELF']);
  exit();
}

// Hapus menu
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  $conn->query("DELETE FROM menu WHERE id_menu=$id");
}
?>



<div class="container">
    <h2 class="mb-4">Data Pembayaran</h2>
<form method="post" enctype="multipart/form-data">
  <input type="text" name="name" placeholder="Nama Menu" required>
  <input type="text" name="harga" id="harga" placeholder="Harga" required>
  
  <select name="kategori" required>
    <option value="" disabled selected>Pilih Kategori</option>
    <option value="food">Food</option>
    <option value="drink">Drink</option>
  </select>
  
  <input type="file" name="image" required>
  <button type="submit">Tambah</button>
</form>

<table border="1" cellspacing="0" cellpadding="5">
  <tr><th>Gambar</th><th>Nama Menu</th><th>Harga</th><th>Kategori</th><th>Aksi</th></tr>
<?php
$res = $conn->query("SELECT * FROM menu");
while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td><img src='../uploads/{$row['image']}' width='60'></td>
            <td>{$row['name']}</td>
            <td>{$row['harga']}</td>
            <td>{$row['kategori']}</td>
            <td>
              <form method='GET' onsubmit='return confirm(\"Yakin ingin hapus?\")'>
                <input type='hidden' name='hapus' value='{$row['id_menu']}'>
                <button type='submit' class='btn btn-danger btn-sm' title='Hapus'>
                  <i class='bi bi-trash'></i>
                </button>
              </form>
            </td>
          </tr>";
}
?>

</table>


<script>
  const hargaInput = document.getElementById('harga');

  hargaInput.addEventListener('input', function(e) {
    // Hapus semua karakter selain angka
    let angka = this.value.replace(/[^0-9]/g, '');

    // Format ke format rupiah
    this.value = formatRupiah(angka, 'Rp ');
  });

  function formatRupiah(angka, prefix) {
    let number_string = angka.toString();
    let sisa = number_string.length % 3;
    let rupiah = number_string.substr(0, sisa);
    let ribuan = number_string.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
      let separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }

    return prefix + rupiah;
  }
</script>
