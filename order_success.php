<?php
session_start();
if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Success | Cafe Kali</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      max-width: 600px;
      margin: 100px auto;
      padding: 0 15px;
      text-align: center;
      color: #333;
    }
    h1 {
      color: #ff5e5e;
      margin-bottom: 20px;
    }
    p {
      font-size: 1.2rem;
      margin-bottom: 40px;
    }
    a.button {
      display: inline-block;
      padding: 12px 25px;
      background-color: #ff5e5e;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      transition: background-color 0.3s;
    }
    a.button:hover {
      background-color: #e04e4e;
    }
  </style>
</head>
<body>
  <h1>Terima Kasih!</h1>
  <p>Pesanan Anda sudah kami terima dan sedang diproses.<br> Kami akan menghubungi Anda jika ada informasi lebih lanjut.</p>
  <a href="menu.php" class="button">Kembali ke Menu</a>
</body>
</html>
