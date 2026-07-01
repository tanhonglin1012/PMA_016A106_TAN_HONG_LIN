<?php
//Memanggil fail sambungan pangkalan data.
require('config.php');
  
//Proses pendaftaran dijalankan apabila butang daftar ditekan.
if(isset($_POST['daftar'])){
  
    //Ambil dan bersihkan data daripada borang pendaftaran.
    $namaPenuh = mysqli_real_escape_string($con, $_POST['namaPenuh']);
    $idPengguna = mysqli_real_escape_string($con, trim($_POST['idPengguna']));
    $kataLaluan = mysqli_real_escape_string($con, $_POST['kataLaluan']);

    //Semak supaya ID pengguna tidak kosong, nombor sahaja, dan tepat 12 digit.
    if($idPengguna === '') {
        echo "<script>alert('ID Pengguna tidak boleh kosong!')</script>";
        echo "<script>window.location.href = 'daftar.php';</script>";
        exit;
    }

    if(!ctype_digit($idPengguna)) {
        echo "<script>alert('ID Pengguna mesti nombor sahaja!')</script>";
        echo "<script>window.location.href = 'daftar.php';</script>";
        exit;
    }

    if(strlen($idPengguna) < 12) {
        echo "<script>alert('ID Pengguna tidak boleh kurang daripada 12 digit!')</script>";
        echo "<script>window.location.href = 'daftar.php';</script>";
        exit;
    }

    if(strlen($idPengguna) > 12) {
        echo "<script>alert('ID Pengguna tidak boleh melebihi 12 digit!')</script>";
        echo "<script>window.location.href = 'daftar.php';</script>";
        exit;
    }
  
    //Simpan akaun log masuk baharu ke dalam jadual pengguna.
    $sql_pengguna = "INSERT INTO pengguna (namaPengguna, katalaluan) VALUES ('$idPengguna', '$kataLaluan')";
  
    //Simpan maklumat pengundi baharu ke dalam jadual pengundi.
    $sql_pengundi = "INSERT INTO pengundi (noKP, nama) VALUES ('$idPengguna', '$namaPenuh')";
  
    //Laksanakan arahan SQL menggunakan sambungan daripada config.php.
    $hasil1 = mysqli_query($con, $sql_pengguna);
    $hasil2 = mysqli_query($con, $sql_pengundi);
  
    //Semak sama ada pendaftaran berjaya atau gagal.
    if($hasil1 && $hasil2){
        echo "<script>alert('Pendaftaran Berjaya! Sila log masuk dengan akaun baharu anda.')</script>";
        echo "<script>window.location.href = 'MenuLogin.php';</script>";
    } else {
        echo "<script>alert('Pendaftaran Gagal. ID Pengguna mungkin sudah wujud. Sila cuba lagi.')</script>";
        echo "<script>window.location.href = 'daftar.php';</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Sistem Pengundian Kelab Rekreasi</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
    <header>
        <center><h1>SISTEM PENGUNDIAN KELAB REKREASI</h1></center>
    </header>
    
    <?php include('color_switch.php'); ?>

    <main>
        <div class="kotak-login">
            <form method="POST" action="" onsubmit="return validateForm()">
                <center>
                    <h2>Daftar Akaun Baru</h2>
                    
                    <table cellpadding="5" cellspacing="0" width="100%">
                        <tr>
                            <td><b><label>Nama Penuh:</label></b></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="namaPenuh" required></td>
                        </tr>
                        <tr>
                            <td><br><b><label>ID PENGGUNA:</label></b></td>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" name="idPengguna" id="idPengguna">
                                <br><small id="idError" style="color:red; display:none;">ID mesti tepat 12 digit angka!</small>
                            </td>
                        </tr>
                        <tr>
                            <td><br><b><label>Kata Laluan:</label></b></td>
                        </tr>
                        <tr>
                            <td><input type="password" name="kataLaluan" required></td>
                        </tr>
                        <tr>
                            <td>
                                <br><br>
                                <div class="button-container">
                                    <button type="submit" name="daftar" class="button-hantar">DAFTAR</button>
                                </div>
                            </td>
                        </tr>
                    </table>

                    <br><br>
                    <p>Sudah mempunyai akaun? <a href="MenuLogin.php">Log Masuk di sini</a></p>
                </center>
            </form>
        </div>
    </main>

    <footer>
        <p>HAK CIPTA@TAN HONG LIN</p>
    </footer>

    <script>
    const idInput = document.getElementById('idPengguna');
    const idError = document.getElementById('idError');

    function validateForm(){
        const id = idInput.value.trim();

        if(id === ''){
            idError.textContent = 'ID Pengguna tidak boleh kosong!';
            idError.style.display = 'inline';
            alert('ID Pengguna tidak boleh kosong!');
            return false;
        }

        if(!/^[0-9]+$/.test(id)){
            idError.textContent = 'ID Pengguna mesti nombor sahaja!';
            idError.style.display = 'inline';
            alert('ID Pengguna mesti nombor sahaja!');
            return false;
        }

        if(id.length < 12){
            idError.textContent = 'ID Pengguna tidak boleh kurang daripada 12 digit!';
            idError.style.display = 'inline';
            alert('ID Pengguna tidak boleh kurang daripada 12 digit!');
            return false;
        }

        if(id.length > 12){
            idError.textContent = 'ID mesti tepat 12 digit angka!';
            idError.style.display = 'inline';
            alert('ID Pengguna tidak boleh melebihi 12 digit!');
            return false;
        }

        return true;
    }
</script>
