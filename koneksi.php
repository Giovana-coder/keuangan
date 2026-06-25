<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect(
    "127.0.0.1",
    "marvel",
    "123456",
    "keuangan"
);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}