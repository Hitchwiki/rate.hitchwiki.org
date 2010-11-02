<?php

$wgExtensionFunctions[] = "wfcountryrating";

$wgExtensionCredits['parserhook'][] = array(
    'name' => 'Country Ratings',
    'author' => '[http://www.philippgruber.de/ Philipp Gruber]',
    'url' => 'http://github.com/Hitchwiki/rate.hitchwiki.org',
    'description' => 'Let users rate countries.'
);


function wftravelmap() {
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
        return "<div style='border: 1px solid red;'>Invalid country</div>";
    }
    $country = $argv['country'];

$output = "
<div id='rating_$country' class='rating'>
    <script>
    function reload_$country() {
        
    } 
    </script>
    <span id='rating_$country"."_value'>?</span>/5 (<span id='rating_$country"."_count'></span> votes)<br />
</div>
    ";

    return '<!-- ENCODED_CONTENT_RATING '.base64_encode($output).' -->';
}


?>
