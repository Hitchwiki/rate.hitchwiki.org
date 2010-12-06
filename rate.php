<?php

include "functions.inc.php";


if (isset($_GET['country']) && strlen(trim($_GET['country'])) === 2)
    $country = mysql_real_escape_string(trim(strtoupper($_GET['country'])));
else
    die(json_encode(array('error' => "Invalid country")));

if (isset($_GET['rating']) && intval($_GET['rating']) > 0 && intval($_GET['rating']) <= 5)
    $rating = intval($_GET['rating']);
else
    die(json_encode(array('error' => "Invalid rating")));

$user = getCurrentUser();
$ip = $_SERVER['REMOTE_ADDR'];

if ($user)
    $query = "SELECT id FROM ratings WHERE user='$user' AND country='$country'";
else
    $query = "SELECT id FROM ratings WHERE ip='$ip' AND user IS NULL AND country='$country' AND timestamp > DATE_SUB(CURDATE(), INTERVAL 7 DAY)"; 

$res = mysql_query($query);
if (mysql_num_rows($res) > 0) {
    while($row = mysql_fetch_row($res)) {
        mysql_query("DELETE FROM ratings WHERE id='$row[0]'");
    }
}

if (empty($user))
    $query = "INSERT INTO ratings (country, user, ip, rating) VALUES ('$country', NULL, '$ip', $rating);";
else
    $query = "INSERT INTO ratings (country, user, ip, rating) VALUES ('$country', '$user', '$ip', $rating);";

mysql_query($query);

echo json_encode(array(
    'country' => $country, 
    'rating' => getRating($country),
));

?>
