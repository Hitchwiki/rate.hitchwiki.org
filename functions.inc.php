<?php 


$conf = crReadConfig('/etc/hitchwiki/hitchwiki.conf');
$dbname = 'hitchwiki_ratings';

$crdb = @mysql_connect($conf['DB_HOST'], $conf['DB_USERNAME'], $conf['DB_PASSWORD']);

@mysql_select_db($dbname);

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
    if ($_SERVER['SERVER_NAME'] == 'hitchwiki.org' && isset($_COOKIE['pg_hitchwiki_enUserName']))
        return mysql_real_escape_string($_COOKIE['pg_hitchwiki_enUserName']);
    return '';
}

function getRating($country) {
    global $crdb;
    $query = "SELECT AVG(rating),COUNT(rating) FROM ratings WHERE country='$country' GROUP BY country";
    $r = mysql_fetch_row(mysql_query($query, $crdb));
    return array('rating' => round($r[0], 1), 'count' => intval($r[1]));
}

function getCountryName($country, $lang) {
    global $crdb;
    $query = "SELECT $lang FROM geo_countries WHERE iso = '$country'";
    $r = mysql_fetch_row(mysql_query($query, $crdb));
    return $r[0];

}

?>
