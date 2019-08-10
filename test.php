<?php
include 'koneksi.php';


$hariini            = date("Y-m-d");
$proseskemarin        = mktime(0,0,0,date("n"),date("j")-1,date("Y"));
$kemarin        = date("Y-m-d", $proseskemarin);

$SSY = mysqli_query($konek,"SELECT sisastok, total FROM stockmaj ORDER BY date='$kemarin' DESC ");

$totalJual = mysqli_fetch_array(mysqli_query($konek,"SELECT SUM(total) AS TJual FROM stockmaj WHERE date='$hariini' AND trx_jual = 1 "));

$totalBeli = mysqli_fetch_array(mysqli_query($konek,"SELECT SUM(total) AS TBeli, SUM(jumlah) AS JBeli FROM stockmaj WHERE date='$hariini' AND trx_beli = 1 "));

$SSD = mysqli_fetch_array(mysqli_query($konek,"SELECT sisastok FROM stockmaj ORDER BY date DESC LIMIT 1"));

$dataSSY = mysqli_fetch_array($SSY);

echo "Total Kemarin ".number_format($dataSSY['total']);
echo "<br />";
echo "Total Stock USD Kemarin ".number_format($dataSSY['sisastok']);
echo "<br />";
echo "Total Beli ".number_format($totalBeli['TBeli']);
echo "<br />";
echo "Total Jual ".number_format($totalJual['TJual']);
echo "<br />";
echo "Jumlah Beli USD ".number_format($totalBeli['JBeli']);
echo "<br />";

$x = $dataSSY['total'] + $totalBeli['TBeli'];
$y = $dataSSY['sisastok'] + $totalBeli['JBeli'];
$newRate = $x / $y;
echo "New Rate ".number_format(round($newRate));
echo "<br />";

echo "Sisa Stock Hari Ini ".number_format($SSD['sisastok']);
echo "<br />";

$hitungLaba = ($totalJual['TJual']+(int)($SSD['sisastok'] * $newRate)) - ($x);
if ($hitungLaba <= 0 ) {
 echo "0";
} else {
 echo "Laba ".number_format($hitungLaba); 
}
echo "<br />";

?>

<!DOCTYPE html>
<html>
<head>
 <title>Laba Rugi</title>
</head>
<body>

</body>
</html>
<?php
$koneksi = "localhost";
$user = "root";
$pass = "";
$database = "ujicoba";

$konek = mysqli_connect("$koneksi","$user","$pass","$database");

if (!$konek) {
 echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
} else {
}

?>