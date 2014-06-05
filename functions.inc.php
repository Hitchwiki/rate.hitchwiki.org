<?php

$conf = crReadConfig('/etc/hitchwiki/hitchwiki.conf');

$dblink = new PDO("mysql:host=".$conf['DB_HOST'].";dbname=hitchwiki_rate;charset=utf8", $conf['DB_USERNAME'], $conf['DB_PASSWORD']);
$dblink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
$dblink->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

$languages = array(
    'de' => 'de_DE',
    'en' => 'en_UK',
    'fi' => 'fi_FI',
    'es' => 'es_ES',
    'ru' => 'ru_RU',
    'lt' => 'lt_LT',
);

function crReadConfig($path = '/etc/hitchwiki/hitchwiki.conf') {
    $conf = array();
    $f = file($path);
    foreach ($f AS $l) {
        list($key, $val) = explode('=', trim($l), 2);
        $conf[$key] = $val;
    }
    return $conf;
}

/* Try to get Username from mediawiki */
function getCurrentUser() {
    return ($_SERVER['SERVER_NAME'] == 'hitchwiki.org' && isset($_COOKIE['hitchwiki_enUserName'])) ? $_COOKIE['hitchwiki_enUserName'] : '';
}

function getRating($country) {
    global $dblink;

    $sql = $dblink->prepare("SELECT AVG(rating) AS rating, COUNT(rating) AS count FROM ratings WHERE country=? GROUP BY country");
    $sql->execute(array($country));
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);

    return array('rating' => round($results[0]['rating'], 1), 'count' => intval($results[0]['count']));
}

function getCountryName($country, $lang) {
    global $dblink;

    $sql = $dblink->prepare("SELECT :lang as lang FROM `geo_countries` WHERE `iso` = :country LIMIT 1");
    $sql->bindValue(':country', $country);
    $sql->bindValue(':lang', $lang);
    $sql->execute();
    $results = $sql->fetchAll(PDO::FETCH_ASSOC);
    return $results[0]['lang'];
}
