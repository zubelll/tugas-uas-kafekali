
<?php
include '../proses/koneksi.php';



?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <link rel="stylesheet" href="style.css">
  <!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  <div class="container">
    <h1>Dashboard Admin</h1>
    <div class="tabs">
  <a href="?tab=menu" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'menu') ? 'active' : ''; ?>">Menu</a>
  <a href="?tab=users" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'users') ? 'active' : ''; ?>">Users</a>
  <a href="?tab=orders" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'orders') ? 'active' : ''; ?>">Pesanan</a>
  <a href="?tab=pembayaran" class="<?php echo (isset($_GET['tab']) && $_GET['tab'] === 'pembayaran') ? 'active' : ''; ?>">Pembayaran</a>
  <a href="../proses/proses-logout.php" onclick="return confirm('Yakin ingin logout?')">Logout</a>
</div>

<div class="content">
  <?php
    $tab = $_GET['tab'] ?? 'menu';
    if ($tab == 'menu') {
      include 'menu.php';
    } elseif ($tab == 'users') {
      include 'users.php';
    
    } elseif ($tab == 'orders') {
      include 'orders.php'; 
    }elseif ($tab == 'pembayaran') {
      include 'pembayaran.php';
    }
  ?>
</div>

</body>
</html>
