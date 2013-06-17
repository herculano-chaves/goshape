<?php 
require('../../../../../wp-blog-header.php');

$mes = $_POST['mes'];
$area = $_POST['area'];

if($mes<10) $mes = '0'.$mes;
get_custom_calendar(false, true, $mes, $area);
?>