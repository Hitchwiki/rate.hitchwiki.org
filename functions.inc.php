<?php 

$dbhost = '127.0.0.1';
$dbuser = 'hitchwiki';
$dbpasswd = 'sk4ib8xh6is2';
$dbname = 'hitchwiki_ratings';

$db = mysql_connect($dbhost, $dbuser, $dbpasswd);
mysql_select_db($dbname);


/* Try to get Username from mediawiki */
function getCurrentUser() {
    if ($_SERVER['SERVER_NAME'] == 'hitchwiki.org' && isset($_COOKIE['pg_hitchwiki_enUserName']))
        return mysql_real_escape_string($_COOKIE['pg_hitchwiki_enUserName']);
    return '';
}

function getRating($country) {
    $query = "SELECT AVG(rating),COUNT(rating) FROM ratings WHERE country='$country' GROUP BY country";
    $r = mysql_fetch_row(mysql_query($query));
    return array('rating' => intval($r[0]), 'count' => intval($r[1]));
}

?>
