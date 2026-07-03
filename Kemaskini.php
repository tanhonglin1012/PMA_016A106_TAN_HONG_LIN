<?php
session_start();
// Memanggil fail konfigurasi pangkalan data
require('config.php');

//Pastikan hanya admin boleh mengakses halaman ini.
if (!isset($_SESSION['noKP']) || $_SESSION['noKP'] !== 'admin') {
    header("Location: MenuLogin.php");
    exit();
}

//Padam rekod undian, data pengundi dan akaun pengguna berdasarkan noKP.
if (isset($_GET['delete_noKP'])) {
    $noKP = mysqli_real_escape_string($con, $_GET['delete_noKP']);
    mysqli_query($con, "DELETE FROM rekod_undi WHERE noKP='$noKP'");
    mysqli_query($con, "DELETE FROM pengundi WHERE noKP='$noKP'");
    //Padam akaun log masuk pengguna yang sama.
    mysqli_query($con, "DELETE FROM pengguna WHERE namaPengguna='$noKP'");
    header("Location: Kemaskini.php");
    exit();
}

//Kemas kini nama pengundi berdasarkan noKP.
if (isset($_POST['update_pengundi'])) {
    $noKP = mysqli_real_escape_string($con, $_POST['noKP']);
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    mysqli_query($con, "UPDATE pengundi SET nama='$nama' WHERE noKP='$noKP'");
    header("Location: Kemaskini.php");
    exit();
}

//Kemas kini nama calon berdasarkan idCalon.
if (isset($_POST['update_calon'])) {
    $idCalon = mysqli_real_escape_string($con, $_POST['idCalon']);
    $namacalon = mysqli_real_escape_string($con, $_POST['namacalon']);
    mysqli_query($con, "UPDATE calon SET namacalon='$namacalon' WHERE idCalon='$idCalon'");
    header("Location: Kemaskini.php");
    exit();
}

//Ambil data pengundi bersama rekod undian, calon dan kategori.
$sql = "SELECT p.noKP, p.nama,
               c.namacalon, k.namaKategori, r.Masa
        FROM pengundi p
        LEFT JOIN rekod_undi r  ON p.noKP       = r.noKP
        LEFT JOIN calon c       ON r.idCalon     = c.idCalon
        LEFT JOIN kategori k    ON r.idKategori  = k.idKategori
        WHERE p.noKP != 'admin'
        ORDER BY p.nama ASC";
$result = mysqli_query($con, $sql);

//Ambil senarai calon untuk paparan kemas kini nama calon.
$calonResult = mysqli_query($con, "SELECT * FROM calon ORDER BY idCalon ASC");
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Kemas Kini - Sistem Pengundian</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <header class="text-center">
        <h1>SISTEM PENGUNDIAN KELAB REKREASI</h1>
    </header>

    <?php include('color_switch.php'); ?>
    <?php include('adminnavigationbar.php'); ?>

    <main>
        <div class="admin-dashboard">
            <?php //Butang kembali ke halaman admin. ?>
            <div class="action-bar">
                <a href="admin.php" class="btn-kembali">Kembali ke Admin</a>
            </div>

            <?php //Bahagian senarai pengundi untuk kemas kini dan padam. ?>
            <h2>Panel Kemas Kini & Padam Pengundi</h2>
            <table>
                <tr>
                    <th>No KP</th>
                    <th>Nama Pengundi</th>
                    <th>Calon Dipilih</th>
                    <th>Kategori</th>
                    <th>Masa Mengundi</th>
                    <th>Kemas Kini Nama</th>
                    <th>Padam</th>
                </tr>
                <?php
                //Kumpul baris mengikut noKP supaya nama pengundi dipaparkan sekali.
                $rows = [];
                while ($row = mysqli_fetch_assoc($result)) {
                    $rows[$row['noKP']][] = $row;
                }
                foreach ($rows as $noKP => $data):
                    $rowspan = count($data);
                    $first = true;
                    foreach ($data as $r):
                        echo "<tr>";
                        if ($first):
                ?>
                        <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($r['noKP']) ?></td>
                        <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($r['nama']) ?></td>
                <?php   endif; ?>
                        <td><?= isset($r['namacalon']) ? $r['namacalon'] : '<span class="text-danger">-</span>' ?></td>
                        <td><?= isset($r['namaKategori']) ? $r['namaKategori'] : '-' ?></td>
                        <td><?= isset($r['Masa']) ? $r['Masa'] : '-' ?></td>
                <?php   if ($first): ?>
                        <?php //Borang kemas kini nama pengundi. ?>
                        <td rowspan="<?= $rowspan ?>">
                            <form method="POST" class="inline-edit-form">
                                <input type="hidden" name="noKP" value="<?= htmlspecialchars($noKP) ?>">
                                <input type="text" name="nama" value="<?= htmlspecialchars($r['nama']) ?>" class="input-kemaskini" required>
                                <button type="submit" name="update_pengundi" class="btn-simpan">Simpan</button>
                            </form>
                        </td>
                        <?php //Butang untuk memadam pengundi. ?>
                        <td rowspan="<?= $rowspan ?>">
                            <a href="Kemaskini.php?delete_noKP=<?= urlencode($noKP) ?>"
                               onclick="return confirm('Padam pengundi ini? Semua rekod undiannya turut dipadam.');"
                               class="btn-logkeluar">Padam</a>
                        </td>
                <?php   endif;$first = false;
                        echo "</tr>";
                    endforeach;
                endforeach; ?>
            </table>

            <?php //Bahagian kemas kini nama calon. ?>
            <h2>Panel Kemas Kini Nama Calon</h2>
            <table>
                <tr>
                    <th>ID Calon</th>
                    <th>Nama Calon</th>
                    <th>Tindakan</th>
                </tr>
                <?php while ($c = mysqli_fetch_assoc($calonResult)): ?>
                <tr>
                    <td><?= htmlspecialchars($c['idCalon']) ?></td>
                    <td><?= htmlspecialchars($c['namacalon']) ?></td>
                    <td>
                        <form method="POST" class="inline-edit-form">
                            <input type="hidden" name="idCalon" value="<?= htmlspecialchars($c['idCalon']) ?>">
                            <input type="text" name="namacalon" value="<?= htmlspecialchars($c['namacalon']) ?>" class="input-kemaskini" required>
                            <button type="submit" name="update_calon" class="btn-simpan">Simpan</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

        </div>
    </main>

    <footer>
        <p>HAK CIPTA @ TAN HONG LIN</p>
    </footer>
</body>
</html>
