
# rate.hitchwiki.org

Rating system for Hitchwiki.org - to use with different projects trough widgets and API.


# Log
* First version of notes 7.8.2010
* Updated drafts 2.10.2010


## Overall
* 1-5 rating
* only 1 vote per ip/week/country or so
* See [db-draft](http://github.com/Hitchwiki/rate.hitchwiki.org/blob/master/db-draft)


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

See [json-draft](http://github.com/Hitchwiki/rate.hitchwiki.org/blob/master/json-draft) for more.
