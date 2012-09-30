<?php

include "functions.inc.php";

$imgpath = '/extensions/rate/img';

$wgExtensionFunctions[] = "wfcountryrating";

$wgExtensionCredits['parserhook'][] = array(
    'name' => 'Country Ratings',
    'author' => '[http://www.philippgruber.de/ Philipp Gruber]',
    'url' => 'http://github.com/Hitchwiki/rate.hitchwiki.org',
    'description' => 'Let users rate countries.'
);

function wfcountryrating() {
    global $wgParser, $wgHooks;
//    $wgParser->disableCache();
    $wgParser->setHook("rating", "countryrating");

    if (isset($wgHooks['ParserAfterTidy']) && is_array($wgHooks['ParserAfterTidy'])) {
        array_unshift($wgHooks['ParserAfterTidy'], 'ratingJS');
    } else {
        $wgHooks['ParserAfterTidy'] = array('ratingJS');
    }
}

// Add Javascript to <head>
function ratingJS(&$parser, &$text) {
    $text = preg_replace(
            '/<!-- ENCODED_CONTENT_RATING ([0-9a-zA-Z\/\\+]+=*) -->/esm',
            'base64_decode("$1")',
            $text);
    return true;
}

function countryrating($input, $argv) {

    $ratingName = array(
        0 => 'Unknown',
        1 => 'Almost impossible',
        2 => 'Bad',
        3 => 'Average',
        4 => 'Good',
        5 => 'Excellent',
    );
    global $imgpath;
    if (!isset($argv['country']) || strlen($argv['country']) != 2) {
        return "<span tyle='border: 1px solid red;'>No valid country specified</span>";
    }
    $country = strtoupper(mysql_real_escape_string($argv['country']));
    $rating = getRating($country);

$output = "
<span id='rating_$country' class='rating'>
    <script>
    function rateCountry(country, value) {
        if (value == 0)
            return;
        document.getElementById(\"rateselect_\"+country).style.display = 'none';
        var result = {}; 
        var rating;
        var count;
        var http_request = new XMLHttpRequest();
        http_request.open('GET', '/rate/rate.php?country='+country+'&rating='+value, true);
        http_request.onreadystatechange = function () {
            if (http_request.readyState == 4 && http_request.status == 200) {
                result = JSON.parse(http_request.responseText);
                rating = result['rating']['rating'];
                roundRating = Math.round(rating);
                count = result['rating']['count'];
                document.getElementById('rating_'+country+'_value').innerHTML = rating;
                document.getElementById('rating_'+country+'_count').innerHTML = count;
            }
        };
        http_request.send(null);
    }
    </script>
    <img src='$imgpath/hitch".round($rating['rating']).".png' /> <i>("._($ratingName[round($rating['rating'])]).")</i>
    <sup>[<a onclick='document.getElementById(\"rateselect_$country\").style.display = \"block\"'>"._("Rate!")."</a>]</sup>
    <span id='rateselect_$country' style='display: none; position: absolute; border: 1px solid blue; background-color: white; padding: 5px; z-index: 999;'>
        "._("Current rating").": 
        <span id='rating_$country"."_value' style='font-weight: bold;'>".$rating['rating']."</span>/5
        (<span id='rating_$country"."_count'>".$rating['count']."</span> "._('votes')."). <br /><br />
        <b>"._("Your rating").":</b><br />
        <a onclick='rateCountry(\"$country\", 5);'><img src='$imgpath/hitch5.png' />"._("Excellent")."</a><br />
        <a onclick='rateCountry(\"$country\", 4);'><img src='$imgpath/hitch4.png' />"._("Good")."</a><br />
        <a onclick='rateCountry(\"$country\", 3);'><img src='$imgpath/hitch3.png' />"._("Average")."</a><br />
        <a onclick='rateCountry(\"$country\", 2);'><img src='$imgpath/hitch2.png' />"._("Bad")."</a><br />
        <a onclick='rateCountry(\"$country\", 1);'><img src='$imgpath/hitch1.png' />"._("Almost impossible")."</a><br /><br />
        <a onclick='document.getElementById(\"rateselect_$country\").style.display = \"none\"' style='border: 1px solid blue; background-color: #EEE; padding: 2px;'>"._("Cancel")."</a>
    </span>
</span>
    ";

    return '<!-- ENCODED_CONTENT_RATING '.base64_encode($output).' -->';
}


?>
