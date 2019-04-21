<?php

$host = "localhost";
$user = "root";
$pass = "";
$db = "login";

$link = mysqli_connect($host,$user,$pass,$db)or die(mysqli_error());

$queryStock = mysqli_query($link,"SELECT transaksi.*, valas.*
FROM transaksi JOIN valas
ON transaksi.id_valas = valas.Id
WHERE transaksi.trx = 2");
$data = mysqli_fetch_array($queryStock);

var_dump($data);
?>