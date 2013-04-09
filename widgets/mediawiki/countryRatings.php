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
    function hwr_rateCountry(country, value) {
        if (value == 0)
            return;
        document.getElementById(\"hwr_rateselect_\"+country).style.display = 'none';
        var result = {}; 
        var rating;
        var count;
        var http_request = new XMLHttpRequest();
        http_request.open('GET', '/rate/rate.php?country='+country+'&rating='+value, true);
        http_request.onreadystatechange = function () {
            if (http_request.readyState == 4 && http_request.status == 200) {
            	//console.log('->hwr_rateCountry: http_request.onreadystatechange');
                result = JSON.parse(http_request.responseText);
                rating = result['rating']['rating'];
                roundRating = Math.round(rating);
                count = result['rating']['count'];
                document.getElementById('hwr_rating_'+country+'_value').innerHTML = rating;
                document.getElementById('hwr_rating_'+country+'_count').innerHTML = count;
            }
        };
        http_request.send(null);
    }
    </script>
    <style>
    .hwr_ratepopup {
    	display: none; 
    	position: absolute; 
    	padding: 5px; 
    	z-index: 999;
    	border: 1px solid #ccc;
	
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;;
		border-radius: 5px;
		
		background-color: #fff;
		
		
		-moz-box-shadow: 1px 3px 5px rgba(0,0,0,0.2);
		-webkit-box-shadow: 1px 3px 5px rgba(0,0,0,0.2);
		box-shadow: 1px 3px 5px rgba(0,0,0,0.2);
    }
    .hwr_rateOptions a {
    	text-align: left;
    }
    .hwr_current_rating {
    	font-size: 13px;
    	line-height: 13px;
    	clear: both;
    	padding: 0 0 5px 0;
    }
    .hwr_votes {
    	font-size: 11px;
    	line-height: 11px;
    	color: #ccc;
    }
    .hwr_rate {
    	font-weight: bold;
    }
    </style>
    <img src='$imgpath/hitch".round($rating['rating']).".png' /> <i>("._($ratingName[round($rating['rating'])]).")</i>
    <sup>[<a onclick='document.getElementById(\"hwr_rateselect_$country\").style.display = \"block\"'>"._("Rate!")."</a>]</sup>
    <span id='hwr_rateselect_$country' class='hwr_ratepopup'>
        <div class='hwr_current_rating'>
        	"._("Current rating").": 
        	<span class='hwr_rate'>
        		<span id='hwr_rating_$country"."_value'>".$rating['rating']."</span>/5
        	</span>
        	<span class='hwr_votes'>(<span id='hwr_rating_$country"."_count'>".$rating['count']."</span> "._('votes').")</span>
        </div>
        
        <b>"._("Your rating").":</b><br />
        <div class='btn-group btn-group-vertical hwr_rateOptions'>
        	<a class='btn btn-mini' onclick='hwr_rateCountry(\"$country\", 5);'><img src='$imgpath/hitch5.png' /> "._("Excellent")."</a>
        	<a class='btn btn-mini' onclick='hwr_rateCountry(\"$country\", 4);'><img src='$imgpath/hitch4.png' /> "._("Good")."</a>
        	<a class='btn btn-mini' onclick='hwr_rateCountry(\"$country\", 3);'><img src='$imgpath/hitch3.png' /> "._("Average")."</a>
        	<a class='btn btn-mini' onclick='hwr_rateCountry(\"$country\", 2);'><img src='$imgpath/hitch2.png' /> "._("Bad")."</a>
        	<a class='btn btn-mini' onclick='hwr_rateCountry(\"$country\", 1);'><img src='$imgpath/hitch1.png' /> "._("Almost impossible")."</a>
        </div>
        <br /><br />
        <a onclick='document.getElementById(\"hwr_rateselect_$country\").style.display = \"none\"' class='btn btn-mini'><i class='icon-ban-circle'></i> "._("Cancel")."</a>
    </span>
</span>
    ";

    return '<!-- ENCODED_CONTENT_RATING '.base64_encode($output).' -->';
}


?>
