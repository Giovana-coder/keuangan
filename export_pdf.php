```php
<?php

session_start();
include 'koneksi.php';

require 'vendor/autoload.php';

use Dompdf\Dompdf;

if(!isset($_SESSION['id_user'])){
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$data = mysqli_query(
    $conn,
    "SELECT *
    FROM transaksi
    WHERE user_id='$id_user'
    ORDER BY created_at DESC"
);

$pemasukan = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(nominal) as total
        FROM transaksi
        WHERE user_id='$id_user'
        AND jenis='pemasukan'"
    )
);

$pengeluaran = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(nominal) as total
        FROM transaksi
        WHERE user_id='$id_user'
        AND jenis='pengeluaran'"
    )
);

$totalMasuk = $pemasukan['total'] ?? 0;
$totalKeluar = $pengeluaran['total'] ?? 0;
$saldo = $totalMasuk - $totalKeluar;

$html = '

<h2 style="text-align:center;">
Laporan Keuangan
</h2>

<p>
<b>Total Pemasukan:</b> Rp '.number_format($totalMasuk,0,",",".").'<br>
<b>Total Pengeluaran:</b> Rp '.number_format($totalKeluar,0,",",".").'<br>
<b>Saldo:</b> Rp '.number_format($saldo,0,",",".").'
</p>

<table border="1" width="100%" cellspacing="0" cellpadding="5">

<tr>
    <th>No Akun</th>
    <th>Jenis</th>
    <th>Deskripsi</th>
    <th>Nominal</th>
    <th>Tanggal</th>
</tr>

';

while($row = mysqli_fetch_assoc($data)){

    $html .= '

    <tr>

        <td>'.$row['nomor_akun'].'</td>

        <td>'.$row['jenis'].'</td>

        <td>'.$row['deskripsi'].'</td>

        <td>Rp '.number_format($row['nominal'],0,",",".").'</td>

        <td>'.$row['created_at'].'</td>

    </tr>

    ';
}

$html .= '</table>';

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4','portrait');

$dompdf->render();

$dompdf->stream(
    "laporan_keuangan.pdf",
    ["Attachment" => true]
);  
