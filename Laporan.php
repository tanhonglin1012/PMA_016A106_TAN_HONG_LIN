<?php
//Memulakan sesi PHP.
session_start();

//Memanggil fail sambungan pangkalan data.
require("config.php");

//Pastikan hanya admin boleh mengakses halaman ini.
if (!isset($_SESSION['noKP']) || $_SESSION['noKP'] !== 'admin') {
    header("Location: MenuLogin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengundian Kelab Rekreasi</title>
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        .report-summary { display: none; }

        @media print {
            .no-print { display: none; }
            .report-summary { display: block; }
            .report-detail { page-break-before: always; }
        }
    </style>
</head>
<body>

    <?php //Tajuk utama yang tidak dicetak. ?>
    <header class="no-print text-center">
        <h1>SISTEM PENGUNDIAN KELAB REKREASI</h1>
    </header>

    <?php //Masukkan suis tema cerah dan gelap. ?>
    <?php include('color_switch.php'); ?>

    <?php //Masukkan bar navigasi admin. ?>
    <?php include('adminnavigationbar.php'); ?>

    <div class="container">

        <?php //Butang navigasi dan cetak yang tidak dicetak. ?>
        <div class="action-bar no-print">
            <?php //Butang kembali ke halaman admin. ?>
            <a href="admin.php" class="btn-kembali">Kembali ke Admin</a>
            <?php //Butang untuk mencetak laporan menggunakan window.print(). ?>
            <button onclick="window.print()" class="btn-cetak">Cetak Laporan</button>
        </div>

        <?php //Bahagian ringkasan keputusan yang dipaparkan semasa mencetak. ?>
        <section class="report-section report-summary">
            <h2>JUMLAH UNDIAN KESELURUHAN</h2>
            <?php //Masukkan fail jadual keputusan. ?>
            <?php include('_keputusan_table.php'); ?>
        </section>

        <?php //Bahagian laporan penuh rekod undian. ?>
        <section class="report-section report-detail">
            <h2>LAPORAN PENUH REKOD UNDIAN</h2>

            <?php //Jadual laporan penuh. ?>
            <table>
                <thead>
                    <tr>
                        <th>Bil.</th>
                        <th>Masa Undi</th>
                        <th>Nama Pengundi</th>
                        <th>Jawatan</th>
                        <th>Nama Calon</th>
                    </tr>
                </thead>
                <tbody>
                <?php
            
                $sqlLaporan = "SELECT
                                rekod_undi.idUndi,
                                rekod_undi.Masa,
                                pengundi.nama AS Nama_Pengundi,
                                calon.namaCalon AS Nama_Calon,
                                kategori.namaKategori AS Kategori
                              FROM rekod_undi
                              INNER JOIN pengundi ON rekod_undi.noKP = pengundi.noKP
                              INNER JOIN calon ON rekod_undi.idCalon = calon.idCalon
                              INNER JOIN kategori ON rekod_undi.idKategori = kategori.idKategori
                              ORDER BY rekod_undi.Masa DESC";

                //Laksanakan arahan SQL.
                $result = mysqli_query($con, $sqlLaporan);

                //Tetapkan nombor siri bermula daripada 1.
                $no = 1;

                //Semak sama ada hasil carian wujud.
                if ($result && mysqli_num_rows($result) > 0) {
                    //Paparkan setiap baris rekod undian.
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $no . "</td>";
                        //Gunakan tanda sempang jika nilai pangkalan data kosong.
                        echo "<td>" . ($row['Masa'] ?? '-') . "</td>";
                        echo "<td>" . ($row['Nama_Pengundi'] ?? '-') . "</td>";
                        echo "<td>" . ($row['Kategori'] ?? '-') . "</td>";
                        echo "<td>" . ($row['Nama_Calon'] ?? '-') . "</td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    //Papar mesej jika tiada data undian dijumpai.
                    echo "<tr><td colspan='5'>Tiada maklumat undian dijumpai.</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </section>
    </div>

    <?php //Bahagian pengaki yang tidak dicetak. ?>
    <footer class="no-print">
        <p>HAK CIPTA @ TAN HONG LIN</p>
    </footer>

</body>
</html>
