<?php
session_start();


if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit();
}


$nama_user = $_SESSION['nama_user'] ?? 'Pelanggan';
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order Sukses | Cafe Kali</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fefefe;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    .container {
      padding: 50px;
    }
    h1 {
      color: #4CAF50;
    }
    .success-box {
      background-color: #e8f5e9;
      padding: 30px;
      border-radius: 10px;
      display: inline-block;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }
    a.button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #ff5e5e;
      color: white;
      text-decoration: none;
      border-radius: 5px;
    }
    a.button:hover {
      background-color: #e14e4e;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="success-box">
      <h1>Terima kasih, <?= htmlspecialchars($nama_user) ?>!</h1>
      <p>Pesanan Anda telah berhasil dibuat dan sedang diproses.</p>
      <p>Silakan tunggu konfirmasi atau pantau status di halaman akun Anda.</p>
      <a class="button" href="../index.php">Home</a>
    </div>
  </div>
</body>
</html>
