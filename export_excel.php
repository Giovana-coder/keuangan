<?php

session_start();
include 'koneksi.php';

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_keuangan.xls");

$data = mysqli_query(
    $conn,
    "SELECT *
    FROM transaksi
    WHERE user_id='$id_user'
    ORDER BY id DESC"
);

?>

<table border="1">

<tr>
    <th>No Akun</th>
    <th>Jenis</th>
    <th>Deskripsi</th>
    <th>Nominal</th>
    <th>Tanggal</th>
</tr>

<?php while($row = mysqli_fetch_assoc($data)){ ?>

<tr>

    <td><?= $row['nomor_akun']; ?></td>

    <td><?= $row['jenis']; ?></td>

    <td><?= $row['deskripsi']; ?></td>

    <td><?= $row['nominal']; ?></td>

    <td><?= $row['created_at']; ?></td>

</tr>

<?php } ?>

</table>