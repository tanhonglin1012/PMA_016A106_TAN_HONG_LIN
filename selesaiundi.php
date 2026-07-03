<?php
session_start();
require('config.php');

if (!isset($_SESSION['noKP'])) {
    header("Location: MenuLogin.php");
    exit();
}

$noKP = mysqli_real_escape_string($con, $_SESSION['noKP']);
$nama = '';
$result = mysqli_query($con, "SELECT nama FROM pengundi WHERE noKP='$noKP'");

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $nama = $row['nama'];
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Selesai Undi - Sistem Pengundian</title>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <header>
        <center><h1>SISTEM PENGUNDIAN KELAB REKREASI</h1></center>
    </header>

    <?php include('color_switch.php'); ?>
    <?php include('navigationbar.php'); ?>

    <main>
        <div class="kotak-login">
            <center>
                <h2>Undian Telah Selesai</h2>
                <p>
                    <?php if ($nama != ''): ?>
                        Terima kasih, <?= htmlspecialchars($nama) ?>. Undian anda telah direkodkan.
                    <?php else: ?>
                        Terima kasih. Undian anda telah direkodkan.
                    <?php endif; ?>
                </p>
            </center>
        </div>
    </main>

    <footer>
        <center><p>HAK CIPTA @ TAN HONG LIN</p></center>
    </footer>
</body>
</html>