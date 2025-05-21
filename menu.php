<?php
session_start();
include 'proses/koneksi.php';

if (isset($_POST['id_menu'])) {
    $id_user = $_SESSION['id_user'];
    $id_menu = $_POST['id_menu'];

    // cek apa menu udah ada di keranjang
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user='$id_user' AND id_menu='$id_menu'");
    if (mysqli_num_rows($cek) > 0) {
        
        mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_user='$id_user' AND id_menu='$id_menu'");
    } else {
       
        mysqli_query($conn, "INSERT INTO keranjang (id_user, id_menu, jumlah) VALUES ('$id_user', '$id_menu', 1)");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MENU</title>
    <link rel="stylesheet" href="css/menu.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    rel="stylesheet" />

  </head>
  <body>
  <main class="container">
    <>
    <?php
      include 'proses/koneksi.php';

      // cek kategori//
      $kategori = isset($_GET['kategori']) ? strtolower($_GET['kategori']) : 'food';

      // data sesuai kategori
      $query = "SELECT * FROM menu WHERE kategori = '$kategori'";
      $result = $conn->query($query);
    ?>

    <!-- Navbar Brand -->
       <nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top" style="background-color: #f3f3f3;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i>Kafe Kali</i></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link active" href="index.php#">Home</a>
        <a class="nav-link" href="index.php#about">About Us</a>
        <a class="nav-link" href="index.php#menu">Menu</a>
        <a class="nav-link" href="index.php#reservasi">Reservasi</a>
      </div>

      <!-- Tampilkan Nama User -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-dark fw-semibold" href="#" role="button" data-bs-toggle="dropdown">
            👤 <?= htmlspecialchars($_SESSION['nama']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="proses/proses-logout.php">Logout</a></li>
            <li><a class="dropdown-item" href="riwayat.php">riwayat</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
    </header>
   <div class="container content-wrapper py-2">
  
</div>

    <!-- Tombol Button -->
    <div class="d-flex justify-content-center my-4">
  <div class="btn-group btn-group-custom" role="group">

    <input type="radio" class="btn-check" name="menuToggle" id="food" autocomplete="off" onchange="window.location.href='?kategori=food'" <?php if ($kategori == 'food') echo 'checked'; ?>>
    <label class="toggle-btn btn btn-outline-primary" for="food">Food</label>

    <input type="radio" class="btn-check" name="menuToggle" id="drink" autocomplete="off" onchange="window.location.href='?kategori=drink'" <?php if ($kategori == 'drink') echo 'checked'; ?>>
    <label class="toggle-btn btn btn-outline-primary" for="drink">Drink</label>

  </div>
</div>

<!-- Card Menu -->
<div class="container">
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
    <?php
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
    ?>
      <div class="col">
        <div class="card h-100 shadow-sm text-center">
          <img src="uploads/<?php echo $row['image']; ?>" 
               class="card-img-top img-fluid mx-auto d-block mt-3" 
               alt="<?php echo $row['name']; ?>" 
               style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title"><?php echo $row['name']; ?></h5>
            <p class="card-text fw-bold text-danger">
            <?php echo $row['harga']; ?>
            </p>
            <!-- tombol  -->
            <form action="proses/proses-order.php" method="post" class="mt-auto">
              <input type="hidden" name="id_menu" value="<?php echo $row['id_menu']; ?>">
              <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">
              <button type="submit" class="btn btn-warning w-100">
                <i class="bi bi-cart-plus"></i> Order
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php
      }
    } else {
      echo "<div class='col-12 text-center text-muted'>Data menu belum ada.</div>";
    }
    ?>
  </div>
</div>

    </main>
    <!-- Footer -->
    
    <footer >
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      <p>📍 Street name 1, City | ☎️ +569 2698 0256</p>
      <p>📧 email1@companyname.com | 📧 email2@companyname.com</p>
      <div><a href="#">Instagram</a> | <a href="#">Facebook</a> | <a href="#">Twitter</a></div>
    </footer>
    

    <!-- JS Bootstrap + Toggle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script>
  document.getElementById('food').addEventListener('change', function() {
    window.location.href = "?kategori=food";
  });
  document.getElementById('drink').addEventListener('change', function() {
    window.location.href = "?kategori=drink";
  });
</script>

  </body>
</html>
