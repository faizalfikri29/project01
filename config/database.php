<?php
// File: config/db.php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "dbkegiatan_dosen";

$conn = mysqli_connect($host, $user, $pass, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
