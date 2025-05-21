<?php
if (isset($_GET['error']) && $_GET['error'] == '1') {
    echo "
    <div style='
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    '>
        <div style='
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        '>
            <h3>Login gagal</h3>
            <p>Nama atau password salah.</p>
            <a href='login.php' style='
                display: inline-block;
                margin-top: 15px;
                padding: 10px 20px;
                background: #007bff;
                color: white;
                border-radius: 5px;
                text-decoration: none;
            '>Coba Lagi</a>
        </div>
    </div>
    ";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="css/login.css">
</head>
<body>
  <div class="container">
    <div class="login-box">
      <h2>LOGIN</h2>
      <form action="proses/proses-login.php" method="POST">


        <div class="input-group">
          <label>👤</label>
          <input type="text" name="nama" placeholder="nama" required>
        </div>
        <div class="input-group">
          <label>🔒</label>
          <input type="password" name="password" placeholder="Password" required>

        </div>
        <button type="submit" class="btn">LOGIN</button>
      </form>

      <p class="signup">Belum punya akun? <a href="daftar.php">Daftar?</a></p>
      <p class="alt-login">masuk dengan</p>
      <div class="socials">
        <img src="img/f.png" alt="Facebook">
        <img src="img/apple.svg" alt="Apple">
        <img src="img/gg.png" alt="Google">
      </div>
    </div>
  </div>
</body>
</html>
