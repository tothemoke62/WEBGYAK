<?php
session_start();
include("./includes/config.inc.php");

$keres = null;
$url = isset($_GET['page']) ? $_GET['page'] : '/';

if(array_key_exists($url, $oldalak)) {
    $keres = $oldalak[$url];
} else {
    $keres = $hiba_oldal;
}

include("./templates/index.tpl.php");
?>