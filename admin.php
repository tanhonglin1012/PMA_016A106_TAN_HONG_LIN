<?php
session_start();
require('config.php');

//Semak sesi supaya hanya admin boleh mengakses halaman ini.
if (!isset($_SESSION['noKP']) || $_SESSION['noKP'] !== 'admin') {
    echo "<script>alert('Akses Ditolak! Hanya Admin yang dibenarkan.');</script>";
    echo "<script>window.location.replace('MenuLogin.php');</script>";
    exit();
}

//Kira jumlah pengundi unik yang telah membuang undi.
$query_jumlah = mysqli_query($con, "SELECT COUNT(DISTINCT noKP) AS jumlah FROM rekod_undi");
$data_jumlah = mysqli_fetch_assoc($query_jumlah);
$jumlahPengundi = $data_jumlah['jumlah'] ? $data_jumlah['jumlah'] : 0;
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pengundian Kelab Rekreasi</title>
    <link rel="stylesheet" href="stylesheet.css">
 
</head>
<body>

    <header>
        <center><h1>SISTEM PENGUNDIAN KELAB REKREASI</h1></center>
    </header>

    <?php include('color_switch.php'); ?>
    <?php include('adminnavigationbar.php'); ?>

    <main>
        <div class="admin-dashboard">
            <div class="dashboard-header">
                <h2>Keputusan Undian</h2>
            </div>

            <div class="stat-banner">
                Jumlah Pengundi Yang Telah Membuang Undi: <strong><?php echo $jumlahPengundi; ?> Orang</strong>
            </div>

            <?php //Bahagian keputusan mengikut kategori (sama seperti keputusan.php). ?>
            <div class="section-heading">
                <h2>Keputusan Keseluruhan</h2>
            </div>

            <?php include('_keputusan_table.php'); ?>
        </div>
    </main>

    <footer>
        <center><p>HAK CIPTA @ TAN HONG LIN</p></center>
    </footer>

</body>
</html>
