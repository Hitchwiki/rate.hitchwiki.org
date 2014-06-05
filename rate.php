<?php
/**
 * Hitchwiki Rate
 *
 * Insert new rate to DB
 */

include "functions.inc.php";

// Validate country
if (isset($_GET['country']) && strlen(trim($_GET['country'])) === 2) {
    $country = trim(strtoupper($_GET['country']));
}
else {
    die(json_encode(array('error' => "Invalid country")));
}

// Validate rating
if (isset($_GET['rating']) && is_numeric($_GET['rating']) && intval($_GET['rating']) > 0 && intval($_GET['rating']) <= 5) {
    $rating = intval($_GET['rating']);
}
else {
    die(json_encode(array('error' => "Invalid rating")));
}


// Check if database has entry with that user OR IP (max 7 days fresh)
$user = getCurrentUser();
$ip = $_SERVER['REMOTE_ADDR'];

if ($user) {
    $sql = $dblink->prepare("SELECT id FROM ratings WHERE user=:user AND country=:country");
    $sql->execute(array(':user' => $user, ':country' => $country));
}
else {
    $sql = $dblink->prepare("SELECT id FROM ratings WHERE ip=:ip AND user IS NULL AND country=:country AND timestamp > DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $sql->execute(array(':ip' => $ip, ':country' => $country));
}

// There indeed was already record, remove old one
if ($sql->rowCount() > 0) {
    $old_id = $sql->fetchAll(PDO::FETCH_ASSOC);
    $remove_old = $dblink->prepare("DELETE FROM ratings WHERE id=:id");
    $remove_old->bindValue(':id', $old_id[0]['id'], PDO::PARAM_STR);
    $remove_old->execute();
}

// Insert new one
if(empty($user)) {
    $new_rate = $dblink->prepare("INSERT INTO ratings (country, user, ip, rating) VALUES (:country, null, :ip, :rating)");
    $new_rate->execute(array(':country' => $country, ':ip' => $ip, ':rating' => $rating));
}
else {
    $new_rate = $dblink->prepare("INSERT INTO ratings (country, user, ip, rating) VALUES (:country, :user, :ip, :rating)");
    $new_rate->execute(array(':country' => $country, ':user' => $user, ':ip' => $ip, ':rating' => $rating));
}


// Return fresh hitchability after new rating
echo json_encode(array(
    'country' => $country,
    'rating' => getRating($country),
));
