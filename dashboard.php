<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

if (isset($_POST['simpan'])) {

    $nomor_akun = mysqli_real_escape_string($conn, $_POST['nomor_akun']);
    $jenis = mysqli_real_escape_string($conn, $_POST['jenis']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $nominal = mysqli_real_escape_string($conn, $_POST['nominal']);

    mysqli_query(
        $conn,
        "INSERT INTO transaksi
        (user_id, nomor_akun, jenis, deskripsi, nominal)
        VALUES
        ('$id_user','$nomor_akun','$jenis','$deskripsi','$nominal')"
    );

    header("Location: dashboard.php");
    exit;
}

$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';
$sort = $_GET['sort'] ?? 'id';

$allowedSort = [
    'id',
    'nomor_akun',
    'deskripsi',
    'nominal'
];

if (!in_array($sort, $allowedSort)) {
    $sort = 'id';
}

$where = "WHERE user_id='$id_user'";

if (!empty($search)) {
    $where .= " AND (
        nomor_akun LIKE '%$search%'
        OR deskripsi LIKE '%$search%'
    )";
}

if ($filter == 'pemasukan') {
    $where .= " AND jenis='pemasukan'";
}

if ($filter == 'pengeluaran') {
    $where .= " AND jenis='pengeluaran'";
}

$data = mysqli_query(
    $conn,
    "SELECT *
    FROM transaksi
    $where
    ORDER BY $sort DESC"
);

$pemasukan = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(nominal) AS total
        FROM transaksi
        WHERE user_id='$id_user'
        AND jenis='pemasukan'"
    )
);

$pengeluaran = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT SUM(nominal) AS total
        FROM transaksi
        WHERE user_id='$id_user'
        AND jenis='pengeluaran'"
    )
);

$totalMasuk = $pemasukan['total'] ?? 0;
$totalKeluar = $pengeluaran['total'] ?? 0;
$saldo = $totalMasuk - $totalKeluar;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sistem Manajemen Keuangan</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            background:#f4f4f4;
            margin:30px;
        }

        .container{
            background:white;
            padding:20px;
            border-radius:10px;
        }

        input,
        select{
            padding:10px;
            margin-bottom:10px;
        }

        button{
            padding:10px 15px;
            cursor:pointer;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table,th,td{
            border:1px solid #ddd;
        }

        th{
            background:#f0f0f0;
        }

        th,td{
            padding:10px;
            text-align:left;
        }

        .summary{
            display:flex;
            gap:20px;
            flex-wrap:wrap;
            margin:20px 0;
        }

        .card{
            background:#f8f8f8;
            padding:15px;
            border-radius:8px;
            min-width:220px;
        }

        .logout{
            float:right;
        }

    </style>
</head>
<body>

<div class="container">

    <a class="logout" href="logout.php">Logout</a>

    <h1>Sistem Manajemen Keuangan</h1>

    <p>
        Selamat datang,
        <b><?= $_SESSION['nama']; ?></b>
    </p>

    <div class="summary">

        <div class="card">
            <h3>Total Pemasukan</h3>
            <p>Rp <?= number_format($totalMasuk,0,',','.'); ?></p>
        </div>

        <div class="card">
            <h3>Total Pengeluaran</h3>
            <p>Rp <?= number_format($totalKeluar,0,',','.'); ?></p>
        </div>

        <div class="card">
            <h3>Saldo</h3>
            <p><b>Rp <?= number_format($saldo,0,',','.'); ?></b></p>
        </div>

    </div>

    <hr>

    <h2>Tambah Transaksi</h2>

    <form method="POST">

        <input
            type="text"
            name="nomor_akun"
            maxlength="4"
            placeholder="Nomor Akun (4 Digit)"
            required
        >

        <select name="jenis" required>
            <option value="pemasukan">Pemasukan</option>
            <option value="pengeluaran">Pengeluaran</option>
        </select>

        <input
            type="text"
            name="deskripsi"
            placeholder="Deskripsi"
            required
        >

        <input
            type="number"
            name="nominal"
            placeholder="Nominal"
            required
        >

        <button
            type="submit"
            name="simpan"
        >
            Simpan
        </button>

    </form>

    <hr>

    <h2>Data Transaksi</h2>
    

    <form method="GET">

        <input
            type="text"
            name="search"
            placeholder="Cari nomor akun / deskripsi"
            value="<?= htmlspecialchars($search); ?>"
        >

        <select name="filter">

            <option value="">Semua</option>

            <option
                value="pemasukan"
                <?= $filter == 'pemasukan' ? 'selected' : ''; ?>
            >
                Pemasukan
            </option>

            <option
                value="pengeluaran"
                <?= $filter == 'pengeluaran' ? 'selected' : ''; ?>
            >
                Pengeluaran
            </option>

        </select>

        <select name="sort">

            <option value="id">
                Terbaru
            </option>

            <option
                value="nomor_akun"
                <?= $sort == 'nomor_akun' ? 'selected' : ''; ?>
            >
                Nomor Akun
            </option>

            <option
                value="deskripsi"
                <?= $sort == 'deskripsi' ? 'selected' : ''; ?>
            >
                Deskripsi
            </option>

            <option
                value="nominal"
                <?= $sort == 'nominal' ? 'selected' : ''; ?>
            >
                Nominal
            </option>

        </select>

        <button type="submit">
            Terapkan
        </button>

    <button type="button" onclick="window.location='export_excel.php'">
    Export Excel
    </button>

    <a href="export_pdf.php">
    <button type="button">
        Export PDF
    </button>
    </a>

    </form>

    <table>

        <tr>
            <th>No Akun</th>
            <th>Jenis</th>
            <th>Deskripsi</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>

        <?php while($row = mysqli_fetch_assoc($data)) { ?>

        <tr>

            <td><?= $row['nomor_akun']; ?></td>

            <td><?= ucfirst($row['jenis']); ?></td>

            <td><?= $row['deskripsi']; ?></td>

            <td>
                Rp <?= number_format($row['nominal'],0,',','.'); ?>
            </td>

            <td>

                <a href="edit.php?id=<?= $row['id']; ?>">
                    Edit
                </a>

                |

                <a
                    href="hapus.php?id=<?= $row['id']; ?>"
                    onclick="return confirm('Yakin ingin menghapus data ini?')"
                >
                    Hapus
                </a>

            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>
</html>