<?php 
session_start(); 
include("koneksi.php");

$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE nama='$nama' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();

    $_SESSION['id_user'] = $data['id_user'];
$_SESSION['nama'] = $data['nama']; 
$_SESSION['level'] = $data['level'];


    if ($data['level'] == 'admin') {
        header("Location: ../admin/index.php");
    } else if ($data['level'] == 'pelanggan') {
        header("Location: ../index.php");
    } else {
        echo "Level user tidak dikenali.";
    }
    exit();
} else {
    header("Location: ../login.php?error=1");
exit();
}

$conn->close(); 
?>
x