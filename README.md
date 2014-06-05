# Hitchwiki Hitchability Rate

Rating system for [Hitchwiki.org](http://hitchwiki.org), rate "hitchability" in different countries.

## Overall
* 1-5 rating
* Only 1 vote per per country for ip/week or for user

## Install
* Upload folder to `extensions` folder and add `require_once("{$IP}/extensions/Rate/widgets/mediawiki/countryRatings.php");` to `LocalSettings.php`
* Make sure you have `[ratings.sql](src/ratings.sql)` and `[geo_countries.sql](src/geo_countries.sql)`

## Todo
* [FontAwesome](http://fontawesome.io) instead of images
* i18n, see [http://hitchwiki.org/translate/projects/rate](http://hitchwiki.org/translate/projects/rate)
* API should be integrated with [MediaWiki API](http://www.mediawiki.org/wiki/API:Extensions#Creating_API_modules_in_extensions)?

## API
* Now `/extensions/Rate/rate.php?country=[COUNTRY]&rating=[0-5]`

## Who
* [MrTweek](https://github.com/mrtweek)
* [Mikael](http://github.com/simison)
* [Contact us](http://hitchwiki.org/contact/)

## History
* First version of notes 7.8.2010
* Updated drafts 2.10.2010
* Partial rewrite to use PDO MySQL driver 6/2014
