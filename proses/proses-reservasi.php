<?php
include 'koneksi.php'; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $person = $_POST['person'];

  
    $dateFormatted = date('Y-m-d', strtotime(str_replace('/', '-', $date)));

    $query = "INSERT INTO reservasi 
    (first_name, last_name, email, phone, tanggal, waktu, jumlah_orang) 
    VALUES 
    ('$first_name', '$last_name', '$email', '$phone', '$dateFormatted', '$time', '$person')";

    if (mysqli_query($conn, $query)) {
        echo "<script>
        alert('Reservasi berhasil!');
        window.location.href = '../menu.php';
    </script>";
    
    } else {
        echo "Gagal menyimpan data: " . mysqli_error($conn);
    }
} else {
    echo "Metode tidak valid.";
}
?>
