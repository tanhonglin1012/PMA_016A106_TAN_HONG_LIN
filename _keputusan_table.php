<?php
//Paparkan jadual keputusan undian mengikut kategori.
//Fail ini memerlukan pemboleh ubah $con daripada config.php.
$queryKategori = mysqli_query($con, "SELECT * FROM kategori");
while ($rowKategori = mysqli_fetch_array($queryKategori)) {
    $idKategori = $rowKategori['idKategori'];
    
    echo "<div class='kategori-box'>";
    echo "<h2>Keputusan Bagi: " . $rowKategori['namaKategori'] . "</h2>";
    echo "<table class='table-keputusan'>
          <tr>
            <th>ID Calon</th>
            <th>Nama Calon</th>
            <th>Jumlah Undian</th>
          </tr>";

    $sql = "SELECT c.idCalon, c.namaCalon, COUNT(r.idUndi) AS jumlahUndi
            FROM calon c
            LEFT JOIN rekod_undi r ON c.idCalon = r.idCalon AND r.idKategori = '$idKategori'
            GROUP BY c.idCalon, c.namaCalon
            ORDER BY jumlahUndi DESC";
    
    $resultQuery = mysqli_query($con, $sql);
    
    if (mysqli_num_rows($resultQuery) > 0) {
        while ($row = mysqli_fetch_array($resultQuery)) {
            echo "<tr>";
            echo "<td>" . $row['idCalon'] . "</td>";
            echo "<td>" . $row['namaCalon'] . "</td>";
            echo "<td><strong>" . $row['jumlahUndi'] . "</strong></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='3'>Tiada data untuk kategori ini.</td></tr>";
    }
    
    echo "</table>";
    echo "</div>";
}
?>
