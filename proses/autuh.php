<?php
session_start();

if (!isset($_SESSION['id_user'])) {
  // Jika user belum login, redirect ke halaman login
  header("Location: login.php");
  exit();
}
?>
