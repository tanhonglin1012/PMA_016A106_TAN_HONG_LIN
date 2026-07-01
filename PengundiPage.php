<?php
//Memulakan sesi PHP.
session_start();

//Memanggil fail sambungan pangkalan data.
require('config.php');

//Pastikan pengguna telah log masuk sebelum mengakses halaman ini.
//Jika sesi noKP tiada, pengguna akan dihalakan ke halaman log masuk.
if (!isset($_SESSION['noKP'])) {
    header("Location: MenuLogin.php");
    exit();
}

//Dapatkan noKP pengguna yang sedang log masuk daripada sesi.
$noKP = $_SESSION['noKP'];

//Semak status undian supaya pengguna tidak mengundi lebih daripada sekali.
$semak = mysqli_query($con, "SELECT * FROM rekod_undi WHERE noKP='$noKP'");
$sudah_undi = mysqli_num_rows($semak) > 0;

//Halakan pengguna ke halaman selesai jika sudah mengundi.
if ($sudah_undi) {
    echo "<script>alert('Anda telah pun membuang undi. Anda akan dibawa ke halaman pilihan.')</script>";
    echo "<script>window.location.replace('selesaiundi.php')</script>";
    exit();
}

//Proses menyimpan undian dijalankan apabila butang hantar ditekan.
if (isset($_POST['hantar']) && !$sudah_undi) {

    //Ambil nilai calon yang dipilih untuk setiap jawatan.
    //K01 mewakili jawatan Pengerusi.
    //K02 mewakili jawatan Naib Pengerusi.
    //K03 mewakili jawatan Setiausaha.
    $C01 = mysqli_real_escape_string($con, $_POST['K01']);
    $C02 = mysqli_real_escape_string($con, $_POST['K02']);
    $C03 = mysqli_real_escape_string($con, $_POST['K03']);

    //Tetapkan zon masa kepada waktu Malaysia.
    date_default_timezone_set('Asia/Kuala_Lumpur');

    //Dapatkan masa semasa dalam format jam, minit dan saat.
    $tarikh = date("H:i:s");

    //Jana ID undian seterusnya secara manual.
    //Cari nilai idUndi paling tinggi dan tambah 1.
    $cariMaxId = mysqli_query($con, "SELECT idUndi FROM rekod_undi ORDER BY CAST(idUndi AS UNSIGNED) DESC LIMIT 1");
    $maxIdRow = mysqli_fetch_array($cariMaxId);

    //Tetapkan nilai asal jika tiada rekod dalam jadual.
    $nextId = 1;

    //Tambah 1 jika rekod terakhir wujud dan idUndi tidak kosong.
    if ($maxIdRow && $maxIdRow['idUndi'] != "") {
        $nextId = (int)$maxIdRow['idUndi'] + 1;
    }

    //Jana tiga ID undian untuk tiga jawatan.
    $id1 = $nextId;
    $id2 = $nextId + 1;
    $id3 = $nextId + 2;

    //Simpan rekod undian ke jadual rekod_undi.
    //Setiap pengundi mempunyai satu rekod bagi setiap jawatan.
    $query1 = mysqli_query($con, "INSERT INTO rekod_undi (idUndi, Masa, noKP, idCalon, idKategori) VALUES('$id1', '$tarikh', '$noKP', '$C01', 'K01')");
    $query2 = mysqli_query($con, "INSERT INTO rekod_undi (idUndi, Masa, noKP, idCalon, idKategori) VALUES('$id2', '$tarikh', '$noKP', '$C02', 'K02')");
    $query3 = mysqli_query($con, "INSERT INTO rekod_undi (idUndi, Masa, noKP, idCalon, idKategori) VALUES('$id3', '$tarikh', '$noKP', '$C03', 'K03')");

    //Semak sama ada semua arahan INSERT berjaya.
    if ($query1 && $query2 && $query3) {
        //Papar mesej jika undian berjaya disimpan.
        echo "<script>alert('Undian anda telah berjaya dihantar! Sila buat pilihan seterusnya.'); window.location.replace('selesaiundi.php');</script>";
        exit();
    } else {
        //Papar mesej ralat jika proses pangkalan data gagal.
        $error = mysqli_error($con);
        echo "<script>alert('Ralat Pangkalan Data: " . $error . "');</script>";
    }
}
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
    <div class="text-center">
        <?php //Tajuk utama. ?>
        <header>
            <h1>SISTEM PENGUNDIAN KELAB REKREASI</h1>
        </header>

        <?php //Masukkan suis tema cerah dan gelap. ?>
        <?php include('color_switch.php'); ?>

        <?php //Kandungan utama borang mengundi. ?>
        <main>
            <?php //Borang undian menggunakan kaedah POST. ?>
            <form method="POST" action="" class="form-undi">

                <div class="kategori-undi" id="kategori-1">
                    <h2>JAWATAN: PENGERUSI</h2>

                    <table>
                        <tr>
                            <td>
                                <label>
                                    <img src="img/Donald.webp" alt="Donald">
                                    <h3>Donald</h3>
                                    <?php //Butang radio untuk memilih calon. ?>
                                    <?php //K01 ialah kategori Pengerusi dan C01 ialah ID calon Donald. ?>
                                    <input type="radio" name="K01" value="C01" required>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <img src="img/Kevin.jpg" alt="Kevin">
                                    <h3>Kevin</h3>
                                    <input type="radio" name="K01" value="C02" required>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <img src="img/Cindy.webp" alt="Cindy">
                                    <h3>Cindy</h3>
                                    <input type="radio" name="K01" value="C03" required>
                                </label>
                            </td>
                        </tr>
                    </table>

                    <?php //Butang untuk ke kategori seterusnya. ?>
                    <div class="step-actions">
                        <button type="button" class="button-hantar btn-step" onclick="paparSeterusnya(1)">SETERUSNYA</button>
                    </div>
                </div>

                <div class="kategori-undi is-hidden" id="kategori-2">
                    <h2>JAWATAN: NAIB PENGERUSI</h2>

                    <table>
                        <tr>
                            <td>
                                <label>
                                    <img src="img/Donald.webp" alt="Donald">
                                    <h3>Donald</h3>
                                    <?php //K02 ialah kategori Naib Pengerusi. ?>
                                    <?php //semakPilihan() menyemak calon yang sudah dipilih. ?>
                                    <input type="radio" name="K02" value="C01" onclick="semakPilihan(2, this)" required>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <img src="img/Kevin.jpg" alt="Kevin">
                                    <h3>Kevin</h3>
                                    <input type="radio" name="K02" value="C02" onclick="semakPilihan(2, this)" required>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <img src="img/Cindy.webp" alt="Cindy">
                                    <h3>Cindy</h3>
                                    <input type="radio" name="K02" value="C03" onclick="semakPilihan(2, this)" required>
                                </label>
                            </td>
                        </tr>
                    </table>

                    <?php //Butang kembali dan seterusnya. ?>
                    <div class="step-actions">
                        <button type="button" class="button-hantar btn-step" onclick="kembali(1)">KEMBALI</button>
                        <button type="button" class="button-hantar btn-step" onclick="paparSeterusnya(2)">SETERUSNYA</button>
                    </div>
                </div>

                <div class="kategori-undi is-hidden" id="kategori-3">
                    <h2>JAWATAN: SETIAUSAHA</h2>

                    <table>
                        <tr>
                            <td>
                                <label>
                                    <img src="img/Donald.webp" alt="Donald">
                                    <h3>Donald</h3>
                                    <?php //K03 ialah kategori Setiausaha. ?>
                                    <input type="radio" name="K03" value="C01" onclick="semakPilihan(3, this)" required>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <img src="img/Kevin.jpg" alt="Kevin">
                                    <h3>Kevin</h3>
                                    <input type="radio" name="K03" value="C02" onclick="semakPilihan(3, this)" required>
                                </label>
                            </td>
                            <td>
                                <label>
                                    <img src="img/Cindy.webp" alt="Cindy">
                                    <h3>Cindy</h3>
                                    <input type="radio" name="K03" value="C03" onclick="semakPilihan(3, this)" required>
                                </label>
                            </td>
                        </tr>
                    </table>


                    <div class="step-actions">
                        <button type="button" class="button-hantar btn-step" onclick="kembali(2)">KEMBALI</button>
                        <?php //Butang hantar undian kepada PHP untuk disimpan. ?>
                        <button type="submit" name="hantar" class="button-hantar btn-step btn-submit-vote">HANTAR UNDIAN</button>
                    </div>
                </div>

            </form>
        </main>

        <footer>
            <p>HAK CIPTA @ TAN HONG LIN</p>
        </footer>
    </div>

    <script>
        //Fungsi paparSeterusnya() memaparkan kategori undian seterusnya.
        //Parameter langkah mewakili nombor kategori semasa.
        function paparSeterusnya(langkah) {
            //Semak sama ada pengundi telah memilih calon untuk kategori semasa.
            if (!document.querySelector('input[name="K0' + langkah + '"]:checked')) {
                alert("Sila pilih calon untuk jawatan ini terlebih dahulu.");
                return;
            }
            //Sembunyikan kategori semasa.
            document.getElementById('kategori-' + langkah).classList.add('is-hidden');
            //Paparkan kategori seterusnya.
            document.getElementById('kategori-' + (langkah + 1)).classList.remove('is-hidden');
        }

        //Fungsi kembali() memaparkan kategori undian sebelumnya.
        //Parameter langkah mewakili nombor kategori sebelumnya.
        function kembali(langkah) {
            //Sembunyikan kategori semasa.
            document.getElementById('kategori-' + (langkah + 1)).classList.add('is-hidden');
            //Paparkan kategori sebelumnya.
            document.getElementById('kategori-' + langkah).classList.remove('is-hidden');
        }

        //Fungsi semakPilihan() memastikan calon tidak dipilih untuk jawatan lain.
        //Setiap calon hanya boleh dipilih untuk satu jawatan sahaja.
        function semakPilihan(langkah, radio) {
            //Semak semua kategori sebelumnya.
            for (let i = 1; i < langkah; i++) {
                //Cari butang radio yang dipilih dalam kategori sebelumnya.
                let s = document.querySelector('input[name="K0' + i + '"]:checked');
                //Batalkan pilihan semasa jika calon yang sama sudah dipilih.
                if (s && s.value === radio.value) {
                    alert("Maaf, calon telah dipilih untuk jawatan sebelumnya. Sila pilih yang lain.");
                    radio.checked = false;
                    return;
                }
            }
        }
    </script>
</body>
</html>
