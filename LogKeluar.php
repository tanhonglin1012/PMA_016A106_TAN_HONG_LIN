<?php
//Memulakan sesi PHP sebelum sesi dimusnahkan.
session_start();

//Membuang semua pemboleh ubah sesi.
session_unset();

//Memusnahkan sesi pengguna sepenuhnya.
session_destroy();

//Papar mesej log keluar berjaya.
echo "<script>alert('Anda telah berjaya log keluar.');</script>";

//Halakan pengguna ke halaman log masuk.
echo "<script>window.location.replace('MenuLogin.php');</script>";
exit();
?>
