<?php
session_start();

// Cek apakah user sudah login dan berlevel 'pelanggan'
if (!isset($_SESSION['id_user']) || $_SESSION['level'] !== 'pelanggan') {
    header("Location: ../login.php");
    exit();
}

require 'proses/koneksi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kafe Kali</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Open+Sans&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

  <style>
    .navbar-custom {
      background-color: white;
      transition: background-color 0.3s ease;
    }
    .navbar-transparent {
      background-color: transparent !important;
    }
    .navbar-brand {
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.6rem;
      letter-spacing: 0.5px;
    }
  </style>
</head>
<body>

<!-- navbar -->
<nav class="navbar navbar-expand-lg navbar-dark shadow-sm fixed-top navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><i>Kafe Kali</i></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto">
        <a class="nav-link active" href="#">Home</a>
        <a class="nav-link" href="#about">About Us</a>
        <a class="nav-link" href="#menu">Menu</a>
        <a class="nav-link" href="#reservasi">Reservasi</a>
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

<!-- header -->
<header>
  <img src="img/g2.webp" class="header-bg" alt="header background" />
  <h1>Restaurant & Coffee Shop</h1>
  <h2>Kafe Kali</h2>
</header>

<!-- What We Can Offer -->
<section id="about" class="scroll-anchor">
  <h2>What We Can Offer</h2>
  <p>from our hearts</p>
  <div class="offerings">
    <div class="offer">
      <img src="img/picture1.png" alt="Healthy Deserts" />
      <h3><strong><em>Healthy</em></strong> Deserts</h3>
      <p>Hidangan penutup yang sehat, dibuat tanpa pengawet dan gula berlebih.</p>
    </div>
    <div class="offer">
      <img src="img/picture2.png" alt="Eco Product" />
      <h3><em>Eco</em> Product</h3>
      <p>Setiap menu dibuat langsung saat dipesan, dijamin fresh!</p>
    </div>
    <div class="offer">
      <img src="img/picture3.png" alt="Always Fresh" />
      <h3><strong><em>Always</em></strong> Fresh</h3>
      <p>Dari bahan alami & kemasan ramah lingkungan — baik buat kamu, baik buat bumi.</p>
    </div>
  </div>
</section>

<!-- Welcome Sections -->
<section class="split">
  <div><img src="img/gurame.jpg" class="menu-img" alt="Nasi Gurame" /></div>
  <div>
    <h2>Welcome to <br /><strong>Kafe Kali</strong></h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  </div>
</section>

<section class="split" style="padding: 40px">
  <div style="flex: 1">
    <h2>Welcome to <br /><strong>Kafe Kali</strong></h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  </div>
  <div style="flex: 1; text-align: right">
    <img src="img/senja.jpg" style="max-width: 100%; border-radius: 8px" />
  </div>
</section>

<!-- Menu Section -->
<section id="menu" class="scroll-anchor">
  <div class="menu-title">
    <h2>Menu</h2>
    <p>From Our Hearts</p>
  </div>
</section>

<!-- Coffee Menu -->
<div class="menu-category">
  <h3 style="margin-left: -750px">Food</h3>
  <div class="menu-items d-flex flex-wrap justify-content-center gap-4">
    <?php
    include 'proses/koneksi.php';
    $query = "SELECT * FROM menu WHERE kategori = 'drink' LIMIT 4";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) :
    ?>
      <div class="menu-card text-center">
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="width:150px; height:150px; object-fit:cover;">
        <h4><?= htmlspecialchars($row['name']) ?></h4>
        <div class="price"><?= htmlspecialchars($row['harga']) ?></div>
          </button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>

 
  <div class="text-center mt-4">
    <a href="menu.php" class="btn btn-outline-primary">More</a>
  </div>
</div>


<!-- Food Menu -->
<div class="menu-category">
  <h3 style="margin-left: -750px">Food</h3>
  <div class="menu-items d-flex flex-wrap justify-content-center gap-4">
    <?php
    include 'proses/koneksi.php';
    $query = "SELECT * FROM menu WHERE kategori = 'food' LIMIT 4";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($result)) :
    ?>
      <div class="menu-card text-center">
        <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="width:150px; height:150px; object-fit:cover;">
        <h4><?= htmlspecialchars($row['name']) ?></h4>
        <div class="price"><?= htmlspecialchars($row['harga']) ?></div>
          
        </form>
      </div>
    <?php endwhile; ?>
  </div>

<!-- Reservasi -->
<section id="reservasi" class="scroll-anchor">
  <div class="bg-wrapper">
    <img src="img/rev1.webp" class="bg-img" />
    <div class="form-container">
      <h2>Select a Date & Book Now</h2>
      <p>We will confirm your order via Email or Phone</p>
      <form action="proses/proses-reservasi.php" method="POST">
        <div class="form-row">
          <input type="text" name="first_name" placeholder="First name" required />
          <input type="text" name="last_name" placeholder="Last name" required />
        </div>
        <div class="form-row">
          <input type="email" name="email" placeholder="E-mail address" required />
          <input type="text" name="phone" placeholder="Phone number" required />
        </div>
        <div class="form-row">
          <label>Tanggal</label>
          <input type="date" name="date" required />
          <label>Jam</label>
          <input type="time" name="time" required />
        </div>
        <div class="form-row">
          <label>Jumlah orang</label>
          <input type="number" name="person" min="1" placeholder="Person" required />
        </div>
        <button type="submit" class="book-btn">BOOK NOW</button>
      </form>
    </div>
  </div>
</section>

<!-- Script Navbar Transparan -->
<script>
  window.addEventListener('scroll', function () {
    const navbar = document.querySelector('.navbar');
    if (window.scrollY > 50) {
      navbar.classList.add('navbar-transparent');
    } else {
      navbar.classList.remove('navbar-transparent');
    }
  });
</script>

<footer>&copy; 2025 Kafe Kali. All rights reserved.</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
