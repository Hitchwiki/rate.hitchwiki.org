<?php

include "functions.inc.php";

header('Content-Type: text/plain; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (isset($_GET['country']) && strlen(trim($_GET['country'])) === 2)
    $country = trim(strtoupper($_GET['country']));
else
    die("Invalid country");

if (isset($_GET['lang']) && in_array($_GET['lang'], array_keys($languages)))
    $lang = $languages[$_GET['lang']];
else
    $lang = 'en_UK';

$rating = getRating($country);
$rating['countryName']= getCountryName($country, $lang);

echo json_encode($rating);


?>
