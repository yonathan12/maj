<?php
$hitungLaba = -5;
if ($hitungLaba <= 0 ) {
    echo "Tidak Untung";
   } else {
    echo "Laba ".number_format($hitungLaba); 
   }
var_dump($hitungLaba);
die;
?>