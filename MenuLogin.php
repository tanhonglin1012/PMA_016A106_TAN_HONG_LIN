<?php
//Memulakan sesi PHP untuk menyimpan data pengguna yang log masuk.
session_start();

//Memanggil fail sambungan pangkalan data.
//Pemboleh ubah $con digunakan untuk semua operasi pangkalan data.
require('config.php');

//Bahagian proses log masuk dijalankan apabila borang dihantar.
if (isset($_POST['hantar'])) {

    //Ambil data input daripada borang log masuk.
    //ID pengguna digunakan sebagai noKP atau namaPengguna.
    //Kata laluan digunakan untuk pengesahan log masuk.
    $username = $_POST['idPengguna'];
    $password = $_POST['kataLaluan'];

    //Semak sama ada pengguna ialah admin.
    //Akaun admin menggunakan ID tetap admin dan kata laluan 123.
    if ($username === '123456789012' && $password === '123') {

        //Tetapkan sesi noKP sebagai admin untuk pengesahan halaman admin.
        $_SESSION['noKP'] = 'admin';

        //Papar mesej berjaya dan halakan ke halaman admin.
        echo "<script>alert('Anda telah berjaya log masuk sebagai Admin.');</script>";
        echo "<script>window.location.replace('admin.php');</script>";
        exit();
    }

    //Semak akaun pengguna biasa dalam pangkalan data.
    else {

        //Bersihkan data input untuk mengurangkan risiko SQL Injection.
        $username = mysqli_real_escape_string($con, $username);
        $password = mysqli_real_escape_string($con, $password);

        //Cari rekod pengguna berdasarkan ID dan kata laluan.
        $query = "SELECT * FROM pengguna WHERE namaPengguna='$username' AND kataLaluan='$password'";
        $rekod = mysqli_query($con, $query);

        //Kira bilangan rekod yang sepadan dengan data log masuk.
        $hasilCarian = mysqli_num_rows($rekod);

        //Semak sama ada rekod pengguna wujud.
        if ($hasilCarian > 0) {

            //Simpan ID pengguna ke dalam sesi untuk digunakan pada halaman lain.
            $_SESSION['noKP'] = $username;

            //Semak status undian pengguna dalam jadual rekod_undi.
            //Jika rekod wujud, pengguna sudah mengundi.
            //Jika rekod tiada, pengguna belum mengundi.
            $query_semak_undi = "SELECT * FROM rekod_undi WHERE noKP='$username'";
            $hasil_semak = mysqli_query($con, $query_semak_undi);

            //Halakan pengguna yang sudah mengundi ke halaman selesai.
            if (mysqli_num_rows($hasil_semak) > 0) {
                echo "<script>alert('Anda telah pun membuang undi. Anda akan dibawa ke halaman pilihan.');</script>";
                echo "<script>window.location.replace('selesaiundi.php');</script>";
                exit();
            }
            //Halakan pengguna yang belum mengundi ke halaman mengundi.
            else {
                echo "<script>alert('Anda telah berjaya log masuk. Sila buang undi anda.');</script>";
                echo "<script>window.location.replace('pengundipage.php');</script>";
                exit();
            }

        }
        //Papar mesej jika ID pengguna atau kata laluan salah.
        else {
            // Jika username atau kata laluan tidak sepadan
            echo "<script>alert('Harap Maaf. Username atau Password salah.');</script>";
            echo "<script>window.location.replace('daftar.php');</script>";
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pengundian Kelab Rekreasi</title>
    <?php //Pautan ke fail CSS luaran. ?>
    <link rel="stylesheet" href="stylesheet.css">
</head>

<body>

    <?php //Tajuk utama. ?>
    <header>
        <h1>SISTEM PENGUNDIAN KELAB REKREASI</h1>
    </header>

    <?php //Masukkan suis tema cerah dan gelap. ?>
    <?php include('color_switch.php'); ?>

    <?php //Kandungan utama borang log masuk. ?>
    <main>
        <div class="kotak-login">
            <?php //Borang log masuk menggunakan kaedah POST. ?>
            <?php //action kosong bermaksud data dihantar ke halaman yang sama. ?>
            <form method="POST" action="">
                <center>
                    <h2>Log Masuk</h2>

                    <?php //Jadual untuk menyusun input borang. ?>
                    <table cellpadding="5" cellspacing="0" width="100%">
                        <tr>
                            <?php //Label untuk medan ID pengguna. ?>
                            <td><b>ID PENGGUNA (NO SEKOLAH)</b></td>
                        </tr>
                        <tr>
                            <?php //Medan input untuk ID pengguna. ?>
                            <?php //required memastikan medan mesti diisi. ?>
                            <td><input type="text" name="idPengguna" required></td>
                        </tr>
                        <tr>
                            <?php //Label untuk medan kata laluan. ?>
                            <td><br><b>Kata Laluan:</b></td>
                        </tr>
                        <tr>
                            <?php //Medan input kata laluan menyembunyikan aksara. ?>
                            <td><input type="password" name="kataLaluan" required></td>
                        </tr>
                        <tr>
                            <td>
                                <br><br>
                                <?php //Butang untuk menghantar borang log masuk. ?>
                                <div class="button-container">
                                    <button type="submit" name="hantar" class="fluid-glass">LOG MASUK</button>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <br><br>
                    <?php //Pautan untuk pengguna baharu mendaftar. ?>
                    <p>Tiada akaun? <a href="daftar.php">Daftar di sini</a></p>
                </center>
            </form>
        </div>
    </main>

    <?php //Bahagian pengaki. ?>
    <footer>
        <p>HAK CIPTA@TAN HONG LIN</p>
    </footer>

</body>
</html>
