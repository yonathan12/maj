<div class="container-fluid">

<?php
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_URL, 'http://www.adisurya.net/kurs-bca/get');
$kurs_bca = curl_exec($ch);
curl_close($ch);
$api_kurs = explode('{', $kurs_bca);
$kurs = explode(',', $api_kurs[4]);
echo $api_kurs;
?>
</div>