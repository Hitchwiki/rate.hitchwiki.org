<?php

include "functions.inc.php";

$wgExtensionFunctions[] = "wfcountryrating";

$wgExtensionCredits['parserhook'][] = array(
    'name' => 'Country Ratings',
    'author' => '[http://www.philippgruber.de/ Philipp Gruber]',
    'url' => 'http://github.com/Hitchwiki/rate.hitchwiki.org',
    'description' => 'Let users rate countries.'
);

function wfcountryrating() {
    global $wgParser, $wgHooks;
    global $wgParser;
    $wgParser->disableCache();
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
    if (!isset($argv['country']) || strlen($argv['country']) != 2) {
        return "<span tyle='border: 1px solid red;'>No country specified</span>";
    }
    $country = strtoupper(mysql_real_escape_string($argv['country']));
    $rating = getRating($country);

$output = "
<span id='rating_$country' class='rating'>
    <script>
    function rateCountry(country, select) {
        var value = select.options[select.options.selectedIndex].value;
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
                count = result['rating']['count'];
                document.getElementById('rating_'+country+'_value').innerHTML = rating;
                document.getElementById('rating_'+country+'_count').innerHTML = count;
            }
        };
        http_request.send(null);
    }
    </script>
    <span id='rating_$country"."_value'>".$rating['rating']."</span>/5 (<span id='rating_$country"."_count'>".$rating['count']."</span> votes). 
    <a onclick='document.getElementById(\"rateselect_$country\").style.display = \"block\"'>Rate!</a>
    <span id='rateselect_$country' style='display: none;'>
        <select name='rate_$country' onChange='rateCountry(\"$country\", this)'>
            <option value='0'>-- Please select --</option>
            <option value='5'>5 - Very good</option>
            <option value='4'>4 - Good</option>
            <option value='3'>3 - Average</option>
            <option value='2'>2 - Bad</option>
            <option value='1'>1 - Almost impossible</option>
        </select>
    </span>
</span>
    ";

    return '<!-- ENCODED_CONTENT_RATING '.base64_encode($output).' -->';
}


?>
