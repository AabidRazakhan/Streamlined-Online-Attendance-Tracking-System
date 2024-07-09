<?php
//require_once './vendor/autoload.php';

use Dompdf\Dompdf;

$dompdf=new Dompdf();
$html = file_get_contents("index.php");
$dompdf->loadHtml($html);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->stream();

?>
