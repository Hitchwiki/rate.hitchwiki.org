
# rate.hitchwiki.org

Rating system for Hitchwiki.org - to use with different projects trough widgets and API.

Notes 7.8.2010 by simison and MrTweek


## Overall
* 1-5 rating


## Widget
* Simple iframe-widget to use in other sites
* Query it with:
	* Country (required)
	* Language (default en_UK)
	* With/without a rating -btn (default: with)
	* With/without a rate (default: with)
	* background-color (default: #fff)
* Visual structure:

	Hitchability in Germany
	5/5
	Good

	What is your overall expression about hitchhiking in Germany?
	[ 1-2-3-4-5 ]


## API
* For the ajax-stuff we need to make a simple JSON-API anyway. We can give an address for people to use it.
* We have a list of countrynames/iso-codes in different languages already: en, fi, de, es, lt, ru. Useful with different wiki-languages.
* Protection from flood?
* Rating URL: ./?rate=5&country=de
	* Would add 5 to the db for Germany

./api/json/ (all)
{"world": [
    {
        "iso": "DE",
        "name": "Germany",
        "continent": "Europe",
        "rating": 5
    },
    {
        "iso": "FI",
        "name": "Finland",
        "continent": "Europe",
        "rating": 5
    },
   etc...
]}

./api/json/?country=de
{
        "iso": "DE",
        "name": "Germany",
        "continent": "Europe",
        "rating": 5
}

./api/json/?country=fi&lang=fi
{
        "iso": "FI",
        "name": "Suomi",
        "continent": "Eurooppa",
        "rating": 5
}

## Database
* timestamp, ip-address, country, rating
* only 1 vote per ip/week/country or so
