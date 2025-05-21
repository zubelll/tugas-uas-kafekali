<?php
include '../proses/koneksi.php';

// Tambah user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nama'], $_POST['email'], $_POST['password'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $conn->query("INSERT INTO users (nama, email, password) VALUES ('$nama', '$email', '$password')");
}

// Hapus user//
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $id = intval($_POST['hapus']);

    // Cek apakah user punya pesanan
    $check = $conn->query("SELECT * FROM pesanan WHERE id_user = $id");
    if ($check->num_rows > 0) {
        echo "<script>alert('User tidak bisa dihapus karena memiliki riwayat pesanan.'); window.location='index.php';</script>";
        exit;
    }

    // Jika tidak punya pesanan, lanjut hapus
    $conn->query("DELETE FROM users WHERE id_user=$id");
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Daftar Pengguna</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    input, button { padding: 8px; margin: 5px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
    .btn-hapus {
      background-color: #e74c3c;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 5px;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn-hapus:hover { background-color: #c0392b; }
  </style>

  <!-- SweetAlert2 + Font Awesome -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container">
    <h2 class="mb-4">Data Pembayaran</h2>

<form method="post">
  <input type="text" name="nama" placeholder="Nama User" required>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Password" required>
  <button type="submit">Tambah</button>
</form>

<table>
  <tr>
    <th>Nama</th>
    <th>Email</th>
    <th>Password</th>
    <th>Aksi</th>
  </tr>
  <?php
  $res = $conn->query("SELECT * FROM users");
  while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td>{$row['nama']}</td>
            <td>{$row['email']}</td>
            <td>{$row['password']}</td>
            <td>
              <form method='post' class='hapus-form' data-nama='{$row['nama']}'>
                <input type='hidden' name='hapus' value='{$row['id_user']}'>
                <button type='button' class='btn-hapus'><i class='fas fa-trash'></i> Hapus</button>
              </form>
            </td>
          </tr>";
  }
  ?>
</table>

<script>
//pop up hapus// 
document.querySelectorAll('.hapus-form').forEach(form => {
  form.querySelector('button').addEventListener('click', function () {
    const nama = form.getAttribute('data-nama');
    Swal.fire({
      title: 'Yakin hapus?',
      text: `User "${nama}" akan dihapus!`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#e74c3c',
      cancelButtonColor: '#aaa',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});
</script>

</body>
</html>
