<?php
session_start();
include 'proses/koneksi.php';

if (isset($_POST['id_menu'])) {
    $id_user = $_SESSION['id_user'];
    $id_menu = $_POST['id_menu'];

    // Cek ap menu udah ada di keranjang
    $cek = mysqli_query($conn, "SELECT * FROM keranjang WHERE id_user='$id_user' AND id_menu='$id_menu'");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id_user='$id_user' AND id_menu='$id_menu'");
    } else {
        mysqli_query($conn, "INSERT INTO keranjang (id_user, id_menu, jumlah) VALUES ('$id_user', '$id_menu', 1)");
    }
}

// Ambil data keranjang 
$id_user = $_SESSION['id_user'];
$query = "
    SELECT 
        keranjang.id_keranjang, 
        menu.name, 
        menu.image, 
        menu.harga, 
        keranjang.jumlah 
    FROM keranjang 
    JOIN menu ON keranjang.id_menu = menu.id_menu 
    WHERE keranjang.id_user = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$cartData = [];
while ($row = $result->fetch_assoc()) {
    $harga = preg_replace('/[^0-9]/', '', $row['harga']);
    $cartData[] = [
        'id' => $row['id_keranjang'],
        'name' => $row['name'],
        'price' => (int)$harga,
        'quantity' => (int)$row['jumlah'],
        'image' => 'uploads/' . $row['image']
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Keranjang | Cafe Kali</title>
  <link rel="stylesheet" href="css/keranjang.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
      color: #333;
    }
    .wrapper {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    main {
      flex: 1;
    }
    footer {
      background-color: #f3f3f3;
      padding: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <header>
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
              <a class="nav-link" href="menu.php">Menu</a>
              <a class="nav-link" href="index.php#reservasi">Reservasi</a>
            </div>
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

    <div class="container content-wrapper py-3" style="margin-top: 80px;">
      <main>
        <div class="steps">
          <button class="active">View chart</button>
          <button class="inactive">Checkout</button>
        </div>

        <table class="table">
          <thead>
            <tr>
              <th>Gambar</th>
              <th>Produk</th>
              <th>Harga</th>
              <th>Jumlah</th>
              <th>Subtotal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody id="cart-items"></tbody>
        </table>

        <div class="total-box">
          <p><strong>Total Keranjang:</strong> <span id="total">Rp 0</span></p>
          <a class="checkout-button btn btn-primary" href="orders.php">Lanjut ke Checkout</a>
        </div>
      </main>
    </div>

    <footer>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
      <p>📍 Street name 1, City | ☎️ +569 2698 0256</p>
      <p>📧 email1@companyname.com | 📧 email2@companyname.com</p>
      <div><a href="#">Instagram</a> | <a href="#">Facebook</a> | <a href="#">Twitter</a></div>
    </footer>
  </div>

  <script>
    const cartData = <?= json_encode($cartData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;

    function formatRupiah(angka) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
      }).format(angka);
    }

    function updateCart() {
      const tbody = document.getElementById('cart-items');
      const checkoutBtn = document.querySelector('.checkout-button');
      tbody.innerHTML = '';
      let total = 0;

      if (cartData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="text-center">Keranjang kamu kosong 🛒</td></tr>';
        checkoutBtn.style.display = 'none';
        document.getElementById('total').textContent = 'Rp 0';
        return;
      }

      checkoutBtn.style.display = 'inline-block';

      cartData.forEach((item, index) => {
        const subtotal = item.price * item.quantity;
        total += subtotal;

        const row = document.createElement('tr');
        row.innerHTML = `
          <td><img class="product-img" src="${item.image}" alt="${item.name}" width="60" /></td>
          <td>${item.name}</td>
          <td>${formatRupiah(item.price)}</td>
          <td>
            <div class="quantity d-flex gap-1 align-items-center">
              <button class="btn btn-sm btn-outline-danger" onclick="updateQuantity(${index}, -1)">-</button>
              <span>${item.quantity}</span>
              <button class="btn btn-sm btn-outline-success" onclick="updateQuantity(${index}, 1)">+</button>
            </div>
          </td>
          <td>${formatRupiah(subtotal)}</td>
          <td><a href="proses/hapus-keranjang.php?id_keranjang=${item.id}" onclick="return confirm('Yakin hapus item ini?')" class="btn btn-sm btn-danger">x</a></td>
        `;
        tbody.appendChild(row);
      });

      document.getElementById('total').textContent = formatRupiah(total);
    }

    function updateQuantity(index, change) {
      const item = cartData[index];
      let newQty = item.quantity + change;
      if (newQty < 1) newQty = 1;

      cartData[index].quantity = newQty;
      updateCart();

      const formData = new FormData();
      formData.append('id_keranjang', item.id);
      formData.append('jumlah', newQty);

      fetch('proses/update_jumlah.php', {
        method: 'POST',
        body: formData,
      })
      .then(response => {
        if (!response.ok) {
          alert('Gagal update jumlah!');
          cartData[index].quantity -= change;
          updateCart();
        }
      })
      .catch(() => {
        alert('Koneksi ke server gagal!');
        cartData[index].quantity -= change;
        updateCart();
      });
    }

    updateCart();
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
