<?php
//Sambungan MySQLi digunakan untuk menghubungkan sistem dengan phpMyAdmin dalam XAMPP.

    $con = mysqli_connect("localhost", "root", "");

    //Semak sambungan pangkalan data sebelum sistem diteruskan.
    if(!$con){
        die('Sambungan kepada Pangkalan Data Gagal'.mysqli_connect_error());

        //die() menghentikan proses jika sambungan pangkalan data gagal.
    }

    //Pilih pangkalan data yang digunakan oleh sistem.
    mysqli_select_db($con,"thl091012070425");
?>
