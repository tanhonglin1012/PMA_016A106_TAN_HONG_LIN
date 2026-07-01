<?php
//Memulakan sesi PHP.
session_start();

//Memanggil fail sambungan pangkalan data.
require "config.php";

//Pastikan hanya admin boleh mengakses halaman ini.
if (!isset($_SESSION['noKP']) || $_SESSION['noKP'] !== 'admin') {
    header("Location: MenuLogin.php");
    exit();
}

//Jalankan proses import apabila butang import ditekan.
if (isset($_POST['import'])) {

        //Semak sama ada fail telah dimuat naik.
    if ($_FILES['DataPengundi']['name']) {

        //Pecahkan nama fail kepada bahagian nama dan sambungan.
        $filename = explode('.', $_FILES['DataPengundi']['name']);

        //Semak sama ada sambungan fail ialah csv.
        if (end($filename) == "csv") {

            //Buka fail CSV untuk dibaca.
            $handle = fopen($_FILES['DataPengundi']['tmp_name'], "r");

            //Kira bilangan data baharu yang berjaya dimasukkan.
            $bil_berjaya = 0;

            //Langkau baris pertama yang mengandungi tajuk CSV.
            fgetcsv($handle);

            //Baca setiap baris CSV sebagai array.
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {

                //Ambil NoKP dan nama daripada data CSV.
                $noKP = mysqli_real_escape_string($con, $data[0]);
                $nama = mysqli_real_escape_string($con, $data[1]);

                //Tetapkan kata laluan lalai untuk pengguna import.
                $katalaluan = "123";

                //Pastikan NoKP tidak kosong sebelum data dimasukkan.
                if (!empty($noKP)) {

                    //Masukkan data pengundi dan langkau jika rekod sudah wujud.
                    $sql1 = "INSERT IGNORE INTO pengundi (noKP, nama) VALUES ('$noKP', '$nama')";
                    //Masukkan akaun pengguna untuk tujuan log masuk.
                    $sql2 = "INSERT IGNORE INTO pengguna (namaPengguna, kataLaluan) VALUES ('$noKP', '$katalaluan')";

                    //Laksanakan kedua-dua arahan SQL.
                    if (mysqli_query($con, $sql1) && mysqli_query($con, $sql2)) {
                        //Tambah kiraan jika rekod baharu benar-benar dimasukkan.
                        if (mysqli_affected_rows($con) > 0) {
                            $bil_berjaya++;
                        }
                    }
                }
            }

            //Tutup fail CSV selepas selesai membaca.
            fclose($handle);

            //Papar mesej berjaya dan halakan ke halaman admin.
            echo "<script>alert('Import selesai! $bil_berjaya data baharu telah dimasukkan.'); window.location.href='admin.php';</script>";

        } else {
            //Papar ralat jika fail bukan format CSV.
            echo "<script>alert('Ralat: Sila pastikan fail anda dalam format .csv');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Data Pengundi</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>

    <?php //Tajuk utama. ?>
    <header>
        <h1>SISTEM PENGUNDIAN KELAB REKREASI</h1>
    </header>

    <?php //Masukkan suis tema cerah dan gelap. ?>
    <?php include('color_switch.php'); ?>

    <?php //Masukkan bar navigasi admin. ?>
    <?php include('adminnavigationbar.php'); ?>

    <?php //Kandungan utama untuk borang import CSV. ?>
    <main>
        <div class="import-container">
            <?php //Tajuk halaman import. ?>
            <h2>Muat Naik Data Pengundi (CSV)</h2>
            <?php //Arahan ringkas kepada pengguna. ?>
            <p>Sila pilih fail CSV yang mengandungi <b>No KP</b> dan <b>Nama</b>.</p>

            <?php //Borang muat naik fail CSV. ?>
            <?php //enctype diperlukan untuk proses muat naik fail. ?>
            <form method="POST" enctype="multipart/form-data">
                <?php //Medan input hanya menerima fail CSV. ?>
                <input type="file" name="DataPengundi" class="file-input" accept=".csv" required>
                <br>
                <?php //Butang untuk memulakan proses import. ?>
                <button type="submit" name="import" class="btn-mula">MULA IMPORT SEKARANG</button>
            </form>

            <br>
            <?php //Butang kembali ke halaman admin. ?>
            <div class="action-bar import-actions">
                <a href="admin.php" class="btn-kembali">Kembali Ke Admin</a>
            </div>
        </div>
    </main>

    <footer>
        <p>HAK CIPTA @ TAN HONG LIN</p>
    </footer>
</body>
</html>
