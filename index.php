<?php

include "functions.inc.php";

if (isset($_GET['country']) && strlen(trim($_GET['country'])) === 2)
    $country = mysql_real_escape_string(trim($_GET['country']));
else
    die("Invalid country");

$rating = getRating($country);

echo json_encode($rating);


?>
