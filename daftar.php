<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Daftar | Ngopi di Kafe Kali</title>
  <link rel="stylesheet" href="css/daftarr.css" />
  <style>
    .modal-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.6);
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .modal-content {
      background: white;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      max-width: 320px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }

    .modal-content h3 {
      margin-bottom: 20px;
      font-size: 20px;
    }

    .modal-content button {
      padding: 10px 20px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }

    .modal-content button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>

  <div class="container">
    <h2>DAFTAR</h2>

    <form action="proses/proses-register.php" method="POST">
      <input type="text" class="form-input" name="nama" placeholder="Nama Lengkap" required />
      <input type="email" class="form-input" name="email" placeholder="E mail" required />
      <input type="password" class="form-input" name="password" placeholder="Password" required />
      <input type="password" class="form-input" name="konfirmasi" placeholder="Konfirmasi password" required />
      
      <button type="submit" class="btn">Konfirmasi</button>
    </form>

    <div class="bottom-text">Sudah Memiliki Akun? <a href="login.php">Masuk</a></div>
  </div>

  <!-- Alert Modal -->
  <div class="modal-overlay" id="successModal">
    <div class="modal-content">
      <h3>Akun berhasil dibuat!</h3>
      <button onclick="window.location.href='login.php'">Kembali ke Home</button>

    </div>
  </div>

  <script>
    // Cek URL apakah ada parameter ?status=success
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
      document.getElementById('successModal').style.display = 'flex';
    }
  </script>
</body>
</html>
