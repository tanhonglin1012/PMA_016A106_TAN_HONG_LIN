<?php
//Memulakan sesi PHP.
session_start();

//Memanggil fail sambungan pangkalan data.
require('config.php');

//Pastikan pengguna telah log masuk sebelum mengakses halaman ini.
if (!isset($_SESSION['noKP'])) {
    echo "<script>alert('Sila log masuk terlebih dahulu.');</script>";
    echo "<script>window.location.replace('MenuLogin.php');</script>";
    exit();
}

//Dapatkan noKP pengguna daripada sesi.
$noKP = $_SESSION['noKP'];

//Ambil nama pengundi daripada jadual pengundi untuk dipaparkan.
$query_nama = mysqli_query($con, "SELECT nama FROM pengundi WHERE noKP='$noKP'");
$data_Nama = mysqli_fetch_assoc($query_nama);

//Gunakan nama pengundi jika dijumpai, jika tidak gunakan teks lalai.
$namaPengundi = $data_Nama ? $data_Nama['nama'] : 'Pengundi';

//Kira jumlah pengundi unik yang telah membuang undi.
$query_jumlah = mysqli_query($con, "SELECT COUNT(DISTINCT noKP) AS jumlah FROM rekod_undi");
$data_jumlah = mysqli_fetch_assoc($query_jumlah);

//Gunakan jumlah undian jika ada data, jika tidak gunakan 0.
$jumlahPengundi = $data_jumlah['jumlah'] ? $data_jumlah['jumlah'] : 0;
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengundian Kelab Rekreasi</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>

    <?php //Tajuk utama. ?>
    <header>
        <h1>SISTEM PENGUNDIAN KELAB REKREASI</h1>
    </header>

    <?php //Masukkan suis tema cerah dan gelap. ?>
    <?php include('color_switch.php'); ?>

    <?php //Kandungan utama halaman keputusan. ?>
    <main>
        <div class="container">

            <?php //Bahagian statistik pengundi. ?>
            <div class="welcome-stats">
                <?php //Papar nama pengundi yang log masuk. ?>
                <?php //htmlspecialchars() melindungi paparan daripada XSS. ?>
                <p>Selamat Datang, <strong><?php echo htmlspecialchars($namaPengundi); ?></strong></p>
                <?php //Papar jumlah pengundi yang telah mengundi. ?>
                <p>Jumlah Pengundi yang telah mengundi: <strong><?php echo $jumlahPengundi; ?></strong></p>
            </div>

            <?php //Tajuk bahagian keputusan. ?>
            <div class="section-heading">
                <h2>Keputusan Keseluruhan</h2>
            </div>

            <?php //Masukkan fail jadual keputusan. ?>
            <?php include('_keputusan_table.php'); ?>

            <?php //Butang log keluar. ?>
            <div class="action-bar centered-bar">
                <a href="logkeluar.php" class="btn-logkeluar">LOG KELUAR</a>
            </div>
        </div>
    </main>

    <?php //Bahagian pengaki. ?>
    <footer>
        <p>HAK CIPTA @ TAN HONG LIN</p>
    </footer>

</body>
</html>
