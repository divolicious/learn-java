<?php
$koneksi = mysqli_connect("localhost", "root", "", "learn_story");
// cek koneksi
if (!$koneksi) {
    die("Error koneksi: " . mysqli_connect_errno());
}
?>