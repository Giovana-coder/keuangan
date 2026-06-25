<?php

session_start();
include 'koneksi.php';

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM transaksi WHERE id='$id'"
);

header("Location: dashboard.php");
exit;