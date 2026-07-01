<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengundian Kelab Rekreasi</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>
<body>

    <?php //Tajuk utama. ?>
    <header>
        <center><h1>SISTEM PENGUNDIAN KELAB REKREASI</h1></center>
    </header>
    
    <?php //Masukkan suis tema cerah dan gelap. ?>
    <?php include('color_switch.php'); ?>

    <?php //Kandungan utama halaman selamat datang. ?>
    <main>
        <div class="pusat-kandungan">
            <h1 class="welcome-title">Selamat Datang</h1>
            
            <div class="logo-wrapper">
                <img src="img/Untitled.jpeg" class="logo-besar" alt="Logo Kelab Rekreasi">
                
                <?php //Pautan untuk masuk ke halaman log masuk. ?>
                <a href="MenuLogin.php" class="button-hantar welcome-login-link">
                    LOG MASUK &#10148;
                </a>
            </div>
        </div>
    </main>

    <?php //Bahagian pengaki. ?>
    <footer class="welcome-footer">
        <center><p>HAK CIPTA @ TAN HONG LIN</p></center>
    </footer>

</body>
</html>
