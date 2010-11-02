<?php

include "functions.inc.php";

header('Content-Type: text/plain; charset=utf-8');

if (isset($_GET['country']) && strlen(trim($_GET['country'])) === 2)
    $country = mysql_real_escape_string(trim(strtoupper($_GET['country'])));
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
