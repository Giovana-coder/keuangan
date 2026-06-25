<?php

session_start();
include 'koneksi.php';

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$data = mysqli_query(
    $conn,
    "SELECT * FROM transaksi WHERE id='$id'"
);

$row = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){

    $nomor_akun = $_POST['nomor_akun'];
    $jenis = $_POST['jenis'];
    $deskripsi = $_POST['deskripsi'];
    $nominal = $_POST['nominal'];

    mysqli_query(
        $conn,
        "UPDATE transaksi SET
        nomor_akun='$nomor_akun',
        jenis='$jenis',
        deskripsi='$deskripsi',
        nominal='$nominal'
        WHERE id='$id'"
    );

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaksi</title>

    <style>

        body{
            font-family:Arial;
            margin:30px;
        }

        input,
        select{
            width:300px;
            padding:10px;
            margin-bottom:10px;
        }

        button{
            padding:10px 20px;
        }

    </style>

</head>
<body>

<h2>Edit Transaksi</h2>

<form method="POST">

    <input
        type="text"
        name="nomor_akun"
        value="<?= $row['nomor_akun']; ?>"
        required
    >

    <br>

    <select name="jenis">

        <option
            value="pemasukan"
            <?= $row['jenis']=='pemasukan' ? 'selected' : ''; ?>
        >
            Pemasukan
        </option>

        <option
            value="pengeluaran"
            <?= $row['jenis']=='pengeluaran' ? 'selected' : ''; ?>
        >
            Pengeluaran
        </option>

    </select>

    <br>

    <input
        type="text"
        name="deskripsi"
        value="<?= $row['deskripsi']; ?>"
        required
    >

    <br>

    <input
        type="number"
        name="nominal"
        value="<?= $row['nominal']; ?>"
        required
    >

    <br>

    <button
        type="submit"
        name="update"
    >
        Update
    </button>

</form>

<br>

<a href="dashboard.php">
    Kembali
</a>

</body>
</html>